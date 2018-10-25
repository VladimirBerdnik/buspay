<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Saritasa\LaravelControllers\BaseController;

/**
 * This controller is responsible for handling password reset requests and uses a simple trait to include this behavior.
 * You're free to explore this trait and override any methods you wish to tweak.
 */
class ResetPasswordController extends BaseController
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * This controller is responsible for handling password reset requests
     * and uses a simple trait to include this behavior.
     * You're free to explore this trait and override any methods you wish to tweak.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
