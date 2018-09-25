<?php

namespace App\Http\Controllers;

use App\Repositories\PodcastRepository;
use Illuminate\Http\Request;
use App\Podcast;

class PodcastController extends Controller
{
    protected $podcast = null;
    
    public function __construct(Podcast $podcast)
    {
        $this->podcast = new PodcastRepository($podcast);
    }
    
    public function index()
    {
        return $this->podcast->allPodcasts();
    }
}
