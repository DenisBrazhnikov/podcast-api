<?php

namespace App\Http\Controllers;

use App\Repositories\PodcastInterface;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    protected $podcast = null;
    
    public function __construct(PodcastInterface $podcast)
    {
        $this->podcast = $podcast;
    }
}
