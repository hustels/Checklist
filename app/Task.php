<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   protected $table = 'tasks';
   public $timestamps = false;
    
protected $fillable = [
        'ownerId',  'day', 'title', 'description', 'start_time' , 'end_time' , 'completed' , 'inProgress' 
    ];

    // A task belongs to a user
    public function user()
    {
    	return $this->belongsTo('App\User');
    }


}
