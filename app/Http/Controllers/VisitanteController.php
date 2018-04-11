<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitanteModel;
use App\Models\VisitaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Storage;

class VisitanteController extends Controller
{
    //POST
    public function cadastrarVisitante(Request $request){
        
        $files = $request->file('file');
        $files2 = Storage::files('/');
        $request->nome = strtoupper($request->nome);
        
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');

        if($request->tipoDoc == "CPF"){
            $request->numeroDoc = $this->ajeitarCPF($request->numeroDoc);
            $cpfValido = $this->validarCPF($request->numeroDoc);
            if ($cpfValido == false){
                return redirect()->back()->with('message', "CPF Inválido!");
            }
        }

        $dadosDb2 = VisitanteModel::orderBy('nomeVisitante');
        $dadosDb2->where('numeroDoc', '=', $request->numeroDoc);
        $dadosDb2 = $dadosDb2->get();
        
        if($dadosDb2->isEmpty()){
            if (!empty($files)){
                foreach ($files as $file){
                    
                    foreach ($files2 as $file2){
                        // somente foto
                        if ($file->getClientMimeType() != ("image/jpeg") && $file->getClientMimeType() != ("image/png")){
                            return redirect()->back()->with('message', 'Formato de arquivo inválido! Por favor envie somente foto!');
                        } 
                    }
                    
                    if($file->getClientMimeType() == ("image/jpeg")){
                        $extensao = ".jpg";
                    } else if ($file->getClientMimeType() == ("image/png")){
                        $extensao = ".png";
                    }

                    Storage::put(date('dmYHis'). '-' . $request->numeroDoc . $extensao, file_get_contents($file));
                    $dadosDb->insert(['nomeVisitante' => $request->nome, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'urlFoto' => date('dmYHis'). '-' . $request->numeroDoc . $extensao]);
                }
                    
            } else {                
                $dadosDb->insert(['nomeVisitante' => $request->nome, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'urlFoto' => 'avatar_padrao.jpg']);
            }       
        } else {
            return redirect()->back()->with('message', 'Número de documento já existente!');
        }

        if(isset($request->checkboxVisita)){
            $dadosDb3 = $this->redirecionarTelaCadastroVisita($request);

            return redirect()->route('cadastroVisita', ['nome' => $dadosDb3[0]->nomeVisitante, 'visitanteID' => $dadosDb3[0]->visitanteID])->with('sucesso', 'Visitante Cadastrado com sucesso.');
        }

        return redirect('/home')->with('sucesso', 'Visitante Cadastrado com sucesso.');
    }


    //POST
    public function procurarVisitante(Request $request){
    	
        if ($request->nomeOuDocumentoVisitante == null){
            $request->nomeOuDocumentoVisitante = 'todos';
        }

        return redirect()->route('mostrarVisitantes', ['nomeOuDocumento' => $request->nomeOuDocumentoVisitante]);
    }

    //GET
    public function mostrarVisitantes($nomeOuDoc){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');
        
        if ($nomeOuDoc == 'todos'){
            $dadosDb = $dadosDb->paginate(15);

            foreach($dadosDb as $valor){
                if ($valor->tipoDoc == "CPF"){
                    $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
                }    
            }
            
            return view ('visitante/listagemVisitantes', compact('dadosDb'));
        }

        $arrayPalavras = explode(' ', $nomeOuDoc);

        foreach ($arrayPalavras as $palavra) {
            $dadosDb->where('nomeVisitante', 'like', '%' . $palavra . '%');
        }   

        $dadosDb->orWhere('numeroDoc', 'like', '%' . $nomeOuDoc . '%');

        $dadosDb = $dadosDb->paginate(15);
        

        if ($dadosDb->isEmpty()){
            return redirect('pesquisarVisitantes')->with('message', 'Nenhum visitante encontrado.');
        }

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }
        
        return view ('visitante/listagemVisitantes', compact('dadosDb'));
    }

    //POST
    public function procurarVisitanteHome(Request $request){
        
        if ($request->nomeOuDocumentoVisitante == null){
            $request->nomeOuDocumentoVisitante = 'todos';
        }

        return redirect()->route('mostrarVisitantesHome', ['nomeOuDocumento' => $request->nomeOuDocumentoVisitante]);
    }

    //GET
    public function mostrarVisitantesHome($nomeOuDoc){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');

        if ($nomeOuDoc == 'todos'){
            $dadosDb = $dadosDb->paginate(15);

            foreach($dadosDb as $valor){
                if ($valor->tipoDoc == "CPF"){
                    $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
                }    
            }
            
            return view ('/home', compact('dadosDb'));
        }
        
        $arrayPalavras = explode(' ', $nomeOuDoc);

        foreach ($arrayPalavras as $palavra) {
            $dadosDb->where('nomeVisitante', 'like', '%' . $palavra . '%');
        }   

        $dadosDb->orWhere('numeroDoc', 'like', '%' . $nomeOuDoc . '%');

        $dadosDb = $dadosDb->paginate(15);

        if ($dadosDb->isEmpty()){
            return redirect('/home')->with('message', 'Nenhum visitante encontrado.');
        }

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }
        
        return view('/home', compact('dadosDb'));
    }

    //POST
    public function editarVisitante(Request $request){

        $files = $request->file('file');
        $files2 = Storage::files('/');
        $request->nomeVisitante = strtoupper($request->nomeVisitante);

        if($request->tipoDoc == "CPF"){
            $request->numeroDoc = $this->ajeitarCPF($request->numeroDoc);
            $cpfValido = $this->validarCPF($request->numeroDoc);
            if ($cpfValido == false){
                return redirect()->back()->with('message', "CPF Inválido!");
            }
        }

        $dadosDb = VisitanteModel::orderBy('nomeVisitante');
        $dadosDb->where('visitanteID', '=', $request->visitanteID);

        $dadosDb2 = VisitanteModel::orderBy('nomeVisitante');
        $dadosDb2->where('numeroDoc', '=', $request->numeroDoc);
        $dadosDb2->where('visitanteID', '!=', $request->visitanteID);
        $dadosDb2 = $dadosDb2->get();

        if($dadosDb2->isEmpty()){
            if(!empty($files)){
                
                foreach ($files as $file){
                    
                    foreach ($files2 as $file2){
                        // somente foto
                        if ($file->getClientMimeType() != ("image/jpeg") && $file->getClientMimeType() != ("image/png")){
                            return redirect()->back()->with('message', 'Formato de arquivo inválido! Por favor envie somente foto!');
                        } 
                    }

                    if($file->getClientMimeType() == ("image/jpeg")){
                        $extensao = ".jpg";
                    } else if ($file->getClientMimeType() == ("image/png")){
                        $extensao = ".png";
                    }

                    if ($request->urlFoto != "avatar_padrao.jpg"){
                        Storage::delete($request->urlFoto);
                    } 

                    Storage::put(date('dmYHis'). '-' . $request->numeroDoc . $extensao, file_get_contents($file));
                    $dadosDb->update(['nomeVisitante' => $request->nomeVisitante, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc, 'urlFoto' => date('dmYHis'). '-' . $request->numeroDoc . $extensao]);
                }
                
            } else{
                
                $dadosDb->update(['nomeVisitante' => $request->nomeVisitante, 'tipoDoc' => $request->tipoDoc, 'numeroDoc' => $request->numeroDoc]);
            }
        } else {
            return redirect()->back()->with('message', 'Número de documento já existente!');
        }
        
        return redirect('pesquisarVisitantes')->with('sucesso', 'Visitante alterado com sucesso!');
    }

    //GET
    public function apagarVisitante($visitanteID){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');
        
        $dadosDb->where('visitanteID', '=', $visitanteID);
        $dadosDb = $dadosDb->get();

        $dadosDb2 = VisitanteModel::orderBy('nomeVisitante');
        
        $dadosDb2->where('visitanteID', '=', $visitanteID);

        try{
            $dadosDb2->delete();    
            Storage::delete($dadosDb[0]->urlFoto);
        } catch(\Illuminate\Database\QueryException $ex){
            return redirect('pesquisarVisitantes')->with('message', 'Não é possível apagar esse visitante nesse momento, pois ele está ligado a alguma(s) visita(s).');
        }
        
        return redirect('pesquisarVisitantes')->with('sucesso', 'Visitante apagado com sucesso!');
    }

    //GET
    public function carregarInformacoesVisitante($visitanteID){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');

        $dadosDb->where('visitanteID', '=', $visitanteID);
        $dadosDb = $dadosDb->get();
        //dd($dadosDb);

        $dadosDb2 = VisitaModel::orderBy('dataHora', 'desc');

        $dadosDb2->where('visitanteID', '=', $visitanteID);
        $dadosDb2->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb2 = $dadosDb2->paginate(15);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }

        foreach($dadosDb2 as $valor2){
            $valor2->dataHora = $this->ajeitaDataHora($valor2->dataHora);
            if($valor2->dataHoraSaida != null){
                $valor2->dataHoraSaida = $this->ajeitaDataHora($valor2->dataHoraSaida);
            }   
        }
        
        return view('visitante/informacoesVisitante', compact('dadosDb', 'dadosDb2'));
    }

    //GET
    public function carregarTodosVisitantes(){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');

        $dadosDb = $dadosDb->paginate(15);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }    
        }

        return view('visitante/listagemVisitantes', compact('dadosDb'));
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

    public function ajeitaDataHora($dataHora){
        
        $elemento = explode(" ", $dataHora);
        $hora = $elemento[1];

        $elemento2 = explode("-",$elemento[0]);
        $ano = $elemento2[0];
        $mes = $elemento2[1];
        $dia = $elemento2[2];
        $resultado = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;
        
        return $resultado;
    }

    public function redirecionarTelaCadastroVisita($request){
        $dadosDb = VisitanteModel::orderBy('nomeVisitante');

        $dadosDb->where('numeroDoc', '=', $request->numeroDoc);
        $dadosDb = $dadosDb->get();

        return $dadosDb;
    }

}
