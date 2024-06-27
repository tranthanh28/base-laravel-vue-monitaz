<?php

namespace App\Console\Commands;

use App\Events\NotificationsToDataEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Monitaz\NotifyDataProcess\NotifyDataProcess;
use App\Models\Monitaz\NotifyDataProcessCompetitor\NotifyDataProcessCompetitor;
class SendNotificationsToDataCompetitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-noti:competitor';

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
        $notiChannels = config('variable.noti_channels');

        \Log::info("test notification to data");
        $this->info("notification to data");
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
        $timeStart = "";
        $time1 = Carbon::now()->format("Y-m-d 08:45:00");
        $time2 = Carbon::now()->format("Y-m-d 09:30:00");
        $time3 = Carbon::now()->format("Y-m-d 14:30:00");
        $time4 = Carbon::now()->format("Y-m-d 16:30:00");
        if ($timeNow >= $time4) {
            $timeStart = $time4;
        } elseif ($timeNow >= $time3) {
            $timeStart = $time3;
        } elseif ($timeNow >= $time2) {
            $timeStart = $time2;
        } elseif ($timeNow >= $time1) {
            $timeStart = $time1;
        } else {
            return 0;
        }
//        $allNotify = NotifyDataProcessCompetitor::where("created_at", ">=", $timeStart)->where("status_confirm", 0)->where("status", 1)->get();
        $allNotify = NotifyDataProcessCompetitor::where("id", "=", 268)->get();
        if ($allNotify->isEmpty()) {
            $this->info("No notification");
            return 0;
        }
        $message = "";
        foreach ($allNotify as $notification) {
            $noti_id = $notification->id;
            $uid = $notification->uid;
            $listObject = json_decode($notification->list_objects, true);
            foreach ($listObject as $channel=>$valueChannel) {
//                $this->info($channel);
                $message .= $channel . ":\n";
                foreach ($valueChannel as $value) {
                    $message .="\t" . $value["name"] . "\n";
                }
            }

            foreach ($notiChannels as $notiChannel) {
                $nameChannel = $notiChannel . "-" . $uid;
                $competitor = true;
                event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel, $competitor));
            }

        }
    }
}
