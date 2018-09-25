<?php 

namespace App\Repositories;

use App\Episode;

class EpisodeRepository implements EpisodeInterface
{
    // model property on class instances
    protected $episode;
    
    // Constructor to bind model to repo
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }
    
    // Get all instances of model
    public function all()
    {
        return $this->episode->all();
    }
    
    // create a new record in the database
    public function create(array $data)
    {
        return $this->episode->create($data);
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
        return $this->episode->destroy($id);
    }
    
    // show the record with the given id
    public function show($id)
    {
        return $this->episode-findOrFail($id);
    }
    
    // Get the associated model
    public function getModel()
    {
        return $this->episode;
    }
    
    // Set the associated model
    public function setModel($model)
    {
        $this->episode = $model;
        return $this;
    }
    
    // Eager load database relationships
    public function with($relations)
    {
        return $this->episode->with($relations);
    }
}