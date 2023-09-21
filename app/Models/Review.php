<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
   protected $fillable = ['post_id','client_id','comment','rate'];
   protected $hidden = [];

    public function post()
    {
        return $this->belongsTo(Post::class)
            //   ->select(['id','name'])
            ;
    }

    public function client()
    {
        return $this->belongsTo(Client::class)
            //   ->select(['id','name'])
            ;
    }
}
