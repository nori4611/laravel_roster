<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use App\Models\MonitoringDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
    public function create()
    {
        return view('monitorings.create');
    }

    public function store(Request $request)
    {
        Log::info('Store Request Received', $request->all());
        Log::info('Full Request', $request->all());

        DB::beginTransaction();

        try {
            // Cari jika sudah wujud untuk tarikh yang sama
            $monitoring = Monitoring::where('date', $request->date)->first();

            if ($monitoring) {
                // 🧽 Padam data detail lama
                MonitoringDetail::where('monitoring_id', $monitoring->id)->delete();

               // 📝 Kemas kini proses Que Trigger jika berubah
               // $monitoring->activity_que = $request->activity_que;
               // $monitoring->save();

         } else {
                // Jika tiada, cipta baru
                $monitoring = Monitoring::create([
                    'date' => $request->input('date'),
                    'activity' => $request->input('activity_que'),// activity_que datang dari frontend
                ]);
            }
   // Simpan data detail baru

            Log::info('Monitor ID: ' . $monitoring->id);
            Log::info('Incoming activities:', $request->activities);

            foreach ($request->activities as $time => $data) {
                if (empty($data['vm']) && empty($data['queue']) && empty($data['remark'])) {
                    continue;
                }

                Log::info('Saving activity', ['time' => $time, 'data' => $data]);

                MonitoringDetail::create([
                    'monitoring_id' => $monitoring->id,
                    'time' => $time,
                    'vm' => $data['vm']?? null,
                    'queue' => $data['queue']?? null,
                    'remark' => $data['remark']?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Data berjaya disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function createWithDate($date)
    {
        return view('monitorings.create', compact('date'));
    }

}
