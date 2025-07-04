<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileSettingsController extends Controller
{
    public function edit()
    {
        return view('profile.settings.edit');
    }

    public function password()
    {
        return view('profile.settings.password');
    }
}
