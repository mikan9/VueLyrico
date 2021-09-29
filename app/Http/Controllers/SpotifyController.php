<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SpotifyController extends Controller
{
    public function getAuthUrl(Request $request)
    {
        $token = $request->session()->token();
        $token = csrf_token();

        $base_uri = 'https://accounts.spotify.com/authorize';
        $scopes = 'user-read-currently-playing user-read-playback-state';
        $redirect_uri = 'http://lyrico.test/api/spotify/callback';
        $client_id = env('SPOTIFY_CLIENT_ID', null);
        $response_type = 'code';

        return "{$base_uri}?client_id={$client_id}&response_type={$response_type}&redirect_uri={$redirect_uri}&scope={$scopes}&state={$token}";
    }

    public function getTokens(Request $request)
    {
        $base_uri = 'https://accounts.spotify.com/api/token';

        $client_id = env('SPOTIFY_CLIENT_ID', null);
        $client_secret = env('SPOTIFY_CLIENT_SECRET', null);

        $body_params = [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => 'http://lyrico.test/api/spotify/callback',
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];
       
        $response = Http::asForm()->withOptions([
            'verify' => false,
        ])->post($base_uri, $body_params);


        $response->throw();
        return redirect("?access_token={$response['access_token']}&expires_in={$response['expires_in']}&refresh_token={$response['refresh_token']}");
    }

    public function getCurrentlyPlaying(Request $request)
    {
        $base_uri = 'https://api.spotify.com/v1/me/player/currently-playing';

        $access_token = $request['access_token'];
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}"
        ])->withOptions([
            'verify' => false,
        ])->get("{$base_uri}?market=from_token");

        $response->throw();

        return $response;
    }
}