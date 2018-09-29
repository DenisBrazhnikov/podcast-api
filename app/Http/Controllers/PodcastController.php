<?php

namespace App\Http\Controllers;

use App\Repositories\PodcastRepository;
use App\Repositories\EpisodeRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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
    
    /**
     * Create a podcast.
     * 
     * @param   string                    $podcastSlug
     * @param   \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Reponse
     */
    public function create(Request $request)
    {
        /**
         * Get errors for this request.
         * 
         * @var array $errors
         */
        $errors = Podcast::getPostErrors($request);
        
        if(!empty($errors))
        {
            /**
             * Return JSON response list of errors to client.
             */
            return response()->json([
                'Message' => 'Invalid input data.',
                'Errors'  => $errors,
            ], 400);
        }
        
        /**
         * Initialize vars to store sanitized fields.
         */
        $name = filter_var($request->input('name'), FILTER_SANITIZE_STRING);
        
        /**
         * Remove unwanted characters from $name.
         */
        $name = str_replace(['/', '-', ','], '', $name);
        
        $slug          = str_slug($name);
        $download_path = "podcasts/$slug";
        
        Storage::disk('spaces')->makeDirectory("$download_path/episodes");
        
        Podcast::create([
            'slug'          => $slug,
            'name'          => $name,
            'download_path' => $download_path,
        ]);
        
        return response()->json([
            'Message' => 'Successful',
        ]);
    }
    
    /**
     * Delete a podcast.
     * 
     * @param   string                     $podcastSlug
     * @param   \Illuminate\Http\Request   $request
     * @return  \Illuminate\Http\Response
     */
    public function delete($podcastSlug, Request $request)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->first();
        
        if(!$this->podcast->doesPodcastExistDb($podcastSlug) || !$this->podcast->doesPodcastExistDisk($podcast['download_path']))
        {
            return response()->json([
                'Message' => 'Podcast not found.'
            ], 404);
        }
        /**
         * Delete directory from spaces.
         */
        Storage::disk('spaces')->deleteDirectory($podcast['download_path']);
        /**
         * Delete database data.
         */
        Episode::where('podcasts_id', $podcast->id)->delete();
        $podcast->delete();
        
        return response()->json([
            'Message' => 'Successful',
        ]);
    }
}
