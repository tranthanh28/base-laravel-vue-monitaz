<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Reaction\ReactionFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanGroup\ScanGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ScanGroup as ScanGroupJob;

class ScanGroupController extends Controller
{
    public function __construct(ReactionFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $scanGroup = ScanGroup::filters($this->filter)
            ->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $scanGroup
        ], 200);

    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $keywords = preg_split("/\r\n|\n|\r/", $request->content_file);
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $fileName = $request->name . "_" . $date .'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($keywords),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
        ];

        $scanGroup = ScanGroup::create($data);

        foreach ($keywords as $keyword) {
            ScanGroupJob::dispatch([$keyword], $fileName, $scanGroup, $request->name);
        }

        return response()->json([
            'status' => true,
            'message' => 'created successfully',
            'data' => $scanGroup
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);

        $scanGroup = ScanGroup::findorFail($id);
        if ($scanGroup->status != 2) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed (status == 1|0)'
            ], 400);
        }

        $keywords = preg_split("/\r\n|\n|\r/", $request->content_file);
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $fileName = $request->name . "_" . $date .'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($keywords),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
            'status' => 0,
        ];

        $scanGroup->update($data);

        ScanGroupJob::dispatch($keywords, $fileName, $scanGroup);

        return response()->json([
            'status' => true,
            'message' => 'update successfully',
            'data' => $scanGroup
        ], 200);

    }

    public function exportExcel(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'required'
        ]);
        return Storage::download('/groups_xlsx/' . $request->file_name);
    }
}
