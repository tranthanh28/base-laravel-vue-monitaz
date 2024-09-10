<?php

namespace App\Console\Commands;

use App\Events\NotificationsToDataEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Monitaz\NotifyDataProcess\NotifyDataProcess;
use App\Models\Monitaz\NotifyDataProcessCompetitor\NotifyDataProcessCompetitor;
use Illuminate\Support\Facades\Http;

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
        sleep(5);
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
//        $timeStart = "";
//        $time1 = Carbon::now()->format("Y-m-d 08:45:00");
//        $time2 = Carbon::now()->format("Y-m-d 09:30:00");
//        $time3 = Carbon::now()->format("Y-m-d 14:30:00");
//        $time4 = Carbon::now()->format("Y-m-d 16:30:00");
//        if ($timeNow >= $time4) {
//            $timeStart = $time4;
//        } elseif ($timeNow >= $time3) {
//            $timeStart = $time3;
//        } elseif ($timeNow >= $time2) {
//            $timeStart = $time2;
//        } elseif ($timeNow >= $time1) {
//            $timeStart = $time1;
//        } else {
//            return 0;
//        }

        $timeStart = Carbon::now()->subMinutes(10);
        $allNotify = NotifyDataProcessCompetitor::where("created_at", ">=", $timeStart)->where("is_confirm", 0)->whereNull("noticed_at")->where("status", 1)->get();
//        $allNotify = NotifyDataProcessCompetitor::where("created_at", ">=", $timeStart)->where("uid", 341)->where("is_confirm", 0)->where("status", 1)->get();
//        $allNotify = NotifyDataProcessCompetitor::where("id", "=", 268)->get();
        if ($allNotify->isEmpty()) {
            $this->info("No notification");
            return 0;
        }
        $this->info("notification to data");
        \Log::debug("notification to data");
        $listIds = $allNotify->pluck("id");
        NotifyDataProcessCompetitor::whereIn('id', $listIds)
            ->update([
                'noticed_at' => $timeNow
            ]);
        foreach ($allNotify as $notification) {
            $message = "Thông báo về tin chưa sửa của đối thủ: \n";
            $noti_id = $notification->id;
            $uid = $notification->uid;
            $listObject = json_decode($notification->list_objects, true);
            foreach ($listObject as $channel=>$valueChannel) {
//                $this->info($channel);
                $message .= $channel . ":\n";
                foreach ($valueChannel as $value) {
                    $message .= "\t" . $value["name"] . "\n";
                }
            }
            $notiChannel = "team-data";
            $nameChannel = $notiChannel . "-" . $uid;
            $competitor = true;
//            pusher
//            event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel, $competitor));

//            socket io
            $data = [
                "message" => $message,
                "noti_id" => $noti_id,
                "uid" => $uid,
                "nameChannel" => $nameChannel,
                "competitor" => $competitor ?? false,
            ];
            Http::post('http://123.31.24.80:3232/notify-data', $data);

//            foreach ($notiChannels as $notiChannel) {
//                $nameChannel = $notiChannel . "-" . $uid;
//                $competitor = true;
//                event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel, $competitor));
//            }

        }
    }
}
