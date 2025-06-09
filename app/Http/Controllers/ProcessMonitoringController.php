<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcessMonitoringController extends Controller
{
    public function index()
    {
        return $this->showJobs(); // Panggil method showJobs
    }

    public function showJobs()
    {
        $client = new \GuzzleHttp\Client();

        $queryParams = [
            '$filter'  => "(CreationTime ge 2024-04-04T12:00:47.264Z) and (ProcessType eq 'Process')",
            '$expand'  => 'Robot,Machine,Release',
            '$orderby' => 'CreationTime desc',
        ];

        $response = $client->get('https://cloud.uipath.com/tm/Robin_Prod/orchestrator_/odata/Jobs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Accept'        => 'application/json',
            ],
            'query' => $queryParams,
            'verify' => false  // BYPASS SSL - jangan guna di production!

        ]);

        $jobs = json_decode($response->getBody(), true)['value'];

        // Hantar ke view
        return view('process', compact('jobs'));
    }

    private function getAccessToken()
    {
        // Gantikan dengan token sebenar atau kaedah ambilan token automatik
        return 'rt_C30E7A8793E1346DC63E5FADC342A18EEC9D390D85DA6AFB9B38AA36838B9CA6-1';
    }
}
