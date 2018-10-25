<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Saritasa\LaravelControllers\BaseController;

/**
 * This controller is responsible for handling password reset emails and
 * includes a trait which assists in sending these notifications from your application to your users.
 * Feel free to explore this trait.
 */
class ForgotPasswordController extends BaseController
{
    use SendsPasswordResetEmails;

    /**
     * This controller is responsible for handling password reset emails and
     * includes a trait which assists in sending these notifications from your application to your users.
     * Feel free to explore this trait.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
