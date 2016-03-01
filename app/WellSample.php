<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
    public function chemical()
    {
    	return $this->hasMany('App\Chemical');
    }


    /**
     * Query to return regular well sample data by well 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellSampleByWell($query, $wellID)
    {
        return $query
                ->join('Chemical', 'WellSample.chemID', '=', 'Chemical.chemID')
                ->join('Well', 'WellSample.wellID', '=', 'Well.wellID')
                ->select('WellSample.sampleDate', 'Chemical.shortName', 'WellSample.pfcLevel')
                ->where('WellSample.wellID', '=', $wellID)
                ->orderBy('WellSample.sampleDate', 'desc')
                ->orderBy('Chemical.chemID', 'asc');
    }


    /**
     * Query to return regular well sample data by well 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellSampleByPfc($query, $chemID)
    {
        return $query
                ->join('Chemical', 'WellSample.chemID', '=', 'Chemical.chemID')
                ->join('Well', 'WellSample.wellID', '=', 'Well.wellID')
                ->select('WellSample.sampleDate', 'Chemical.chemID', 'WellSample.pfcLevel', 'Well.wellName')
                ->where('Chemical.chemID', '=', $chemID)
                ->orderBy('WellSample.sampleDate', 'desc')
                ->orderBy('Well.wellID', 'asc');
    }


    /**
     * Query to return well sample data in cross tab format
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCrosstab($query, $wellID)
    {
        return $query->select('sampleDate', \DB::raw("
                    max(if(chemID=1, pfcLevel, ' ')) as 'PFOA', max(if(chemID=1, noteAbr, ' ')) as 'PFOANote',
                    max(if(chemID=2, pfcLevel, ' ')) as 'PFOS', max(if(chemID=2, noteAbr, ' ')) as 'PFOSNote',
                    max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS', max(if(chemID=3, noteAbr, ' ')) as 'PFHxSNote',
                    max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA', max(if(chemID=5, noteAbr, ' ')) as 'PFOSANote',
                    max(if(chemID=6, pfcLevel, ' ')) as 'PFNA', max(if(chemID=6, noteAbr, ' ')) as 'PFNANote',
                    max(if(chemID=8, pfcLevel, ' ')) as 'PFPeA', max(if(chemID=8, noteAbr, ' ')) as 'PFPeANote',
                    max(if(chemID=9, pfcLevel, ' ')) as 'PFHxA', max(if(chemID=9, noteAbr, ' ')) as 'PFHxANote',
                    max(if(chemID=10, pfcLevel, ' ')) as 'PFBA', max(if(chemID=10, noteAbr, ' ')) as 'PFBANote'
                "))
            ->leftJoin('SampleNote', 'WellSample.noteID', '=', 'SampleNote.noteID')
            ->where('wellID', '=', $wellID)
            ->groupBy('sampleDate');
    }
}
