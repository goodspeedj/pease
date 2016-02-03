<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Participant extends Model
{

    protected $table = "Participant";

    // Which fields can be modified by users
    protected $fillable = [
    ];


    /**
     * Query to return participant data in cross tab format
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCrosstab($query)
    {
        //$wellID = 2;
        return $query->select('participantRecordID', \DB::raw("
                    max(if(chemID=1, pfcLevel, ' ')) as 'PFOA',
                    max(if(chemID=2, pfcLevel, ' ')) as 'PFOS',
                    max(if(chemID=3, pfcLevel, ' ')) as 'PFHxS',
                    max(if(chemID=5, pfcLevel, ' ')) as 'PFOSA',
                    max(if(chemID=6, pfcLevel, ' ')) as 'PFNA',
                    max(if(chemID=8, pfcLevel, ' ')) as 'PFPeA',
                    max(if(chemID=9, pfcLevel, ' ')) as 'PFHxA',
                    max(if(chemID=10, pfcLevel, ' ')) as 'PFBA'
                "))
            ->leftJoin('ParticipantPFCLevel', 'ParticipantPFCLevel.participantID', '=', 'Participant.participantID')
            ->groupBy('participantRecordID');
    }
}
