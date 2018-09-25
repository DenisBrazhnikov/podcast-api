<?php

namespace App\Repositories;

interface PodcastInterface
{
    public function allPodcasts();
    
    public function doesPodcastExist($podcast);
}