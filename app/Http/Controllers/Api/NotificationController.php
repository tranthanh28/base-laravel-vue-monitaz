<?php

namespace App\Http\Controllers\Api;

use App\Models\Monitaz\NotifyDataProcess\NotifyDataProcess;
use App\Models\Monitaz\NotifyDataProcessCompetitor\NotifyDataProcessCompetitor;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function __construct()
    {
    }

    public function alertNotification(Request $request)
    {
        $uid = $request->get('uid');
        $channelName = $request->get('channel') ?? "";
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
        $timeStart = Carbon::now()->format("Y-m-d H:00:00");
        $halfTime = Carbon::now()->format("Y-m-d H:30:00");
        if ($timeNow >= $halfTime) {
            $timeStart = $halfTime;
        }

        $notification = NotifyDataProcess::where("created_at", ">=", $timeStart)->where("role", 1)->where("is_confirm", 0)->where("uid", $uid)->where("status", 1)->first();
        if (empty($notification)) {
            return response()->json(
                [
                    "status" => false,
                    "data" => []
                ]
            );
        }
        $noti_id = $notification->id;
        NotifyDataProcess::where('id', $noti_id)
            ->update([
                'noticed_at' => $timeNow
            ]);
        $listObject = json_decode($notification->list_objects, true);
        $message = "Thông báo về tin chưa sửa của đối tượng chính: \n";
        foreach ($listObject as $channel => $valueChannel) {
//            if ($channelName == $channel) {
            $message .= $channel . ":\n";
            foreach ($valueChannel as $value) {
                $message .= "\t" . $value["name"] . ": " . $value["count"] . "\n";
            }
        }
        return response()->json(
            [
                "status" => true,
                "data" => [
                    "message" => $message,
                    "noti_id" => $noti_id
                ]
            ]
        );
    }

    public function alertNotificationAboutCompetitor(Request $request)
    {
//        time: 8h45, 9h30, 14h30, 16h30
        $uid = $request->get('uid');
        $channelName = $request->get('channel') ?? "";
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
            return response()->json(
                [
                    "status" => false,
                    "data" => [$timeNow]
                ]
            );
        }

        $notification = NotifyDataProcessCompetitor::where("created_at", ">=", $timeStart)->where("role", 1)->where("is_confirm", 0)->where("uid", $uid)->where("status", 1)->first();
        if (empty($notification)) {
            return response()->json(
                [
                    "status" => false,
                    "data" => []
                ]
            );
        }
        $noti_id = $notification->id;
        NotifyDataProcessCompetitor::where('id', $noti_id)
            ->update([
                'noticed_at' => $timeNow
            ]);
        $listObject = json_decode($notification->list_objects, true);
        $message = "Thông báo về tin chưa sửa của đối thủ: \n";
        foreach ($listObject as $channel => $valueChannel) {
//            if ($channelName == $channel) {
            $message .= $channel . ":\n";
            foreach ($valueChannel as $value) {
                $message .= "\t" . $value["name"] . "\n";
            }
        }
        return response()->json(
            [
                "status" => true,
                "data" => [
                    "message" => $message,
                    "noti_id" => $noti_id
                ]
            ]
        );
    }


    public function confirm(Request $request)
    {
        $notiId = $request->get('noti_id');
        $notice_by = $request->get('notice_by') ?? 0;
//        $confirmAbout = $request->get('confirm_about') ?? "object";
        $timeStart = Carbon::now()->format("Y-m-d H:00:00");
        $timeNow = Carbon::now()->format("Y-m-d H:i:s");
        $halfTime = Carbon::now()->format("Y-m-d H:30:00");
        if ($timeNow >= $halfTime) {
            $timeStart = $halfTime;
        }
        $notifications = NotifyDataProcess::where("id", $notiId)->where("created_at", ">=", $timeStart)->where("is_confirm", 0)->where("status", 1)->first();

        if (empty($notifications)) {
            return response()->json(
                [
                    "status" => false,
                    "data" => []
                ]
            );
        }

        $notifications->update(["is_confirm" => 1, "notice_by" => $notice_by, "confirm_at" => $timeNow]);
        return response()->json(
            [
                "status" => true,
                "data" => []
            ]
        );
    }

    public function confirmAboutCompetitor(Request $request)
    {
        $notiId = $request->get('noti_id');
        $notice_by = $request->get('notice_by') ?? 0;
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
            return response()->json(
                [
                    "status" => false,
                    "data" => [$timeNow]
                ]
            );
        }
        $notifications = NotifyDataProcessCompetitor::where("id", $notiId)->where("created_at", ">=", $timeStart)->where("is_confirm", 0)->where("status", 1)->first();

        if (empty($notifications)) {
            return response()->json(
                [
                    "status" => false,
                    "data" => []
                ]
            );
        }

        $notifications->update(["is_confirm" => 1, "notice_by" => $notice_by, "confirm_at" => $timeNow]);
        return response()->json(
            [
                "status" => true,
                "data" => []
            ]
        );

    }

}
