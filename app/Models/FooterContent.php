<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FooterContent extends Model
{
  use SoftDeletes;
   protected $fillable = [
        'text',
        'value',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    
    
}
