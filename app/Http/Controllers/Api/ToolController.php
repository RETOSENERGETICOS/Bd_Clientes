<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Family;
use App\Models\Country;
use App\Models\Turn;
use App\Models\Services;
use App\Models\Distribution;
use App\Models\Training;
use App\Models\File;
use App\Models\Group;
use App\Models\Log;
use App\Models\Tool;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{

    public function history(Request $request) {
        return response()->json(Log::with('tool','user')->orderBy('created_at', 'desc')->limit(1000)->get());
    }

    public function index(Request $request) {
        return response()->json(
            Tool::all()->map(static function(Tool $tool) {
                return [
                    'id' => $tool->id,
                    'item' => $tool->item,
                    'country' => $tool->country,
                    'measurement' => $tool->measurement,
                    'turn' => $tool->turn,
                    'services' => $tool->services,
                    'distribution' => $tool->distribution,
                    'training' => $tool->training
                ];
            })
        );
    }

    public function show(Request $request, Tool $tool) {
        return response()->json([
            'id' => $tool->id,
            'item' => $tool->item,
            'country' => $tool->country,
            'measurement' => $tool->measurement,
            'turn' => $tool->turn,
            'services' => $tool->services,
            'distribution' => $tool->distribution,
            'training' => $tool->training,
            'tradename' => $tool->tradename,
            'bname' => $tool->bname,
            'fvn' => $tool->fvn,
            'address' => $tool->address,
            'contact' => $tool->contact,
            'phone' => $tool->phone,
            'mail' => $tool->mail,
            'payments' => $tool->payments,
            'phonee' => $tool->phonee,
            'maill' => $tool->maill,
            'terms' => $tool->terms,
            'credit' => $tool->credit,
            'user' => $tool->user,
            'files' => $tool->files->map(static function(File $file) {
                return $file->path;
            })
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tool = $this->createTool($request);
            foreach ($request->documents as $document) {
                $tool->files()->create([
                    'path' => $document
                ]);
            }
            $request->user()->logs()->create([
                'tool_id' => $tool->id,
                'comment' => 'Se inserto registro',
                'type'=> 'insert',
                'after' => json_encode($this->getValues($tool->toArray(), $tool))
            ]);
            DB::commit();
            return response()->json(
                $tool
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, Tool $tool) {
        DB::beginTransaction();
        try {
            $request->user()->logs()->create([
                'tool_id' => $tool->id,
                'comment' => 'Se elimino registro',
                'type'=> 'delete',
            ]);
            $tool->deleteOrFail();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
        DB::commit();
        return response()->json($tool);
    }

    public function update(Request $request, Tool $tool) {
        DB::beginTransaction();
        try {
            $country = $this->getCountry($request->country);
            $turn = $this->getTurn($request->turn);
            $services = $this->getServices($request->services);
            $distribution = $this->getDistribution($request->distribution);
            $training = $this->getTraining($request->training);
            $oldTool = json_encode($this->getValues($tool->toArray(), $tool));
            if ($request->main_localization !== $tool->main_localization) {
                $tool->update([ 'quantity' => $tool->quantity - $request->movingQuantity ]);
                $request->quantity = $request->movingQuantity;
                $tool = $this->createTool($request);
                $request->user()->logs()->create([
                    'tool_id' => $tool->id,
                    'comment' => 'Se traspaso registro',
                    'type'=> 'update',
                    'before' => $oldTool,
                    'after' => json_encode($this->getValues($tool->toArray(), $tool))
                ]);
            } else {
                $tool->update([
                    'country_id' => $country->id ?? null,
                    'turn_id' => $turn->id ?? null,
                    'services_id' => $services->id ?? null,
                    'distribution_id' => $distribution->id ?? null,
                    'training_id' => $training->id ?? null,
                    'tradename' => $request->tradename,
                    'bname' => $request->bname,
                    'fvn' => $request->fvn,
                    'address' => $request->address,
                    'contact' => $request->contact,
                    'phone' => $request->phone,
                    'mail' => $request->mail,
                    'payments' => $request->payments,
                    'phonee' => $request->phonee,
                    'maill' => $request->maill,
                    'terms' => $request->terms,
                    'credit' => $request->credit,
                ]);
                $oldValues = $tool->getChanges();
                if (count($oldValues) > 0) {
                    $request->user()->logs()->create([
                        'tool_id' => $tool->id,
                        'comment' => 'Se edito registro',
                        'type'=> 'update',
                        'before' => $oldTool,
                        'after' => json_encode($this->getValues($oldValues, $tool->refresh()))
                    ]);
                }
            }
            DB::commit();
            return response()->json(
                $tool
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    private function createTool(Request $request) {
        $training = $this->getTraining($request->training);
        $turn = $this->getTurn($request->turn);
        $services = $this->getServices($request->services);
        $distribution = $this->getDistribution($request->distribution);
        $distribution = $this->getDistribution($request->distribution);
        $tool = $request->user()->tools()->create([
            'country_id' => $country->id ?? null,
            'turn_id' => $turn->id ?? null,
            'services_id' => $services->id ?? null,
            'distribution_id' => $distribution->id ?? null,
            'training_id' => $training->id ?? null,
            'tradename' => $request->tradename,
            'bname' => $request->bname,
            'fvn' => $request->fvn,
            'address' => $request->address,
            'contact' => $request->contact,
            'phone' => $request->phone,
            'mail' => $request->mail,
            'payments' => $request->payments,
            'phonee' => $request->phonee,
            'maill' => $request->maill,
            'terms' => $request->terms,
            'credit' => $request->credit
        ]);
        $tool->update([
            'item' => sprintf('CLT%04d', $tool->id)
        ]);
        return $tool->refresh();
    }

    private function getValues($values, Tool $tool) {
//        dd($values, $tool);
        $specialAttributes = ['country_id' => 'country','turn_id' => 'turn','services_id' => 'services','distribution_id' => 'distribution','training_id' => 'training'];
        $names = ['item' => 'Item','country' => 'Pais/Country','turn_id' => 'Actividad/Activity','services_id' => 'Servicios/Service','distribution_id' => 'Distribucion/Distribution',
            'training' => 'Capacitacion/Training','tradename' => 'Nombre comercial/Company Name','fvn' => 'RFC/VAT/NIF',
            'address' => 'Direccion/Address', 'contact' => 'Contacto de compras/Purchasing Contact', 'phone' => 'Telefono/Phone number', 'mail' => 'Correo/Email',
            'payments' => 'Contacto de pagos/Payment Contact', 'phonee' => 'Telefono/Phone number', 'maill' => 'Correo/Email', 'terms' => 'Condiciones de venta', 'credit' => 'Credito'];
        $data = array();
        foreach (array_keys($values) as $key) {
            if (array_key_exists($key, $specialAttributes)) {
                $data[$names[$key]] = $tool[$specialAttributes[$key]]->name ?? '';
            } else if(array_key_exists($key, $names)){
                $data[$names[$key]] = $values[$key];
            }
        }
        return $data;
    }

    public function showTool(Tool $tool): array {
        return [
            'id' => $tool->id,
            'item' => $tool->item,
            'country' => $tool->country,
            'turn' => $tool->turn,
            'services' => $tool->services,
            'distribution' => $tool->distribution,
            'training' => $tool->training,
            'tradename' => $tool->tradename,
            'bname' => $tool->bname,
            'fvn' => $tool->fvn,
            'address' => $tool->address,
            'contact' => $tool->contact,
            'phone' => $tool->phone,
            'mail' => $tool->mail,
            'payments' => $tool->payments,
            'phonee' => $tool->phonee,
            'maill' => $tool->maill,
            'terms' => $tool->terms,
            'credit' => $tool->credit,
            'user' => $tool->user,
        ];
    }

    public function search(Request $request) {
        $especialKeys = ['country','turn','distribution','services','training','user'];
        $filters = $request->keys();
        $query = Tool::query();
        foreach($filters as $filter) {
            if (in_array($filter, $especialKeys, true)) {
                $value = is_null($request[$filter]) ? null : $request[$filter]['id'];
                if ($value !== 0) {
                    $query = $query->where("{$filter}_id", is_null($request[$filter]) ? null : $request[$filter]['id']);
                }
            }
            else if (!in_array($filter, $especialKeys, true)){
                $query = $query->where(Str::snake($filter), 'like', "%$request[$filter]%");
            }
        }
        $data = $query->get()->map(function(Tool $tool) {
            return $this->showTool($tool);
        });
        return response()->json($data);
    }

    private function getCountry($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Country::find($data['id']);
        }
        return Country::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getTurn($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Turn::find($data['id']);
        }
        return Turn::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getServices($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Services::find($data['id']);
        }
        return Services::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getDistribution($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Distribution::find($data['id']);
        }
        return Distribution::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }

    private function getTraining($data)
    {
        if (is_null($data)) {
            return null;
        }
        if (is_array($data)) {
            return Training::find($data['id']);
        }
        return Training::where('name', $data)->firstOrCreate([
            'name' => $data
        ]);
    }
}
