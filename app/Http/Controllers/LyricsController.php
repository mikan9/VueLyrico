<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

use App\Models\Lyrics;

class LyricsController extends Controller
{
    public function show(Lyrics $lyrics, $source_type, $artist, $title)
    {
        if(!$lyrics->exists) {
            $base_uri = env('MUSIXMATCH_BASE_URL');
            $response = Http::withOptions([
                'verify' => false,
            ])->get($base_uri.'matcher.lyrics.get?apikey='.env('MUSIXMATCH_API_KEY').'&q_track='.urlencode($title).'&q_artist='.urlencode($artist));

            if($response['message']['header']['status_code'] == 200) {
                $body = $response['message']['body']['lyrics'];

                $lyrics = Lyrics::create([
                    'artists' => $artist,
                    'title' => $title,
                    'content' => $body['lyrics_body'],
                    'source_id' => $body['lyrics_id'],
                    'source_type' => $source_type,
                    'tracking_url' => $body['pixel_tracking_url'],
                    'copyright' => $body['lyrics_copyright'],
                ]);

                return $lyrics;
            }

            abort(404);
        }

        return $lyrics;
    }
}