<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemical extends Model
{

    protected $table = "Chemical";


    /**
     * A Chemical is part of a Well Sample
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sample()
    {
    	return $this->belongsTo('App\WellSample');
    }

}