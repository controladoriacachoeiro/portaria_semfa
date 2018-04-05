<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    //POST
    public function cadastrarUsuario(Request $request){
        $dadosDb = UsuarioModel::orderBy('name');

        if($request->tipoDoc == "CPF"){
            $request->numeroDoc = $this->ajeitarCPF($request->numeroDoc);
            $cpfValido = $this->validarCPF($request->numeroDoc);
            if ($cpfValido == false){
                return redirect()->back()->with('message', "CPF Inválido!");
            }
        }

        $token = Str::random(60);

        $dadosDb2 = UsuarioModel::orderBy('name');
        $dadosDb2->where('numeroDoc', '=', $request->numeroDoc);
        $dadosDb2 = $dadosDb2->get();

        if($dadosDb2->isEmpty()){
            $dadosDb->insert(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'status' => 'Ativo', 'remember_token' => $token, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'idGrupo' => $request->idGrupo]);
        } else {
            return redirect()->back()->with('message', 'Número de documento já existente!');
        }

        return redirect()->route('home')->with('sucesso', 'Usuário Cadastrado com sucesso.');
    }


	//POST
    public function procurarUsuario(Request $request){
		if ($request->nomeOuDocumentoUsuario == null){
        	$request->nomeOuDocumentoUsuario = 'todos';
        }

        return redirect()->route('mostrarUsuarios', ['nomeOuDocumento' => $request->nomeOuDocumentoUsuario]);
    }

    //GET
    public function mostrarUsuarios($nomeOuDoc){
        $dadosDb = UsuarioModel::orderBy('name');
        
		if ($nomeOuDoc == 'todos'){
        	$dadosDb->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo');
        	$dadosDb = $dadosDb->paginate(15);

        	foreach($dadosDb as $valor){
	            if ($valor->tipoDoc == "CPF"){
	                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
	            }    
	        }
	        
	        return view ('auth/listagemUsuarios', compact('dadosDb'));
        }
        
        $arrayPalavras = explode(' ', $nomeOuDoc);

        foreach ($arrayPalavras as $palavra) {
            $dadosDb->where('name', 'like', '%' . $palavra . '%');
        }   

        $dadosDb->orWhere('numeroDoc', 'like', '%' . $nomeOuDoc . '%');
        $dadosDb->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo');

        $dadosDb = $dadosDb->paginate(15);
        

        if ($dadosDb->isEmpty()){
            return redirect('pesquisarUsuarios')->with('message', 'Nenhum usuário encontrado.');
        }

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }
        
        return view ('auth/listagemUsuarios', compact('dadosDb'));
    }

    //GET
    public function carregarTodosUsuarios(){
        $dadosDb = UsuarioModel::orderBy('name');

        $dadosDb->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo');
        $dadosDb = $dadosDb->paginate(15);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }

        return view('auth/listagemUsuarios', compact('dadosDb'));
    }

    //GET
    public function carregarInformacoesUsuario($id){
        $dadosDb = UsuarioModel::orderBy('name');

        $dadosDb->where('id', '=', $id);
        $dadosDb->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo');
        $dadosDb = $dadosDb->get();
        //dd($dadosDb);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }

        return view('auth/register', compact('dadosDb'));
    }

    //POST
    public function editarUsuario(Request $request){

        
        if($request->tipoDoc == "CPF"){
            $request->numeroDoc = $this->ajeitarCPF($request->numeroDoc);
            $cpfValido = $this->validarCPF($request->numeroDoc);
            if ($cpfValido == false){
                return redirect()->back()->with('message', "CPF Inválido!");
            }
        }

        $token = Str::random(60);

        $dadosDb = UsuarioModel::orderBy('name');
        $dadosDb->where('id', '=', $request->id);

        $dadosDb2 = UsuarioModel::orderBy('name');
        $dadosDb2->where('numeroDoc', '=', $request->numeroDoc);
        $dadosDb2->where('id', '!=', $request->id);
        $dadosDb2 = $dadosDb2->get();

        if($dadosDb2->isEmpty()){
            
            $dadosDb->update(['idGrupo' => $request->idGrupo, 'name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password), 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'remember_token' => $token]);
            
        } else {
            return redirect()->back()->with('message', 'Número de documento já existente!');
        }
        
        return redirect('pesquisarUsuarios')->with('sucesso', 'Usuário alterado com sucesso!');
    }

    //GET
    public function apagarUsuario($id){
        $dadosDb = UsuarioModel::orderBy('name');
        
        $dadosDb->where('id', '=', $id);

        $dadosDb->update(['status' => 'Desativado']);
        
        return redirect('pesquisarUsuarios')->with('sucesso', 'Usuário desativado com sucesso!');
    }

    public function ajeitarCPF($cpf){
        $elemento = explode(".",$cpf);
        $primeiraParte = $elemento[0];
        $segundaParte = $elemento[1];
        $terceiraParte = $elemento[2];

        $elemento2 = explode("-", $terceiraParte);
        $terceiraParte = $elemento2[0];
        $quartaParte = $elemento2[1];
        
        $resultado = $primeiraParte . $segundaParte . $terceiraParte . $quartaParte;
        
        return $resultado;
    }

    public function validarCPF($cpf) {
 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

    public static function remontarCPF($cpf){
        
        $cpf = substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-' . substr($cpf,9,2);
                
        return $cpf;
    }
}
