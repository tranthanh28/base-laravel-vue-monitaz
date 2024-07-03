<?php

namespace App\Console\Commands;

use App\Events\NotificationsToDataEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Monitaz\NotifyDataProcess\NotifyDataProcess;
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
        $notiChannels = config('variable.noti_channels');
        $this->info("notification to data");
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
        $timeStart = Carbon::now()->format("Y-m-d H:00:00");
        $halfTime = Carbon::now()->format("Y-m-d H:30:00");
        if ($timeNow >= $halfTime) {
            $timeStart = $halfTime;
        }
        $allNotify = NotifyDataProcess::where("created_at", ">=", $timeStart)->where("is_confirm", 0)->where("status", 1)->get();
//        $allNotify = NotifyDataProcess::where("created_at", ">=", $timeStart)->where("uid", 341)->where("is_confirm", 0)->where("status", 1)->get();
//        $allNotify = NotifyDataProcess::where("uid", "=", 314)->get();

        if ($allNotify->isEmpty()) {
            $this->info("No notification");
            return 0;
        }
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
            event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel));

//            foreach ($notiChannels as $notiChannel) {
//                $nameChannel = $notiChannel . "-" . $uid;
//                event(new NotificationsToDataEvent($message, $noti_id, $uid, $nameChannel));
//            }

        }
    }
}
