<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UiPathService
{
    protected $baseUrl;
    protected $refreshToken;
    protected $clientId;
    protected $clientSecret;
    protected $tenantLogicalName;
    protected $accountLogicalName;
    protected $folderId;
  
    public function __construct()
    {
         // 2. Set nilai konfigurasi anda
        $this->baseUrl = 'https://cloud.uipath.com/sendijzqxqhw/TeamNori/orchestrator_';
        $this->clientId = env('UIPATH_CLIENT_ID'); // disarankan guna .env
        $this->clientSecret = env('UIPATH_CLIENT_SECRET');
        $this->tenantLogicalName = 'TeamNori'; // Sesuaikan
        $this->accountLogicalName = 'sendijzqxqhw'; // Sesuaikan
        $this->folderId = 4421642; // Dari Job JSON anda
    }

     // 3. Fungsi ambil access token (disimpan dalam cache untuk elak request berulang)
    public function getAccessToken()
    {
       // Guna token valid dari Postman
    return 'rt_C30E7A8793E1346DC63E5FADC342A18EEC9D390D85DA6AFB9B38AA36838B9CA6-1';
    }

    // 4. Fungsi untuk ambil jobs dari API UiPath
    public function getProcesses()
    {
       $token = $this->getAccessToken(); // Guna fungsi token automatik

    $url = $this->baseUrl . '/odata/Jobs';
    
    // Filter: Tarikh & ProcessType
    $query = [
        '$filter' => "(CreationTime ge 2024-04-04T12:00:47.264Z) and (ProcessType eq 'Process')",
        '$expand' => 'Robot,Machine,Release',
        
        '$orderby' => 'CreationTime desc'
    ];

    $response = Http::withToken($token)
        ->withHeaders([
            'X-UIPATH-OrganizationUnitId' => $this->folderId, // WAJIB
            'Accept' => 'application/json',
        ])
        ->get($url, $query);

    if ($response->successful()) {
        return $response->json()['value'] ?? [];
    }

    // Debug jika error
    logger('UiPath API Error', ['status' => $response->status(), 'body' => $response->body()]);
    return [];

    }

public function getJobs()
{
    $accessToken = $this->getAccessToken();

    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->get("https://cloud.uipath.com/tm/Robin_Prod/orchestrator_/odata/Jobs\$filter=((CreationTime ge 2024-04-04T12:00:47.264Z) and (ProcessType eq 'Process'))&\$expand=Robot,Machine,Release&\$orderby=CreationTime desc");

    if ($response->successful()) {
        return $response->json()['value'];
    }

    throw new \Exception("Gagal ambil senarai Jobs: " . $response->body());
}
}