<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Episode;

class Podcast extends Model
{
    protected $guarded = [];
    
    /**
     * Get all podcasts.
     * 
     * @return array
     */
    public function allPodcasts()
    {
        $podcasts = $this->all();
        
        $podcastsCollection = array();
        
        foreach($podcasts as $podcast)
        { 
            array_push($podcastsCollection, [
                'Id'           => $podcast['id'],
                'Slug'         => $podcast['slug'],
                'Name'         => $podcast['name'],
                'DownloadPath' => $podcast['download_path'],
                'CreatedAt'    => Carbon::parse($podcast['created_at'])->format('Y-m-d H:i:s'),
                'UpdatedAt'    => Carbon::parse($podcast['updated_at'])->format('Y-m-d H:i:s'),
            ]);
        }
        
        /**
         * Return array with list of podcast names
         */
        return $podcastsCollection;
    }
    
    /**
     * Find whether podcast exists on database.
     * 
     * @param string $slug
     * @return boolean
     */
    public function doesPodcastExistDb($slug)
    {
        $podcast = $this->where('slug', $slug)->first();
        
        return $podcast !== NULL ? TRUE : FALSE;
    }
    
    /**
     * Find whether podcast exists on disk
     * 
     * @param string $downloadPath
     * @return boolean
     */
    public function doesPodcastExistDisk($downloadPath)
    {
        $podcastExists = Storage::disk('spaces')->exists($downloadPath);
        
        return $podcastExists === TRUE;
    }
    
    public function getPodcast($slug)
    {
        $podcast = $this->where('slug', $slug)->first();
        
        $podcastsCollection = array(        
            'Id'           => $podcast['id'],
            'Slug'         => $podcast['slug'],
            'Name'         => $podcast['name'],
            'DownloadPath' => $podcast['download_path'],
            'CreatedAt'    => Carbon::parse($podcast['created_at'])->format('Y-m-d H:i:s'),
            'UpdatedAt'    => Carbon::parse($podcast['updated_at'])->format('Y-m-d H:i:s'),
        );
        
        /**
         * Return array with list of podcast names
         */
        return $podcastsCollection;
    }
    /**
     * Get errors of post request for this resource.
     * 
     * @param   \Illuminate\Http\Request  $request
     * @return  array
     */
    public static function getPostErrors($request)
    {
        $errors = array();
        
        $name = $request->input('name');
        
        if($name === NULL)
        {
            array_push($errors, 'Name not provided.');            
        }
        elseif(strlen($name) < 3)
        {
            array_push($errors, 'Name is less than 3 characters.');
        }
        elseif(strlen($name) > 191)
        {
            array_push($errors, 'Name exceeds 191 characters.');
        }
        
        return $errors;
    }
}
