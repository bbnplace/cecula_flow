<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerifySSOSession
{
  public function handle($request, Closure $next)
  {
      if ($request->has('sso_token')) {
        Log::info("Found SSO Token: " . $request->sso_token);
        $verificationUrl = config('sso.verification_path');
        Log::info("SSO Token Verification path: " . $verificationUrl);
          $response = Http::get($verificationUrl, [
              'token' => $request->sso_token
          ]);

          Log::info("Verification Response: " . json_encode($response->json()));

          if ($response->successful()) {
              $data = $response->json();
              $user = $this->syncUser($data['user']);

              // Log in the user locally
              auth()->login($user);

              // Set session cookie
              session()->regenerate();

              return redirect($request->path());
          }
      }

      if (!auth()->check()) {
          $loginUrl = config('sso.login_url');
          return redirect()->away($loginUrl . '?redirect='.urlencode($request->fullUrl()));
      }

      return $next($request);
  }

  protected function syncUser($userData)
  {
      return User::updateOrCreate(
          ['email' => $userData['email']],
          [
              'name' => $userData['name'],
              'auth_server_id' => $userData['id']  // Reference to central auth
          ]
      );
  }
}
