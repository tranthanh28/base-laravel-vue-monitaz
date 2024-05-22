<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\TNS\TNSFilter;
use App\Models\Monitaz\DailyData\DailyData;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanPage\ScanPage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TNSController extends Controller
{
    public function __construct(TNSFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $scanPage = ScanPage::filters($this->filter)
            ->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $scanPage
        ], 200);

    }

    public function indexDay()
    {
        $data = DailyData::filters($this->filter)
            ->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $data
        ], 200);
    }

    public function storeDay(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'file' => 'required'
        ]);
        $file = $request->file;
//        $path = '/excel/day/'. $request->name;
        $originalName = $request->file->getClientOriginalName();
        $extension = "." . $request->file->getClientOriginalExtension();
        $path = '/excel/day/'. $originalName;
        $fileName = str_replace($extension,"",$originalName);

        Storage::disk('public')->put($path, file_get_contents($file));
        $response = Http::get('http://tns.bigdata.io.vn/daily-scan?file_name='. $fileName);
        $data = $response->json();
        return response()->json([
            'status' => true,
            'message' => 'created successfully',
            'data' => $data
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $scanPage = ScanPage::findorFail($id);
        if ($scanPage->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed (status == 1)'
            ], 400);
        }

        $postIds = preg_split("/\r\n|\n|\r/", $request->content_file);
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $fileName = $request->name . "_" . $date .'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($postIds),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
            'status' => 0,
        ];

        $scanPage->update($data);

        $result = array_map(function ($item) {
            return [$item];
        }, $postIds);
        Excel::store(new ReactionExport($result), $fileName);
        return response()->json([
            'status' => true,
            'message' => 'update successfully',
            'data' => $scanPage
        ], 200);

    }

    public function exportExcel(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'required'
        ]);
        return Storage::download($request->file_name);
    }
}
