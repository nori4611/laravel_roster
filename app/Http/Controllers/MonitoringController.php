<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function create()
{
    return view('monitorings.create');
}


public function store(Request $request)
{
    // Simpan data utama (date & activity)
    $monitoring = Monitoring::create([
        'date' => $request->date,
        'activity' => $request->activity,
    ]);

    // Simpan aktiviti VM dan Queue dan Remark ikut masa
    foreach ($request->activities as $time => $activity) {
        $monitoring->details()->create([
            'time' => $time,
            'vm' => $activity['vm'],
            'queue' => $activity['queue'],
            'remark' => $activity['remark'] ?? null,
        ]);
    }

    return redirect()->route('dashboard')->with('success', 'Data berjaya disimpan!');
}


// Controller
public function createWithDate($date)
{
    return view('monitorings.create', compact('date'));
}


}
