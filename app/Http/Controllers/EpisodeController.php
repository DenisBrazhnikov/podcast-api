<?php

namespace App\Http\Controllers;

use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use Illuminate\Http\Request;
use App\Episode;
use App\Podcast;

class EpisodeController extends Controller
{
    protected $podcast = null;
    protected $episode = null;
    /**
     * Object constructor.
     * @param Podcast $podcast
     * @param Episode $episode
     */
    public function __construct(Podcast $podcast, Episode $episode)
    {
        $this->podcast = new PodcastRepository($podcast);
        $this->episode = new EpisodeRepository($episode);
    }
    /**
     * List all podcast episodes.
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        if(!$this->podcast->doesPodcastExist($slug))
        {
            return response()->json([
                'Message' => 'Podcast not found.'                
            ], 404);
        }
        
        return $this->episode->allEpisodes($slug);
    }
    /**
     * Create podcast episode.
     * @param string $slug
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create($slug, Request $request)
    {
        if(! $this->podcast->doesPodcastExist($slug))
        {
            return response()->json([
                'Message' => 'Podcast not found.'
            ], 404);
        }
        
        $episode = $request->file('episode');
        
        if($episode === NULL)
        {
            return response()->json([
                'Message' => 'Episode not provided.'
            ], 404);
        }
        
        $podcastName = $episode->getClientOriginalName();
        $podcastName = removeFileExtension($podcastName);        
        $podcastName = str_replace(['/', '-', ','], '', $podcastName);
        
        $path = "podcasts/$slug/episodes";
        $disk = "spaces";
        $request->file('episode')->store($path, $disk);
        
        return response()->json([
           'Message' => 'Successful' 
        ]);
    }
}
