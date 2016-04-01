<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Well extends Model
{

    /**
     * Query to return regular well data
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellData($query)
    {
        return $query
        		->select('Well.wellID', 'WellType.wellType', 'Well.wellName', 'Well.wellDesc', 'Well.wellLat', 'Well.wellLong', 'Well.wellYeild', 'Well.wellActive')
                ->join('WellType', 'Well.wellTypeID', '=', 'WellType.wellTypeID');
    }

}