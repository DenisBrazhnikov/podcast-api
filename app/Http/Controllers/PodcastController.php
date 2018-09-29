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
     * 
     * @param Podcast $podcast
     */
    public function __construct(Podcast $podcast, Episode $episode)
    {
        $this->podcast = new PodcastRepository($podcast);
        $this->episode = new EpisodeRepository($episode);
    }
    /**
     * List all podcasts.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $podcasts = $this->podcast->allPodcasts();
        
        for($i=0; $i < sizeof($podcasts); $i++)
        {
            $podcasts[$i] = array_merge($podcasts[$i], [
                'Episodes' => $this->episode->allEpisodes($podcasts[$i]['Id']),
            ]);
        }
        
        return response()->json($podcasts);
    }
    
    /**
     * Get a specific podcast and it's related data.
     * 
     * @param  string  $podcastSlug
     * @return \Illuminate\Http\Response
     */
    public function show($podcastSlug)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->first();
        
        if(!$this->podcast->doesPodcastExistDb($podcastSlug) || !$this->podcast->doesPodcastExistDisk($podcast['download_path']))
        {
            return response()->json([
                'Message' => 'Podcast not found.'
            ], 404);
        }
        
        $podcast = $this->podcast->getPodcast($podcastSlug);
        
        $podcast = array_merge($podcast, ['Episodes'=>$this->episode->getPodcastEpisodes($podcast['Id'])]);
        
        return response()->json($podcast);
    }
}
