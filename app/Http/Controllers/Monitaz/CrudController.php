<?php

namespace App\Http\Controllers\Monitaz;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\CrudRequest as Request;
use App\Models\App\Crud\Crud;
use App\Notifications\App\Crud\CrudNotification;
use App\Services\Monitaz\Reaction\Crud\CrudService;
use Maatwebsite\Excel\Facades\Excel;

class CrudController extends Controller
{
    public function __construct(CrudService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('demo-crud.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $crud = $this->service->save();

        notify()
            ->on('row_created')
            ->with($crud)
            ->send(CrudNotification::class);

        return created_responses('data');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->service->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crud $crud)
    {
        $crud = $this->service->update($crud);

        notify()
            ->on('row_updated')
            ->with($crud)
            ->send(CrudNotification::class);

        return updated_responses('data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Crud $crud)
    {
        if ($this->service->delete($crud)) {

            notify()
                ->on('row_deleted')
                ->with((object)$crud->toArray())
                ->send(CrudNotification::class);

            return deleted_responses('data');
        }
        return failed_responses();
    }



}
