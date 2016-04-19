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
    public function scopeWellData($query, $pfc)
    {
        return $query
        		->select('Well.wellID', 'WellType.wellType', 'Well.wellName', 'Well.wellDesc', 
        			     'Well.wellLat', 'Well.wellLong', 'Well.wellYeild', 'Well.wellActive', 
        			     DB::raw('avg(WellSample.pfcLevel) as pfcAvg'))
                ->join('WellType', 'Well.wellTypeID', '=', 'WellType.wellTypeID')
                ->join('WellSample', 'Well.wellID', '=', 'WellSample.WellID')
                ->join('Chemical', 'Chemical.chemID', '=', 'WellSample.chemID')
                ->where('Chemical.shortName', '=', $pfc)
                ->groupBy('Well.wellID')
                ->orderBy('pfcAvg', 'DESC');
    }


    /**
     * Query to return one Well data set
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWell($query, $wellName)
    {
        return $query
                ->select('Well.wellName', 'Well.wellDesc', 'Well.wellActive', 'Well.wellYeild', 'WellType.wellType', 'WellType.wellTypeID')
                ->join('WellType', 'Well.wellTypeID', '=', 'WellType.wellTypeID')
                ->where('Well.wellName', '=', $wellName);
    }


    /**
     * Query to return all Well data sets
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllWells($query)
    {
        return $query
                ->select('Well.wellName', 'Well.wellDesc', 'Well.wellActive', 'Well.wellYeild', 'WellType.wellType', 'WellType.wellTypeID')
                ->join('WellType', 'Well.wellTypeID', '=', 'WellType.wellTypeID');
    }

}