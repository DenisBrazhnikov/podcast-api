<?php

namespace App\Repositories;

interface PodcastInterface
{
    public function allPodcasts();
    
    public function doesPodcastExistDb($slug);
    
    public function doesPodcastExistDisk($downloadPath);
    
    public function getPodcast($podcastSlug);
}