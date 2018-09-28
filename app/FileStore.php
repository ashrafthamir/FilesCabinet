<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileStore extends Model
{
    public function user()
    {
        return $this->belongsTo(User::Class);
    }
}
