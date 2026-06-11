<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hsn extends Model
{
    protected $table = 'hsn';

    protected $fillable = [
        'hsn_category',
        'hsn_code',
        'hsn_description',
        'chapter',
        'url',
    ];
}
