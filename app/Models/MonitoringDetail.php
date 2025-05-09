<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringDetail extends Model
{
    use HasFactory;

    // MonitoringDetail.php
public function monitoring()
{
    return $this->belongsTo(Monitoring::class);
}

}
