<?php

namespace App\Repositories;

use App\Podcast;

class PodcastRepository implements PodcastInterface
{
    // model property on class instances
    protected $podcast;
    
    // Constructor to bind model to repo
    public function __construct(Podcast $podcast)
    {
        $this->podcast = $podcast;
    }
    
    // Get all instances of model
    public function allPodcasts()
    {
        return $this->podcast->allPodcasts();
    }
    
    // Find whether podcast exists
    public function doesPodcastExist($podcast)
    {
        return $this->podcast->doesPodcastExist($podcast);
    }
}