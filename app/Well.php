<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Well extends Model
{

	protected $table = "Well";

    /**
     * Query to return regular well data
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellData($query)
    {
        return $query
        		->select('Well.wellID', 'WellType.wellType', 'Well.wellName', 'Well.wellDesc', 'Well.wellLat', 'Well.wellLong', 'Well.wellYeild', 'Well.wellActive', DB::raw('avg(WellSample.pfcLevel) as pfcAvg'))
                ->join('WellType', 'Well.wellTypeID', '=', 'WellType.wellTypeID')
                ->join('WellSample', 'Well.wellID', '=', 'WellSample.WellID')
                ->where('WellSample.chemID', '=', 2)
                ->groupBy('Well.wellID')
                ->orderBy('pfcAvg', 'DESC');
    }

}