<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TypeOfService;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::latest()->paginate(10);
        return view('master.services.index', compact('services'));
    }

    public function create()
    {
        return view('master.services.create');
    }

    public function store(StoreServiceRequest $request)
    {
        TypeOfService::create($request->validated());
        return redirect()->route('master.services.index')->with('success', 'Service created successfully.');
    }

    public function edit($id)
    {
        $service = TypeOfService::findOrFail($id);
        return view('master.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->update($request->validated());
        return redirect()->route('master.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();
        return redirect()->route('master.services.index')->with('success', 'Service deleted successfully.');
    }
}
