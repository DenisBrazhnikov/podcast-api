<?php

namespace App\Repositories;

interface PodcastInterface
{
    public function allPodcasts();
    
    public function create(array $data);
    
    public function update(array $data, $id);
    
    public function delete($id);
    
    public function show($id);
}