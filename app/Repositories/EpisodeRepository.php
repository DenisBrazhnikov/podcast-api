<?php 

namespace App\Repositories;

use App\Episode;

class EpisodeRepository implements EpisodeInterface
{
    // model property on class instances
    protected $episode;
    
    // Constructor to bind model to repo
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }
    
    // Get all instances of model
    public function allEpisodes($podcastId)
    {
        return $this->episode->allEpisodes($podcastId);
    }
    
    public function getPodcastEpisodes($podcastId)
    {
        return $this->episode->getPodcastEpisodes($podcastId);
    }
}