<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Override Table Name
    protected $table = 'posts';

    // Primary Key
    public $primaryKey = "id";

    // TimeStamps
    public $timestamps = true;

    
}
