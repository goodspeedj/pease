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
     * Query to return well sample data in cross tab format
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCrosstab($query, $wellName)
    {
        return $query->select('sampleDate','wellName', \DB::raw("
                    max(if(chemID=1, pfcLevel, ' ')) as 'PFOA', max(if(chemID=1, noteAbr, ' ')) as 'PFOANote',
                    max(if(chemID=2, pfcLevel, ' ')) as 'PFOS', max(if(chemID=2, noteAbr, ' ')) as 'PFOSNote',
                    max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS', max(if(chemID=3, noteAbr, ' ')) as 'PFHxSNote',
                    max(if(chemID=4, pfcLevel, ' ')) as 'PFHxA', max(if(chemID=4, noteAbr, ' ')) as 'PFHxANote',
                    max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA', max(if(chemID=5, noteAbr, ' ')) as 'PFOSANote',
                    max(if(chemID=6, pfcLevel, ' ')) as 'PFBA', max(if(chemID=6, noteAbr, ' ')) as 'PFBANote',
                    max(if(chemID=7, pfcLevel, ' ')) as 'PFBS', max(if(chemID=7, noteAbr, ' ')) as 'PFBSNote',
                    max(if(chemID=8, pfcLevel, ' ')) as 'PFDA', max(if(chemID=8, noteAbr, ' ')) as 'PFDANote',
                    max(if(chemID=9, pfcLevel, ' ')) as 'PFDS', max(if(chemID=9, noteAbr, ' ')) as 'PFDSNote',
                    max(if(chemID=10, pfcLevel, ' ')) as 'PFDoA', max(if(chemID=10, noteAbr, ' ')) as 'PFDoANote',
                    max(if(chemID=11, pfcLevel, ' ')) as 'PFHpS', max(if(chemID=11, noteAbr, ' ')) as 'PFHpSNote',
                    max(if(chemID=12, pfcLevel, ' ')) as 'PFHpA', max(if(chemID=12, noteAbr, ' ')) as 'PFHpANote',
                    max(if(chemID=13, pfcLevel, ' ')) as 'PFNA', max(if(chemID=13, noteAbr, ' ')) as 'PFNANote',
                    max(if(chemID=14, pfcLevel, ' ')) as 'PFPeA', max(if(chemID=14, noteAbr, ' ')) as 'PFPeANote',
                    max(if(chemID=15, pfcLevel, ' ')) as 'PFTeDA', max(if(chemID=15, noteAbr, ' ')) as 'PFTeDANote',
                    max(if(chemID=16, pfcLevel, ' ')) as 'PFTrDA', max(if(chemID=16, noteAbr, ' ')) as 'PFTrDANote',
                    max(if(chemID=17, pfcLevel, ' ')) as 'PFUnA', max(if(chemID=17, noteAbr, ' ')) as 'PFUnANote',
                    max(if(chemID=18, pfcLevel, ' ')) as 'FTS62', max(if(chemID=18, noteAbr, ' ')) as 'FTS62Note',
                    max(if(chemID=19, pfcLevel, ' ')) as 'FTS82', max(if(chemID=19, noteAbr, ' ')) as 'FTS82Note',
                    max(if(chemID=20, pfcLevel, ' ')) as 'EtFOSA', max(if(chemID=20, noteAbr, ' ')) as 'EtFOSANote',
                    max(if(chemID=21, pfcLevel, ' ')) as 'EtFOSE', max(if(chemID=21, noteAbr, ' ')) as 'EtFOSENote',
                    max(if(chemID=22, pfcLevel, ' ')) as 'MeFOSA', max(if(chemID=22, noteAbr, ' ')) as 'MeFOSANote',
                    max(if(chemID=23, pfcLevel, ' ')) as 'MeFOSE', max(if(chemID=23, noteAbr, ' ')) as 'MeFOSENote'
                "))
            ->leftJoin('SampleNote', 'WellSample.noteID', '=', 'SampleNote.noteID')
            ->join('Well', 'WellSample.wellID', '=', 'Well.wellID')
            ->where('wellName', '=', $wellName)
            ->groupBy('sampleDate');
    }


    /**
     * Query to return regular well sample data by well 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellSampleByWell($query, $wellName)
    {
        return $query
                ->join('Chemical', 'WellSample.chemID', '=', 'Chemical.chemID')
                ->join('Well', 'WellSample.wellID', '=', 'Well.wellID')
                ->select('WellSample.sampleID', 'WellSample.sampleDate', 'Well.wellName', 'Chemical.shortName', 'Chemical.longName', 'WellSample.pfcLevel', 'Chemical.chemID')
                ->where('Well.wellName', '=', $wellName)
                ->orderBy('WellSample.sampleDate', 'desc')
                ->orderBy('Chemical.shortName', 'asc');
    }


    /**
     * Query to return regular well sample data by well 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWellSampleByPfc($query, $pfc)
    {
        return $query
                ->join('Chemical', 'WellSample.chemID', '=', 'Chemical.chemID')
                ->join('Well', 'WellSample.wellID', '=', 'Well.wellID')
                ->select('WellSample.sampleID', 'WellSample.sampleDate', 'Chemical.chemID', 'Chemical.shortName', 'WellSample.pfcLevel', 'Well.wellName', 'Well.wellDesc', 'Well.wellID')
                ->where('Chemical.shortName', '=', $pfc)
                ->orderBy('WellSample.sampleDate', 'desc')
                ->orderBy('Well.wellID', 'asc');
    }
}
