<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Episode extends Model
{
    protected $guarded = [];
    /**
     * List all podcast episodes.
     * 
     * @param integer $podcastId
     * @return array
     */
    public function allEpisodes($podcastId)
    {
        $episodes = $this->where('podcasts_id', $podcastId)->get();
        
        $episodesCollection = array();
        
        foreach($episodes as $episode)
        {
            $file = Storage::disk('spaces')->files($episode['download_path']);
            
            array_push($episodesCollection, [
                'Id'            => $episode['id'],
                'PodcastsId'    => $episode['podcasts_id'],
                'Title'         => $episode['title'],
                'DownloadPath'  => $episode['download_path'],
                'EpisodeNumber' => $episode['episode_number'],
                'CreatedAt'     => Carbon::parse($episode['created_at'])->format('Y-m-d H:i:a'),
                'UpdatedAt'     => Carbon::parse($episode['updated_at'])->format('Y-m-d H:i:a'),
                'Episode'       => !empty($file) ? $file[0] : FALSE, 
            ]);            
        }
        
        return $episodesCollection;
    }
    /**
     * Get number of episodes for a given podcast.
     * 
     * @param integer $productId
     * @return integer
     */
    public static function getEpisodeCount($podcastId)
    {
        $episodeCount = self::where('podcasts_id', $podcastId)->count();
        
        return $episodeCount;
    }
    /**
     * Get errors of post request for this resource.
     * 
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public static function getPostErrors($request)
    {
        $errors = array();
        
        $episode = $request->file('episode');
        $title   = $request->input('title');
        $desc    = $request->input('description');
        
        if($episode === NULL)
        {
            array_push($errors, 'Episode not provided.');
        }
        elseif($episode->getClientOriginalExtension() != 'mp3')
        {
            array_push($errors, 'Invalid episode file type given.');
        }
        
        if($title === NULL)
        {
            array_push($errors, 'Title not provided');
        }
        elseif(strlen($title) < 5)
        {
            array_push($errors, 'TItle is less than 5 characters.');
        }
        elseif(strlen($title) > 191)
        {
            array_push($errors, 'Title is greater than 5 characters.');
        }
        
        if($desc === NULL)
        {
            array_push($errors, 'Description not provided.');
        }
        elseif(strlen($desc) < 5)
        {
            array_push($errors, 'Description is less than 10 characters.');
        }
        elseif(strlen($desc) > 191)
        {
            array_push($errors, 'Description is greater than 5 characters.');
        }
        
        return $errors;
    }
}
