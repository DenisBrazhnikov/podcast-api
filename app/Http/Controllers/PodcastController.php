<?php

namespace App\Http\Controllers;

use App\Repositories\PodcastRepository;
use App\Podcast;

class PodcastController extends Controller
{
    protected $podcast = null;
    /**
     * Object constructor.
     * @param Podcast $podcast
     */
    public function __construct(Podcast $podcast)
    {
        $this->podcast = new PodcastRepository($podcast);
    }
    /**
     * List all podcasts.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->podcast->allPodcasts();
    }
}
