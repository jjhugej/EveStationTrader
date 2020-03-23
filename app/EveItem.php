<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EveItem extends Model
{
    protected $primaryKey = 'typeID';
    protected $table = 'eveitems';
}
