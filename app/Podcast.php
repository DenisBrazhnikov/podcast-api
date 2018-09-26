<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Episode;

class Podcast extends Model
{
    protected $guarded = [];
    
    // Get all podcasts
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
         * Return json array with list of podcast names
         */
        return json_encode($podcastsCollection);
    }
    
    // Find whether podcast exists on database
    public function doesPodcastExistDb($slug)
    {
        $podcast = $this->where('slug', $slug)->first();
        
        return $podcast !== NULL ? TRUE : FALSE;
    }
    
    // Find whether podcast exists on disk
    public function doesPodcastExistDisk($downloadPath)
    {
        /**
         * Gets directories within given podcast folder.
         * @var array $podcasts
         */
        $podcasts = Storage::disk('spaces')->directories($downloadPath);
        
        return !empty($podcasts) ? TRUE : FALSE;
    }
}
