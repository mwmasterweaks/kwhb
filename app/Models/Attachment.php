<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['source', 'source_id', 'file_name', 'extention_name'];
    use HasFactory;
}
