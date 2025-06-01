<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
      // Optional: Notify auth server
      // $logout_url = config('sso.logout');
      // Http::get(logout_url, [
      //     'user_id' => auth()->id()
      // ]);

      auth()->logout();
      return redirect(route('home'));
    }
}
