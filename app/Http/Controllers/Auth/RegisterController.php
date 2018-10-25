<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Saritasa\LaravelControllers\BaseController;

/**
 * This controller handles the registration of new users as well as their validation and creation.
 * By default this controller uses a trait to provide this functionality without requiring any additional code.
 */
class RegisterController extends BaseController
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Validation factory.
     *
     * @var ValidationFactory
     */
    protected $validationFactory;

    /**
     * This controller handles the registration of new users as well as their validation and creation.
     * By default this controller uses a trait to provide this functionality without requiring any additional code.
     *
     * @param ValidationFactory $validationFactory Validation factory
     */
    public function __construct(ValidationFactory $validationFactory)
    {
        $this->middleware('guest');
        $this->validationFactory = $validationFactory;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param mixed[] $data Data to validate
     *
     * @return Validator
     */
    protected function validator(array $data): Validator
    {
        return $this->validationFactory->make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}
