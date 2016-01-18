<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WellSample extends Model
{

    protected $table = "WellSample";

    // Which fields can be modified by users
    protected $fillable = [
        'sampleDate',
        'pfcLevel'
    ];


    /**
     * A Well Sample can have many chemicals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checmical()
    {
    	return $this->hasMany('App\Chemical');
    }
}
