<?php

return [
    'login_url' => env('SSO_LOGIN_URL', 'https://auth.cecula.com/login'),
    'verification_path' => env('SSO_VERIFICATION_ENDPOINT', 'https://auth.cecula.com/api/verify-sso'),
    'logout_url' => env('SSO_LOGOUT_URL', 'https://auth.cecula.com/logout'),
];
