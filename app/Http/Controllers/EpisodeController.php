<?php

namespace App\Http\Controllers;

use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use App\Episode;
use App\Podcast;

class EpisodeController extends Controller
{
    protected $podcast = null;
    protected $episode = null;
    
    public function __construct(Podcast $podcast, Episode $episode)
    {
        $this->podcast = new PodcastRepository($podcast);
        $this->episode = new EpisodeRepository($episode);
    }
    
    public function index($slug)
    {
        if($this->podcast->doesPodcastExist($slug))
        {
            return $this->episode->allEpisodes($slug);
        }
        else
        {
            return response()->json([
                'Message' => 'Podcast not found.'                
            ], 404);
        }
    }
    
    public function show($slug)
    {
        
    }
}
