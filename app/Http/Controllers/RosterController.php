<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    public function store(Request $request)
{
    foreach ($request->rosters as $roster) {
        if (!empty($roster['morning'])) {
            Roster::updateOrCreate(
                [
                    'date' => $roster['date'],
                    'shift_type' => 'morning'
                ],
                [
                    'day' => $roster['day'],
                    'week' => $roster['week'],
                    'staff_name' => $roster['morning']
                ]
            );
        }

        if (!empty($roster['afternoon'])) {
            Roster::updateOrCreate(
                [
                    'date' => $roster['date'],
                    'shift_type' => 'afternoon'
                ],
                [
                    'day' => $roster['day'],
                    'week' => $roster['week'],
                    'staff_name' => $roster['afternoon']
                ]
            );
        }
    }

    return redirect()->back()->with('success', 'Roster saved successfully.');
}

public function index(Request $request)
{
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    $days = [];
    $currentDate = $startOfMonth->copy();
    while ($currentDate <= $endOfMonth) {
        $days[] = [
            'date' => $currentDate->format('Y-m-d'),
            'day' => $currentDate->format('l'),
            'week' => $currentDate->weekOfMonth,
        ];
        $currentDate->addDay();
    }

    $staff = ['Raihana', 'Robin Francis @ Robinson', 'Norizuwan'];

    // Ambil roster yang sudah disimpan dari DB
    $savedRosters = Roster::whereBetween('date', [$startOfMonth, $endOfMonth])->get()->groupBy(function ($item) {
        return $item->date . '_' . $item->shift_type;
    });

    return view('dashboard', compact('days', 'staff', 'month', 'year', 'savedRosters'));
}


public function save(Request $request)
{
    
    $data = $request->input('roster', []);

    foreach ($data as $date => $shifts) {
        foreach ($shifts as $shiftType => $staffName) {
            if ($staffName) {
                Roster::updateOrCreate(
                    [
                        'date' => $date,
                        'shift_type' => $shiftType,
                    ],
                    [
                        'day' => Carbon::parse($date)->format('l'),
                        'week' => Carbon::parse($date)->weekOfMonth,
                        'staff_name' => $staffName,
                    ]
                );
            } else {
                // Remove if empty
                Roster::where('date', $date)
                      ->where('shift_type', $shiftType)
                      ->delete();
            }
        }
    }

    return redirect()->back()
        ->with('success', 'Roster saved successfully!')
        ->withInput($request->all());
}


public function show($date)
{
    $carbonDate = Carbon::parse($date);
    
    // Contoh: Ambil semua rekod roster untuk tarikh tu
    $rosters = Roster::whereDate('date', $carbonDate)->get();

    return view('roster.details', compact('rosters', 'carbonDate'));
}

public function notifyStaff()
    {
        $whatsapp = new WhatsAppService();
        $whatsapp->sendMessage('60137174556', 'Anda bertugas hari ini jam 8:30AM. Sila semak sistem.');
        
        return response()->json(['status' => 'Berjaya hantar WhatsApp']);
    }


}
