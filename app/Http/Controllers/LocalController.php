<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocalModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LocalController extends Controller
{
	//POST
    public function cadastrarLocal(Request $request){
    	$dadosDb = LocalModel::orderBy('nomeLocal');

    	$dadosDb->insert(['nomeLocal' => $request->nomeLocal, 'telefone' => $request->telefone]);

    	return redirect()->back()->with('sucesso', 'Setor Cadastrado com Sucesso.');
    }

    public function pesquisarLocalNome(Request $request){

        if ($request->nomeLocal == null){
            $request->nomeLocal = 'todos';
        }

    	return redirect()->route('mostrarLocais', ['nome' => $request->nomeLocal]);
    }

    public function mostrarLocais($nome){
        $dadosDb = LocalModel::orderBy('nomeLocal');

        if ($nome == 'todos'){
            $dadosDb = $dadosDb->paginate(10);

            return view ('local/pesquisarLocalNome', compact('dadosDb'));
        }

        $arrayPalavras = explode(' ', $nome);

        foreach ($arrayPalavras as $palavra) {
            $dadosDb->where('nomeLocal', 'like', '%' . $palavra . '%');
        }

        $dadosDb = $dadosDb->paginate(10);

        if ($dadosDb->isEmpty()){
            return redirect('pesquisarLocalNome')->with('message', 'Nenhum setor encontrado.');
        }

        return view ('local/pesquisarLocal', compact('dadosDb'));
    }

    //GET
    public function carregarInformacoesLocal($localID){
    	$dadosDb = LocalModel::orderBy('nomeLocal');
    	$dadosDb->where('localID', '=', $localID);
    	$dadosDb = $dadosDb->get();

    	return View('local/editarLocal',compact('dadosDb'));

    }

    public function carregarTodosLocais(){
        $dadosDb = LocalModel::orderBy('nomeLocal');
        $dadosDb = $dadosDb->paginate(10);

        return view('local/pesquisarLocal', compact('dadosDb'));
    }

    //POST
    public function editarLocal(Request $request){
    	$dadosDb = LocalModel::orderBy('nomeLocal');
    	$dadosDb->where('localID', '=', $request->localID);
    	$dadosDb->update(['nomeLocal' => $request->nomeLocal, 'telefone' => $request->telefone]);


    	//return redirect()->route('local/pesquisarLocal')->with('sucesso', 'Setor Alterado com Sucesso.');
    	return redirect('pesquisarLocal')->with('sucesso', 'Setor Alterado com Sucesso');
    }

    //GET
    public function apagarLocal($localID){
    	$dadosDb = LocalModel::orderBy('nomeLocal');
    	$dadosDb->where('localID', '=', $localID);

        try{
            $dadosDb->delete();    
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect('pesquisarLocal')->with('message', 'Não é possível apagar esse setor nesse momento, pois ele está ligado a alguma(s) visita(s).');
        }
    	

    	//return redirect()->route('local/pesquisarLocal')->with('sucesso', 'Setor Apagado com Sucesso.');
    	return redirect('pesquisarLocal')->with('sucesso', 'Setor Apagado com Sucesso');
    }
}
