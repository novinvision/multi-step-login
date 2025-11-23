<?php

namespace NovinVision\MultiStepLogin\Controllers;

use App\Http\Requests\Request;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use NovinVision\MultiStepLogin\Models\UserVerification;
use NovinVision\MultiStepLogin\Requests\AuthenticateRequest;

class VerifyController extends Controller
{
    public function index(Request $request, string $field = 'mobile'): \Illuminate\Contracts\View\View
    {
        $this->resend($request, $field);

        return view('novinvision.multi-step-login::verify', [
            'field' => $field,
            'countdown' => RateLimiter::availableIn("otp_verification_countdown.$field"),
            'username' => $request->user()->{$field} ?? null,
        ]);
    }

    public function verify(Request $request, string $field = 'mobile'): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $code = UserVerification::verify($request->user(), $request->post('code'), $field);
        if (!$code) {
            return back()->withErrors(trans('novinvision.multi-step-login::multi-step-login.invalid-verify-code-error'));
        }

        $request->user()->forceFill([
            "{$field}_verified_at" => now(),
        ])->save();

        $code->delete();

        return redirect()->to(urldecode($request->get('redirect')) ?: url(''));
    }

    public function resend(Request $request, string $field = 'mobile'): \Illuminate\Http\RedirectResponse
    {
        if (!RateLimiter::tooManyAttempts("otp_verification_countdown.$field", 1)) {
            $code = UserVerification::make($request->user(), $field);

            $notifyClass = config('multi-step-login.verify_notification');
            $request->user()->notify(new $notifyClass($code));

            RateLimiter::hit("otp_verification_countdown.$field", config("multi-step-login.verify_resend_seconds.{$field}", 60));
        }

        return back()->with("کد تایید ارسال شد");
    }
}
