<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * One-time Gmail OAuth2 authorization flow.
 *
 * Step 1 — Admin visits /admin/gmail/oauth   → redirected to Google
 * Step 2 — Google redirects to /admin/gmail/oauth/callback with ?code=…
 * Step 3 — We exchange code → tokens, display refresh_token to copy into .env
 */
class GmailOAuthController extends Controller
{
    private const SCOPE = 'https://mail.google.com/';

    public function index()
    {
        $configured = !empty(config('services.gmail.refresh_token'));

        return view('admin.gmail-oauth', [
            'configured' => $configured,
            'fromEmail'  => config('mail.from.address'),
        ]);
    }

    public function redirect()
    {
        $clientId    = config('services.gmail.client_id');
        $redirectUri = route('admin.gmail.callback');

        if (empty($clientId)) {
            return back()->with('error', 'GOOGLE_CLIENT_ID is not set in .env');
        }

        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => self::SCOPE,
            'access_type'   => 'offline',
            'prompt'        => 'consent',
            'state'         => csrf_token(),
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        if ($request->get('state') !== csrf_token()) {
            abort(403, 'Invalid state parameter.');
        }

        if ($request->has('error')) {
            return redirect()->route('admin.gmail.index')
                ->with('error', 'Google authorization denied: ' . $request->get('error'));
        }

        $code        = $request->get('code');
        $clientId    = config('services.gmail.client_id');
        $clientSecret = config('services.gmail.client_secret');
        $redirectUri = route('admin.gmail.callback');

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'code'          => $code,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code',
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (empty($response['refresh_token'])) {
            return redirect()->route('admin.gmail.index')
                ->with('error', 'No refresh_token in response: ' . json_encode($response));
        }

        return view('admin.gmail-oauth', [
            'configured'   => true,
            'fromEmail'    => config('mail.from.address'),
            'refreshToken' => $response['refresh_token'],
            'accessToken'  => $response['access_token'],
        ]);
    }
}
