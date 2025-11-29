<?php

namespace NovinVision\MultiStepLogin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use NovinVision\MultiStepLogin\Requests\AuthenticateRequest;

class AuthenticateController extends Controller
{
    public function index(AuthenticateRequest $request)
    {
        return view('multi-step-login::username');
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
        return view('multi-step-login::register', [
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
        return view('multi-step-login::password', [
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

    public function forgetPassword(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('multi-step-login::forget-password');
    }

    public function requestForgetPassword(AuthenticateRequest $request)
    {
        $request->validate([
            'username' => 'required|auth_username',
        ]);

        $user = $request->getUserWithUsername($request->post('username'));
        if (!$user) {
            return back()->withErrors(__('novinvision.multi-step-login::multi-step-login.username_not_exists'));
        }

        $status = Password::sendResetLink(['id' => $user->getKey()]);
        if ($status != 'passwords.sent') {
            return back()->withErrors(__($status));
        }

        return redirect()->route('forget-password-verify')->with('username', $request->post('username'));
    }

    public function forgetPasswordVerify(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $username = $request->session()?->get('username', $request->get('username'));
        if (!$username) return redirect()->route('forget-password');

        return view('multi-step-login::forget-password-verify', [
            'username' => $username,
        ]);
    }

    public function forgetNewPassword(Request $request, string $hash): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        try {
            $data = Crypt::decrypt($hash);
            if(!$data) throw new \Exception("invalid hash");
        }catch (\Exception $exception){
            return redirect()->route('forget-password')->withErrors(
                __('novinvision.multi-step-login::multi-step-login.forget_password_verify_hash_invalid')
            );
        }

        $user = $data['user_type']::findOrFail($data['user_id']);
        if(!$user) return redirect()->route('forget-password')->withErrors(
            __('novinvision.multi-step-login::multi-step-login.username_not_exists')
        );

        if (!Password::tokenExists($user, $data['token'])) {
            return redirect()->route('forget-password')->withErrors(
                __('novinvision.multi-step-login::multi-step-login.invalid-verify-code-error')
            );
        }

        return view('multi-step-login::forget-password-change', [
            'user' => $user
        ]);
    }

    public function forgetNewPasswordSubmit(Request $request, string $hash): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        try {
            $data = Crypt::decrypt($hash);
            if(!$data) throw new \Exception("invalid hash");
        }catch (\Exception $exception){
            return redirect()->route('forget-password')->withErrors(
                __('novinvision.multi-step-login::multi-step-login.forget_password_verify_hash_invalid')
            );
        }

        $user = $data['user_type']::findOrFail($data['user_id']);
        if(!$user) return redirect()->route('forget-password')->withErrors(
            __('novinvision.multi-step-login::multi-step-login.username_not_exists')
        );

        $request->validate([
           'password' => 'required|string|min:6',
           'password_confirm' => 'required|string|same:password',
        ]);

        $user->forceFill([
            'password' => bcrypt($request->post('password')),
        ])->save();

        return redirect()->route('login.password');
    }

}
