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


    /**
     * Query to return PFC names
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePfc($query, $pfc)
    {
        return $query
                ->select('Chemical.shortName', 'Chemical.longName')
                ->where('Chemical.shortName', '=', $pfc);
    }

}