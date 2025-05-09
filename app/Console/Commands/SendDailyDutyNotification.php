<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Roster;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendDailyDutyNotification extends Command
{
    protected $signature = 'notify:daily-duty';
    protected $description = 'Hantar notifikasi WhatsApp kepada staff yang bertugas hari ini';

    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $timeSlot = '08:30AM'; // anda boleh loop lebih dari satu jika perlu

        $rosters = Roster::whereDate('date', $today)
            ->where('time', $timeSlot)
            ->with('user') // pastikan ada relationship
            ->get();

        $whatsapp = new WhatsAppService();

        foreach ($rosters as $roster) {
            $phone = $roster->user->phone_number; // format 60XXXXXXXXX
            $name = $roster->user->name;
            $message = "Hi $name, anda dijadualkan bertugas hari ini ($today) pada jam $timeSlot. Sila semak sistem.";

            $whatsapp->sendMessage($phone, $message);
        }

        $this->info('Notifikasi dihantar kepada semua staff yang bertugas.');
    }
}
