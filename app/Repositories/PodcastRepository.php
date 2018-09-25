<?php

namespace App\Repositories;

use App\Podcast;

class PodcastRepository implements EpisodeInterface
{
    // model property on class instances
    protected $podcast;
    
    // Constructor to bind model to repo
    public function __construct(Podcast $podcast)
    {
        $this->podcast = $podcast;
    }
    
    // Get all instances of model
    public function all()
    {
        return $this->podcast->all();
    }
    
    // create a new record in the database
    public function create(array $data)
    {
        return $this->podcast->create($data);
    }
    
    // update record in the database
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }
    
    // remove record from the database
    public function delete($id)
    {
        return $this->podcast->destroy($id);
    }
    
    // show the record with the given id
    public function show($id)
    {
        return $this->podcast-findOrFail($id);
    }
    
    // Get the associated model
    public function getModel()
    {
        return $this->podcast;
    }
    
    // Set the associated model
    public function setModel($model)
    {
        $this->podcast = $model;
        return $this;
    }
    
    // Eager load database relationships
    public function with($relations)
    {
        return $this->podcast->with($relations);
    }
}