<?php

namespace App\Http\Controllers;

use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Episode;
use App\Podcast;

class EpisodeController extends Controller
{
    protected $podcast = null;
    protected $episode = null;
    /**
     * Object constructor.
     * 
     * @param  Podcast  $podcast
     * @param  Episode  $episode
     */
    public function __construct(Podcast $podcast, Episode $episode)
    {
        $this->podcast = new PodcastRepository($podcast);
        $this->episode = new EpisodeRepository($episode);
    }
    /**
     * List all podcast episodes.
     * 
     * @param   string  $podcastSlug
     * @return  \Illuminate\Http\Response
     */
    public function index($podcastSlug)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->first();
        
        if(!$this->podcast->doesPodcastExistDb($podcastSlug) || !$this->podcast->doesPodcastExistDisk($podcast['download_path']))
        {
            return response()->json([
                'Message' => 'Podcast not found.'                
            ], 404);
        }        
        
        $episodes = $this->episode->allEpisodes($podcast->id);
        
        return response()->json($episodes);
    }
    /**
     * Create podcast episode.
     * 
     * @param   string   $podcastSlug
     * @param   Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function create($podcastSlug, Request $request)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->first();
        
        if(!$this->podcast->doesPodcastExistDb($podcastSlug) || !$this->podcast->doesPodcastExistDisk($podcast['download_path']))
        {
            return response()->json([
                'Message' => 'Podcast not found.'
            ], 404);
        }
        /**
         * Store file object if uploaded.
         * 
         * @var \Illuminate\Http\Request $episode
         */
        $episodeFile = $request->file('episode');
        /**
         * Initialize var to hold errors for this request.
         * 
         * @var array $errors
         */
        $errors = Episode::getPostErrors($request);
        
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
         * Store file name.
         * 
         * @var string $podcastName
         */
        $podcastName = $episodeFile->getClientOriginalName();
        /**
         * Remove extension from filename.
         */
        $podcastName = removeFileExtension($podcastName);     
        /**
         * Remove unwanted characters from filename.
         */
        $podcastName = str_replace(['/', '-', ','], '', $podcastName);
        /**
         * Get episode count.
         * 
         * @var integer $episodeCount
         */
        $episodeCount = Episode::getEpisodeCount($podcast->id);
        /**
         * Initialize upload path variable.
         * 
         * @var string $path
         */
        $path = sprintf("podcasts/%s/episodes/%d", $podcastSlug, ++$episodeCount);
        /**
         * Initialize disk variable.
         * 
         * @var string $disk
         */
        $disk = "spaces";
        /**
         * Store file on disk.
         */
        $episodeFile->store($path, $disk);
        /**
         * Store episode data in database.
         */
        Episode::create([
            'podcasts_id'    => $podcast->id,
            'download_path'  => $path,
            'title'          => filter_var($request->input('title'), FILTER_SANITIZE_STRING),
            'description'    => filter_var($request->input('description'), FILTER_SANITIZE_STRING),
            'episode_number' => $episodeCount,
        ]);
        /**
         * Return JSON response.
         */
        return response()->json([
           'Message' => 'Successful' 
        ]);
    }
    
    /**
     * Download a podcast episode.
     * 
     * @param   string   $podcastSlug
     * @param   integer  $episodeNumber
     * @return  \Illuminate\Http\Response
     */
    public function show($podcastSlug, $episodeNumber)
    {
        $podcast = Podcast::where('slug', $podcastSlug)->first();
        
        if(!$this->podcast->doesPodcastExistDb($podcastSlug) || !$this->podcast->doesPodcastExistDisk($podcast['download_path']))
        {
            return response()->json([
                'Message' => 'Podcast not found.'
            ], 404);
        }
        
        $episode = Episode::where('episode_number', $episodeNumber)->first();
        
        if($episode === NULL)
        {
            return response()->json([
                'Message' => 'Episode not found.'
            ], 400);
        }
        $file = Storage::disk('spaces')->files($episode['download_path']);
        
        if(empty($file))
        {
            return response()->json([
                'Message' => 'Unsuccessful',
                'Errors'  => [
                    'Unable to retrieve file from storage.'
                ]
            ]); 
        }
        
        return Storage::disk('spaces')->download($file[0]);
    }
}
