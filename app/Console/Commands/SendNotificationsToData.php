<?php

namespace App\Console\Commands;

use App\Events\NotificationsToDataEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Monitaz\NotifyDataProcess\NotifyDataProcess;
use Illuminate\Support\Facades\Http;

class SendNotificationsToData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-noti:data';

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
//        sleep(5);
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
//        $timeStart = Carbon::now()->format("Y-m-d H:00:00");
//        $halfTime = Carbon::now()->format("Y-m-d H:30:00");
//        if ($timeNow >= $halfTime) {
//            $timeStart = $halfTime;
//        }
        $timeStart = Carbon::now()->subMinutes(10);
        $allNotify = NotifyDataProcess::where("created_at", ">=", $timeStart)->where("is_confirm", 0)->whereNull("noticed_at")->where("status", 1)->get();
//        $allNotify = NotifyDataProcess::where("created_at", ">=", $timeStart)->where("uid", 341)->where("is_confirm", 0)->where("status", 1)->get();
//        $allNotify = NotifyDataProcess::where("uid", "=", 314)->get();

        if ($allNotify->isEmpty()) {
            $this->info("No notification");
            return 0;
        }
        $this->info("notification to data");
        \Log::debug("notification to data");
        $listIds = $allNotify->pluck("id");
        NotifyDataProcess::whereIn('id', $listIds)
            ->update([
                'noticed_at' => $timeNow
            ]);
        foreach ($allNotify as $notification) {
            $noti_id = $notification->id;
            $uid = $notification->uid;
            $listObject = json_decode($notification->list_objects, true);
            $message = "Thông báo về tin chưa sửa của đối tượng chính: \n";
            foreach ($listObject as $channel=>$valueChannel) {
                $this->info($channel);
                $message .= $channel . ":\n";
                foreach ($valueChannel as $value) {
                    $message .="\t" . $value["name"] . ": " . $value["count"] . "\n";
                }
            }

//            \Log::info([$uid, $message]);

            $notiChannel = "team-data";
            $nameChannel = $notiChannel . "-" . $uid;
//            pusher
//            event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel));

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
//                event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel));
//            }

        }
    }
}
