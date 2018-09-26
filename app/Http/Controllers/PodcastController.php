<?php

namespace App\Http\Controllers;

use App\Repositories\PodcastRepository;
use App\Repositories\EpisodeRepository;
use App\Podcast;
use App\Episode;

class PodcastController extends Controller
{
    protected $podcast = null;
    protected $episode = null;
    /**
     * Object constructor.
     * @param Podcast $podcast
     */
    public function __construct(Podcast $podcast, Episode $episode)
    {
        $this->podcast = new PodcastRepository($podcast);
        $this->episode = new EpisodeRepository($episode);
    }
    /**
     * List all podcasts.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $podcasts = json_decode($this->podcast->allPodcasts(), TRUE);
        
        for($i=0; $i < sizeof($podcasts); $i++)
        {
            $podcasts[$i] = array_merge($podcasts[$i], [
                'Episodes' => json_decode($this->episode->allEpisodes($podcasts[$i]['Id']), TRUE),
            ]);
        }
        
        return response()->json($podcasts);
    }
}
