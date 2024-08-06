<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\TNS\TNSFilter;
use App\Jobs\SendDataWeekJob;
use App\Models\Monitaz\DailyData\DailyData;
use App\Models\Monitaz\ReportProgress\ReportProgress;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanPage\ScanPage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TNSExportController extends Controller
{
    public function __construct()
    {}

    public function getBrandTypeList()
    {
        try {
            $response = Http::get('http://tns.bigdata.io.vn/get-report-title-list');
            $data = $response->json();
            return response()->json([
                'status' => true,
                'message' => 'created successfully',
                'data' => $data['data']
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $this->validate($request, [
                'brand_type' => 'required',
                'month_filters' => 'required|array',
            ]);

            $month_filters = $request->get("month_filters") ?? [];
            $range_date = array_values(array_unique($month_filters));


            $data = [
                'report_title' => $request->get("brand_type"),
                'range_date' => $range_date,
            ];

            return $data;

            $response = Http::post('http://tns.bigdata.io.vn/export-top-cost', $data);
            $data = $response->json();
            return response()->json([
                'status' => true,
                'message' => 'successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 500);
        }

    }
}
