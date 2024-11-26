<?php

namespace App\Http\Controllers;

use App\Exports\Devices\FormatExport;
use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Services\DniService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public $dniService;

    public function __construct(DniService $dniService)
    {
        $this->dniService = $dniService;
    }
    
    public function index()
    {
        return view('pages.devices.index');
    }

    public function getData()
    {
        $devices = Device::orderBy('office')->orderBy('id', 'desc')->paginate();
        return response()->json($devices, 200);
    }

    public function search(Request $request)
    {
        $query = $request->search;

        $devices = Device::where('office', 'like', '%' .$query . '%')
                            ->orWhere('dni', 'like', '%' .$query . '%')
                            ->orWhere('fullname', 'like', '%' .$query . '%')
                            ->orWhere('ip', 'like', '%' .$query . '%')
                            ->orderBy('office')
                            ->orderBy('id', 'desc')
                            ->paginate();

        return response()->json($devices, 200);
    }

    public function getDataPerson($dni)
    {
        $consultation = $this->getAllDataReniec($dni);
        return response()->json(['person' => $consultation['data'], 'exists' => $consultation['exists']], 200);
    }

    public function store(DeviceRequest $request)
    {
        $device = Device::create($request->all());
        return response()->json(['message' => 'Se guardó con éxito!'], 200);
    }

    public function update(DeviceRequest $request, Device $device)
    {
        $device->update($request->all());
        return response()->json(['message' => 'Se modificó con éxito!'], 200);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['message' => 'Se eliminó con éxito!'], 200);
    }

    public function getAllDataReniec($dni)
    {
        $response = $this->dniService->getPersonByDni($dni);
        $statusCode = $response->getStatusCode();
        $exists = false;

        $data = $response->getData();

        if(isset($response->getData()->nombres) && $response->getData()->nombres != '' && $statusCode == 200) {
            $exists = true;
        }

        return [
            'data' => $data,
            'exists' => $exists
        ];
    }

    public function download()
    {
        $devices = Device::orderBy('office')->orderBy('id', 'desc')->get();

        if ($devices->isEmpty()) {
            return back()->with('error', 'No hay datos disponibles para exportar.');
        }    

        $data = [];
        foreach($devices as $d)
        {
            $data[] = [
                $d->office,
                $d->dni,
                $d->fullname,
                $d->charge,
                $d->ip,
                $d->mac,
                $d->port,
                $d->type,
                $d->is_ugel_value,
                $d->connection_type,
                $d->use_type,
            ];
        }    

        $resp = (new FormatExport($data))->store('devices.xlsx', 'temp');

        $filePath = storage_path('app/public/temp/devices.xlsx');
        return response()->download($filePath, 'Equipos.xlsx')->deleteFileAfterSend(true);
    }
}
