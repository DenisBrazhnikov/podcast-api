<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Episode extends Model
{
    public function allEpisodes($podcast)
    {
        /**
         * Gets podcast episodes
         * @var array $episodes
         */
        $episodes = Storage::disk('spaces')->files("podcasts/$podcast/episodes");
        
        $episodeNames = array();
        
        foreach($episodes as $episode)
        {
            $episodeName = explode('/', $episode)[3];
            $episodeName = explode('.', $episodeName)[0];
            
            array_push($episodeNames, $episodeName);
        }
        
        return response()->json([
            'Episodes' => $episodeNames
        ]);
    }
}
