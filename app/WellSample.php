<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WellSample extends Model
{

    // Which fields can be modified by users
    protected $fillable = [
        'sampleDate',
        'pfcLevel'
    ];
}
