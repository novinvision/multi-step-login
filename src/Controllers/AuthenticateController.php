<?php

namespace NovinVision\MultiStepLogin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use NovinVision\MultiStepLogin\Requests\AuthenticateRequest;

class AuthenticateController extends Controller
{
    public function index(AuthenticateRequest $request)
    {
        return view('novinvision.multi-step-login::username');
    }

    public function username(AuthenticateRequest $request)
    {
        $request->validate([
            'username' => 'required|auth_username',
        ]);

        $user = $request->getUserWithUsername($request->post('username'));
        if ($user) {
            return redirect()->route('login.password')->with('username', $request->post('username'));
        }

        return redirect()->route('register')->with('username', $request->post('username'));
    }

    public function register(AuthenticateRequest $request)
    {
        return view('novinvision.multi-step-login::register', [
            'oldData' => $this->getOldData($request)
        ]);
    }

    public function store(AuthenticateRequest $request)
    {
        $validateRules = [];

        foreach (config('multi-step-login.register_columns') as $key => $field) {
            $validateRules[$key] = $field['validation'] ?? ['required'];
        }

        $validateRules = array_merge($validateRules, [
            'password' => ['required', 'string', 'min:5'],
            'password_confirm' => ['same:password'],
        ]);

        $request->validate($validateRules);

        $request->userModel()->newQuery()->create($request->only([
            ...array_keys(config('multi-step-login.register_columns')),
            'password'
        ]));

        Auth::attempt($request->only([
            ...config('multi-step-login.username_columns'),
            'password'
        ]), true);

        return redirect($request->nextUrl());
    }

    public function password(AuthenticateRequest $request)
    {
        return view('novinvision.multi-step-login::password', [
            'username' => $request->session()->get('username'),
            'oldData' => $this->getOldData($request)
        ]);
    }

    public function loginPassword(AuthenticateRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:250',
            'password' => ['required', 'string']
        ]);

        $authData = [
            'password' => $request->post('password'),
        ];

        if (filter_var($request->post('username'), FILTER_VALIDATE_EMAIL)) {
            $authData['email'] = $request->post('username');
        } else {
            $authData['mobile'] = $request->post('username');
        }

        if (!Auth::attempt($authData, true)) {
            return back()->withErrors("اطلاعات وارد شده نامعتبر است")->with([
                'username' => $request->post('username')
            ]);
        }

        return redirect($request->nextUrl());
    }

    protected function getOldData(Request $request)
    {
        $username = $request->old('username') ?: $request->session()->get('username');
        return [
            'mobile' => is_numeric($username) ? $username : '',
            'email' => filter_var($username, FILTER_VALIDATE_EMAIL) ? $username : '',
        ];
    }
}
