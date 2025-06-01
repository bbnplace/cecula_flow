# Authentication Flow
1. User visits App1:
Middleware detects no session
Redirects to auth server with return URL

2. Auth Server:

Presents login form
Creates central session
Generates one-time token
Redirects back to App1 with token

3. App1:

Receives token
Verifies with auth server
Creates local user if needed
Establishes local session

4. User visits App2:

Middleware checks local session
If none, redirects to auth server
Auth server detects existing central session
Generates new token for App2
App2 creates its own local session


# Guide for Setup

## Create RedirectIfUnauthenticated middleware

```
php artisan make:middleware RedirectIfUnauthenticated
```

## Save the Single Sign-On URL to a config file.
```
touch config/sso.php
```

## Paste the code below into config/sso.php
```
return [
    'login_url' => env('SSO_LOGIN_URL', 'https://auth.cecula.com'),
];
```
Also add SSO_LOGIN_URL=https://auth.cecula.com directive to the .env file

## Copy the code below into the middleware file
```
public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (!Auth::guard($guard)->check()) {
            $ssoLoginUrl = config('sso.login_url');
            $redirectUrl = $ssoLoginUrl . '?site='.config('app.name').'&redirect=' . urlencode($request->fullUrl());

            return redirect()->away($redirectUrl);
        }
    }

    return $next($request);
}
```

## Register the Middleware
Open the file **bootstrap/app.php**

Append the RedirectIfUnauthenticated middleware to the list of middlewares.
```
$middleware->append(RedirectIfUnauthenticated::class);
```


## Protect your routes with the RedirectIfUnauthenticated middleware.
```
Route::middleware(['auth'])->group(function() {
  Route::get('/', function () {
      return view('welcome');
  });
});
```

## Create another middleware for verying sso token when returned to the Server
```
php artisan make:middleware VerifySSOSession
```





# 4. Key Components
## A. Token System:

One-time use tokens (5 minute expiry)
Stored in Redis/DB cache
Contains user ID and session reference

## B. Session Verification:

Auth server provides API endpoint
Clients verify tokens via HTTP
Redis used to check session validity

## C. Local User Sync:

Each app maintains its own users table
Maps to central auth server's user ID
Can add app-specific user fields
# cecula_flow
