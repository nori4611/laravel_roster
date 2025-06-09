<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardMonitoringController extends Controller
{
    // DashboardMonitoringController.php
public function index()
{
    $uipathService = app(\App\Services\UiPathService::class);

    $jobs = $uipathService->getJobs(); // Anda mungkin perlu tapis ikut tarikh/robot/queue jika perlu

    $counts = [
        'Successful' => 0,
        'Faulted' => 0,
        'Pending' => 0,

    ];

    foreach ($jobs as $job) {
        $status = $job['State']; // contoh: 'Successful', 'Faulted', 'Pending'

        if (isset($counts[$status])) {
            $counts[$status]++;
        }

    }

    

    return view('dashboard-monitoring', [
        'jobCounts' => $counts,
    ]);
}
}