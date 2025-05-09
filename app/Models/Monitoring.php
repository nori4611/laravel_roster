<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'queue_trigger',
        'time_0830',
        'time_1030',
        'time_1200',
        'time_1400',
        'time_1530',
        'time_1630',
        'remark_que',
        'time_trigger',
        'status_0830',
        'status_1030',
        'status_1200',
        'status_1400',
        'status_1530',
        'status_1630',
        'remark_time',
    ];

    public function details()
    {
        return $this->hasMany(MonitoringDetail::class);
    }
    
}


