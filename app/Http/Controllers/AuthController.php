<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function login(Request $request): View
    {
        return view("login", []);
    }

    function loginPost(Request $request): RedirectResponse
    {
        return redirect("/");
    }
}
