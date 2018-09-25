<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Podcast extends Model
{
    // Get all podcasts
    public function allPodcasts()
    {
        /**
         * Gets directories within podcasts folder
         * @var array $podcasts
         */
        $podcasts = Storage::disk('spaces')->directories('podcasts');
        /**
         * If podcasts directory is empty return empty json response;
         */
        if(empty($podcasts)) return response()->json();
        /**
         * Initialize array @var to store podcast names
         * @var array $podcastNames
         */
        $podcastNames = array();
        /**
         * Iterate over directories within podcasts folder
         */
        foreach($podcasts as $p)
        {
            /**
             * Extract the podcast name from the folder path
             * @var string $podcast
             */
            $podcast = explode('/', $p)[1];
            /**
             * Append the podcast name to the $podcastNames array
             */
            array_push($podcastNames, $podcast);
        }
        /**
         * Return json array with list of podcast names
         */
        return response()->json(['Podcasts' => $podcastNames]);
    }
    
    // Find whether podcast exists
    public function doesPodcastExist($podcast)
    {
        /**
         * Gets directories within given podcast folder.
         * @var array $podcasts
         */
        $podcasts = Storage::disk('spaces')->directories('podcasts/podcast-1');
        
        return !empty($podcasts) ? TRUE : FALSE;
    }
}
