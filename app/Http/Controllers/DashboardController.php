<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $month = $request->input('month', now()->format('m'));
    $year = $request->input('year', now()->format('Y'));

    $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endOfMonth = $startOfMonth->copy()->endOfMonth();

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

    $staff = ['Zulkifli Bin Abdullah', 'Raihana Binti Kamsiran', 'Robin Francis @ Robinson', 'Norizuwan Bin Che Hamid'];
    $loggedInUser = auth()->user()->name;

    // Ambil data roster yang disimpan
    $savedRosters = Roster::whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get()
                    ->groupBy(function($item) {
                        return $item->date . '_' . $item->shift_type;
                    });
                    

    // Return selepas SEMUA data disediakan
    return view('dashboard', compact(
        'days', 'staff', 'month', 'year', 'loggedInUser', 'savedRosters'
    ));
}


}
