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
     * @param   array  $data
     * @return  array
     */
    public static function getPostErrors($data)
    {
        $errors = array();
        
        if($data['episode'] === NULL)
        {
            array_push($errors, 'Episode not provided.');
        }
        elseif($data['episode']->getClientOriginalExtension() != 'mp3')
        {
            array_push($errors, 'Invalid episode file type given.');
        }
        
        if($data['title'] === NULL)
        {
            array_push($errors, 'Title not provided');
        }
        elseif(strlen($data['title']) < 5)
        {
            array_push($errors, 'TItle is less than 5 characters.');
        }
        elseif(strlen($data['title']) > 191)
        {
            array_push($errors, 'Title is greater than 5 characters.');
        }
        
        if($data['description'] === NULL)
        {
            array_push($errors, 'Description not provided.');
        }
        elseif(strlen($data['description']) < 5)
        {
            array_push($errors, 'Description is less than 10 characters.');
        }
        elseif(strlen($data['description']) > 191)
        {
            array_push($errors, 'Description is greater than 5 characters.');
        }
        
        return $errors;
    }
    
    /**
     * Get episodes for a given podcast.
     * 
     * @param  integer $podcastId
     * @return array
     */
    public function getPodcastEpisodes($podcastId)
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
     * Get errors of patch request for this resource. 
     * 
     * @param  array  $data
     * @param  array                     
     */
    public static function getPatchErrors($data)
    {
        $errors = array();
        
        if(empty($data['episode']) && empty($data['title']) && empty($data['description']))
        {
            array_push($errors, 'No data has been provided.');
        }
        else
        {
            if(!empty($data['episode']))
            {
                if($data['episode']->getClientOriginalExtension() != 'mp3')
                {
                    array_push($errors, 'Invalid episode file type given.');
                }
            }
            
            if(!empty($data['title']))
            {
                if(strlen($data['title']) < 5)
                {
                    array_push($errors, 'TItle is less than 5 characters.');
                }
                elseif(strlen($data['title']) > 191)
                {
                    array_push($errors, 'Title is greater than 5 characters.');
                }
            }
            
            if(!empty($data['description']))
            {
                if(strlen($data['description']) < 5)
                {
                    array_push($errors, 'Description is less than 10 characters.');
                }
                elseif(strlen($data['description']) > 191)
                {
                    array_push($errors, 'Description is greater than 5 characters.');
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Update an instance of this model.
     * 
     * @param  array  $data
     */
    public function updateEpisode($data)
    {
        $updateClause = array();
        
        if($data['episode'] !== NULL)
        {
            /**
             * Initialize var to store file object from disk.
             * @var Storage $file
             */
            $file = Storage::disk('spaces')->files($this->attributes['download_path']);
            
            if(!empty($file))
            {
                
                $this->deleteEpisode($file[0]);
            }
            /**
             * Replace episode.
             */
            $data['episode']->store($this->attributes['download_path'], 'spaces');
        }
        
        if(!empty($data['title']))
        {
            $updateClause['title'] = $data['title'];
        }
        
        if(!empty($data['description']))
        {
            $updateClause['description'] = $data['description'];
        }
        
        if(empty($updateClause))
        {
            /**
             * Set updated_at field to current datetime if only episode file has been updated.
             */
            $this->update([
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),   
            ]);
        }
        else
        {
            /**
             * Update model instance using update clause array.
             */
            $this->update($updateClause);
        }
    }
    
    /**
     * Delete a podcast episode.
     * 
     * @param  string  $path
     */
    public function deleteEpisode($path)
    {
        Storage::disk('spaces')->delete($path);
    }
}
