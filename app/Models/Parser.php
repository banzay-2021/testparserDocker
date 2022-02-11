<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parser extends Model
{

    use HasFactory;

    protected $table = 'parser';

    protected $fillable = [
        'title',
        'id_item',
        'link',
        'site',
        'points',
        'created'
    ];
    protected $guarded  = [
        'id',
        'created_at',
        'updated_at'
    ];

}
