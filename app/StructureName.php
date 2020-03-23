<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
    This model, and its associated table, was created in order to reduce the number of requests sent to the ESI api.
    The goal is to recycle structure_ids that the program has already seen.
    This has the portential to cause issues in the future, and a force override should be made available to prevent issues
    such as using a structure name/id from our DB that has been destroyed in the real eve world. An expiration of 7-30 days should be set
    to automatically update with ESI (note this doesn't need to be a cron job, just do it as needed by the end user).
    If the user knows the station is no longer accessible a (well-hidden) button will allow them to override this feature, and query the ESI api
*/

class StructureName extends Model
{
    protected $table = 'structure_names';

    public function marketOrder(){
        $this->belongsTo('App\MarketOrders');
    }
}
