<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Saritasa\LaravelControllers\BaseController;

/**
 * Controller for home page.
 */
class HomeController extends BaseController
{
    /**
     * Home page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('home.index');
    }
}
