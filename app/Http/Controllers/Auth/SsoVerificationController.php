<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SsoVerificationController extends Controller
{
    public function auth()
    {
      return redirect(route('home'));
    }
}
