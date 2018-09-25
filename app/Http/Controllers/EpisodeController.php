<?php

namespace App\Http\Controllers;

use App\Repositories\EpisodeInterface;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected $episode = null;
    
    public function __construct(EpisodeInterface $episode)
    {
        $this->episode = $episode;
    }
}
