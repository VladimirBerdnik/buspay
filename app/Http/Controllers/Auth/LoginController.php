<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Saritasa\LaravelControllers\BaseController;

/**
 * This controller handles authenticating users for the application and redirecting them to your home screen.
 * The controller uses a trait to conveniently provide its functionality to your applications.
 */
class LoginController extends BaseController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * This controller handles authenticating users for the application and redirecting them to your home screen.
     * The controller uses a trait to conveniently provide its functionality to your applications.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
