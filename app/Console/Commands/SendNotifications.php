<?php

namespace App\Console\Commands;

use App\Events\NotificationsEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Monitaz\NotifyDataAlert\NotifyDataAlert;
class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("test notification");
        $this->info("notification");
        $timeStart = Carbon::now()->format("Y-m-d H:00:00");
        $allNotify = NotifyDataAlert::where("created_at", ">", $timeStart)->where("is_confirm", 0)->get();
        if ($allNotify->isEmpty()) {
            $this->info("No notification");
            return 0;
        }
        foreach ($allNotify as $notification) {
            $message = "Số lượng tin đã check trong 1 tiếng qua: ". $notification->quantity_is_check . ", số lượng tin chưa check còn lại: ". $notification->quantity_non_check;
            $noti_id = $notification->id;
            $name = $notification->username;
            event(new NotificationsEvent($message, $noti_id, $name));
        }
    }
}
