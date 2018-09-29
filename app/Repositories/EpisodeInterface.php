<?php 

namespace App\Repositories;

interface EpisodeInterface
{
    public function allEpisodes($podcastId);
    
    public function getPodcastEpisodes($podcastId);
}