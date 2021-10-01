<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

use App\Models\SpotifyTokens;

class SpotifyController extends Controller
{
    public function getAuthUrl(Request $request)
    {
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
        if($request->state && $request->state != csrf_token()) 
        {
            abort(403, 'Invalid CSRF Token');    
        }
        
        $tokens = SpotifyTokens::where('user_id', 1)->first();
        $auth_code = $tokens->auth_code;

        if(!$request->code && !$auth_code) 
        {
            return abort(401);
        }

        if($request->code) 
        {
            $tokens->auth_code = $request->code;
            $tokens->save();
        }

        $auth_code = $tokens->auth_code;
       
        $base_uri = 'https://accounts.spotify.com/api/token';

        $client_id = env('SPOTIFY_CLIENT_ID', null);
        $client_secret = env('SPOTIFY_CLIENT_SECRET', null);

        $body_params = [
            'grant_type' => 'authorization_code',
            'code' => $auth_code,
            'redirect_uri' => 'http://lyrico.test/api/spotify/callback',
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];

        $response = Http::asForm()->withOptions([
            'verify' => false,
        ])->post($base_uri, $body_params);

        if($response->status() == 400) {
            return abort(401, explode('error_description', $response->body())[1], $response->headers());
        }

        $response->throw();
  
        $tokens->access_token = $response['access_token'];
        $tokens->refresh_token = $response['refresh_token'];
        $tokens->expires_in = $response['expires_in'];
        $tokens->save();

        return back();
    }

    public function refreshToken()
    {
        $tokens = SpotifyTokens::where('user_id', 1)->first();

        if(!$tokens->refresh_token) 
        {
            return abort(401);
        }

        $base_uri = 'https://accounts.spotify.com/api/token';

        $client_id = env('SPOTIFY_CLIENT_ID', null);
        $client_secret = env('SPOTIFY_CLIENT_SECRET', null);

        $body_params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $tokens->refresh_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];
        
        $response = Http::asForm()->withOptions([
            'verify' => false,
        ])->post($base_uri, $body_params);

        if($response->status() == 400) {
            return abort(401);
        }

        if($response->status() == 401) {
            $this->getTokens();
        }

        $response->throw();

        $tokens->access_token = $response['access_token'];
        $tokens->expires_in = $response['expires_in'];
        $tokens->save();

        return $response;
    }

    public function getCurrentlyPlaying(Request $request)
    {
        $tokens = SpotifyTokens::where('user_id', 1)->first();
        $access_token = $tokens->access_token;
        $refresh_token = $tokens->refresh_token;
        
        if(!$access_token || !$refresh_token) 
        {
            $this->getTokens($request);
            $this->getCurrentlyPlaying($request);
        }

        $base_uri = 'https://api.spotify.com/v1/me/player/currently-playing';
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}"
        ])->withOptions([
            'verify' => false,
        ])->get("{$base_uri}?market=from_token");

        if($response->failed()) 
        {
            $this->refreshToken();
            $this->getCurrentlyPlaying($request);
        }

        $response->throw();

        return $response;
    }
}