<?php

namespace App\Http\Controllers;

use App\Models\Cliente as ClienteModel;
use App\Http\Resources\Cliente as ClientesResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = ClienteModel::paginate(15);
        return ClientesResource::collection($clientes);
    }

    public function store(Request $request)
    {
        $cliente = new ClienteModel;
        $cliente->nome        = $request->input('nome');
        $cliente->telefone    = $request->input('telefone');
        $cliente->cpf         = $request->input('cpf');
        $cliente->placa_carro = $request->input('placa_carro');

        if($cliente->save()) {
            return response()->json([
                "message" => "cliente record created"
            ], 200);
        }
        else {
            return response()->json([
                "message" => "erro ao inserir"
            ], 450);
        }
    }

    public function show($placa)
    {

        $users = DB::table('clientes')->where('placa_carro', 'like', '%'.$placa)->get();

        if($users != null or $users != '') {

            return $users;
        }
        else {

            return response()->json([
                "message" => "nenhuma placa correponde ao parâmetro informado"
            ], 301);
        }
    }

    public function update(Request $request)
    {
        $cliente = ClienteModel::findOrFail($request->id);
        $cliente->nome        = $request->input('nome');
        $cliente->telefone    = $request->input('telefone');
        $cliente->cpf         = $request->input('cpf');
        $cliente->placa_carro = $request->input('placa_carro');

        if($cliente->save()) {
            return ClientesResource::make($cliente);
        }
    }

    public function destroy($id)
    {
        $cliente = ClienteModel::findOrFail($id);

        if($cliente->delete()) {
            return ClientesResource::make($cliente);
        }
    }
}
