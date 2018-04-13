<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitaModel;
use App\Models\VisitanteModel;
use App\Models\LocalModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VisitaController extends Controller
{
    //POST
    public function cadastrarVisita(Request $request){
        
        $request->dataHora = $request->dataHora . ":00";
        $request->dataHora = $this->ajeitaDataHora2($request->dataHora);

    	$dadosDb = VisitaModel::orderBy('visitaID');
    	$dadosDb->insert(['visitanteID' => $request->visitanteID, 'localID' => $request->localID, 'userID' => $request->userID, 'dataHora' => $request->dataHora ,'visitado' => $request->visitado, 'assunto' => $request->assunto, 'numeroCracha' => $request->numeroCracha]);


        return redirect('/home')->with('sucesso', 'Visita Cadastrada com sucesso.');
    	//return view('/home');
    }

    //POST
    public function procurarVisita(Request $request){
        if($request->dataInicial == null){
            return redirect('pesquisarVisitas')->with('message', 'Por favor selecione uma Data Inicial!');
        }

        if($request->dataFinal == null){
            return redirect('pesquisarVisitas')->with('message', 'Por favor selecione uma Data Final!');
        }

        if($request->dataFinal < $request->dataInicial){
            return redirect('pesquisarVisitas')->with('message', 'A Data Final deve ser maior que a Data Inicial!');
        }

        if ((string)$request->dataInicial > date('Y-m-d')){
            return redirect('pesquisarVisitas')->with('message', 'A Data Inicial não pode ser um dia que ainda não aconteceu!');
        }

        $request->dataInicial = $this->ajeitaDataUrl($request->dataInicial);
        $request->dataFinal = $this->ajeitaDataUrl($request->dataFinal);

        return redirect()->route('mostrarVisitas', ['dataInicial' => $request->dataInicial, 'dataFinal' => $request->dataFinal]);
        
    }

    //GET
    public function mostrarVisitas($dataInicial, $dataFinal){
        $dataInicial = $this->ajeitaDataUrl2($dataInicial);
        $dataFinal = $this->ajeitaDataUrl2($dataFinal);
        
        $dadosDb = VisitaModel::orderBy('visitaID', 'desc');
        $dadosDb->whereBetween('dataHora', [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
        $dadosDb->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb->count();
        $dadosDb = $dadosDb->paginate(20);
        //dd($dadosDb);

        if ($dadosDb->isEmpty()){
            return redirect('pesquisarVisitas')->with('message', 'Nenhum dado encontrado.');
        }

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }

            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora); 

            if($valor->dataHoraSaida != null){
                $valor->dataHoraSaida = $this->ajeitaDataHora($valor->dataHoraSaida);
            }
        }


        return View('visita/listagemVisitas',compact('dadosDb'));
    }

    //GET
    public function carregarInformacoes($nome, $visitanteID){
    	$dadosDb = LocalModel::orderBy('nomeLocal');
        $dadosDb = $dadosDb->get();
        
        $dadosDb2 = VisitanteModel::orderBy('visitanteID');
        $dadosDb2->where('visitanteID', '=', $visitanteID);
        $dadosDb2 = $dadosDb2->get();

        $dadosDb3 = VisitaModel::orderBy('numeroCracha');
        $dadosDb3->select('numeroCracha');
        $dadosDb3->where('dataHoraSaida', '=', null);
        $dadosDb3->where('numeroCracha', '!=', null);
        $dadosDb3 = $dadosDb3->get();

        $arrayDataFiltro =[]; 
        
        foreach ($dadosDb as $valor) {
            array_push($arrayDataFiltro,$valor);
        }

        $arrayDataFiltro = json_encode($arrayDataFiltro);
        $dadosDb = $arrayDataFiltro;    
        //dd($dadosDb);
                                
        return View('visita/cadastroVisita',compact('dadosDb', 'dadosDb2', 'dadosDb3', 'nome', 'visitanteID'));
    }

    //GET
    public function carregarTodasVisitas(){
        $dadosDb = VisitaModel::orderBy('visitaID', 'desc');

        $dadosDb->join('users', 'visita.userID', '=', 'users.id');
        $dadosDb->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        
        $dadosDb = $dadosDb->paginate(20);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }

            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora); 

            if($valor->dataHoraSaida != null){
                $valor->dataHoraSaida = $this->ajeitaDataHora($valor->dataHoraSaida);
            }
        }

        return view('visita/listagemVisitas', compact('dadosDb'));
    }

    //GET
    public function carregarTodasVisitasAtivas(){
        $dadosDb = VisitaModel::orderBy('visitaID', 'desc');

        $dadosDb->where('dataHoraSaida', '=', null);
        $dadosDb->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        
        $dadosDb = $dadosDb->paginate(20);

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }

            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora); 
        }

        return view('visita/listagemVisitasAtivas', compact('dadosDb'));
    }

    //GET
    public function carregarTodasVisitasAtivasHome(){
        $dadosDb2 = VisitaModel::orderBy('visitaID', 'desc');

        $dadosDb2->where('dataHoraSaida', '=', null);
        $dadosDb2->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb2->join('local', 'visita.localID', '=', 'local.localID');
        
        $dadosDb2 = $dadosDb2->paginate(20);

        foreach($dadosDb2 as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }

            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora); 
        }

        return view('/home', compact('dadosDb2'));
    }

    //GET
    public function verVisita($visitaID){
        $dadosDb = VisitaModel::orderBy('visitaID');

        $dadosDb->where('visitaID', '=', $visitaID);
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb = $dadosDb->get();

        $dadosDb2 = VisitanteModel::orderBy('visitanteID');
        $dadosDb2->where('visitanteID', '=', $dadosDb[0]->visitanteID);
        $dadosDb2 = $dadosDb2->get();

        $dadosDb3 = LocalModel::orderBy('localID');
        $dadosDb3 = $dadosDb3->get();

        $dadosDb4 = VisitaModel::orderBy('numeroCracha');
        $dadosDb4->select('numeroCracha');
        $dadosDb4->where('dataHoraSaida', '=', null);
        $dadosDb4->where('numeroCracha', '!=', null);
        $dadosDb4 = $dadosDb4->get();

        foreach($dadosDb as $valor){
            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora);

            if($valor->dataHoraSaida != null){
                $valor->dataHoraSaida = $this->ajeitaDataHora($valor->dataHoraSaida);
            }
        }

        return view('visita/editarVisita', compact('dadosDb', 'dadosDb2', 'dadosDb3', 'dadosDb4'));
    }

    //GET
    public function apagarVisita($visitaID){
        $dadosDb = VisitaModel::orderBy('visitaID');

        $dadosDb->where('visitaID', '=', $visitaID);
        $dadosDb->delete();

        return redirect()->back()->with('sucesso', 'Visita Apagada com Sucesso.');
    }

    //GET
    public function registrarSaida($visitaID){
        $dadosDb = VisitaModel::orderBy('visitaID');

        $dadosDb->where('visitaID', '=', $visitaID);
        $dadosDb->update(['dataHoraSaida' => date('Y-m-d H:i:s')]);

        return redirect('/home')->with('sucesso', 'Registro de saída de visitante realizado com Sucesso.');
    }

    //POST
    public function editarVisita(Request $request){
        
        $request->dataHora = $request->dataHora . ":00";
        $request->dataHora = $this->ajeitaDataHora2($request->dataHora);

        if($request->dataHoraSaida != null){
            $request->dataHoraSaida = $request->dataHoraSaida . ":00";
            $request->dataHoraSaida = $this->ajeitaDataHora2($request->dataHoraSaida);
        }
        
        $dadosDb = VisitaModel::orderBy('visitaID');

        $dadosDb->where('visitaID', '=', $request->visitaID);
        $dadosDb->update(['localID' => $request->localID, 'dataHora' => $request->dataHora, 'dataHoraSaida' => $request->dataHoraSaida, 'numeroCracha' => $request->numeroCracha, 'visitado' => $request->visitado, 'assunto' => $request->assunto]);

        // return redirect('pesquisarVisitas')->with('sucesso', 'Visita Alterada com Sucesso');
        return redirect()->route('verVisita', ['visitaID' => $request->visitaID])->with('sucesso', 'Visita Alterada com Sucesso');
    }

    //POST
    public function relatorioVisitantesSEMFA(Request $request){
        
        if($request->dataInicial == null){
            return redirect('relatorioVisitantesSEMFA')->with('message', 'Por favor selecione uma Data Inicial!');
        }

        if($request->dataFinal == null){
            return redirect('relatorioVisitantesSEMFA')->with('message', 'Por favor selecione uma Data Final!');
        }

        if($request->dataFinal < $request->dataInicial){
            return redirect('relatorioVisitantesSEMFA')->with('message', 'A Data Final deve ser maior que a Data Inicial!');
        }

        if ((string)$request->dataInicial > date('Y-m-d')){
            return redirect('relatorioVisitantesSEMFA')->with('message', 'A Data Inicial não pode ser um dia que ainda não aconteceu!');
        }

        $request->dataInicial = $this->ajeitaDataUrl($request->dataInicial);
        $request->dataFinal = $this->ajeitaDataUrl($request->dataFinal);

        return redirect()->route('mostrarVisitasSEMFA', ['dataInicial' => $request->dataInicial, 'dataFinal' => $request->dataFinal]); 
    }

    //GET
    public function mostrarVisitasSEMFA($dataInicial, $dataFinal){
        $dataInicial = $this->ajeitaDataUrl2($dataInicial);
        $dataFinal = $this->ajeitaDataUrl2($dataFinal);

        $dadosDb = VisitaModel::orderBy('visitaID', 'desc');
        $dadosDb->whereBetween('dataHora', [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
        $dadosDb->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb->count();
        $dadosDb = $dadosDb->paginate(15);
        //dd($dadosDb);

        if ($dadosDb->isEmpty()){
            return redirect('relatorioVisitantesSEMFA')->with('message', 'Nenhum dado encontrado.');
        }

        foreach($dadosDb as $valor){
            if ($valor->tipoDoc == "CPF"){
                $valor->numeroDoc = $this->remontarCPF($valor->numeroDoc);
            }

            $valor->dataHora = $this->ajeitaDataHora($valor->dataHora);
            
            if($valor->dataHoraSaida != null){
                $valor->dataHoraSaida = $this->ajeitaDataHora($valor->dataHoraSaida);
                $valor->tempoGasto = $this->calculaTempoVisita($valor->dataHora, $valor->dataHoraSaida);
            } else {
                $valor->tempoGasto = $this->calculaTempoVisita($valor->dataHora, date('d/m/Y H:i:s'));
            }

        }

        $dadosDb2 = VisitaModel::orderBy('visitaID');
        $dadosDb2->selectRaw('COUNT(*) as quantidade, nomeVisitante');
        $dadosDb2->whereBetween('dataHora', [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
        $dadosDb2->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb2->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb2->groupBy('nomeVisitante');
        $dadosDb2 = $dadosDb2->get();

        $dadosDb3 = VisitaModel::orderBy('visitaID', 'desc');
        $dadosDb3->whereBetween('dataHora', [$dataInicial . ' 00:00:01', $dataFinal . ' 23:59:59']);
        $dadosDb3->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb3->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb3->count();
        $dadosDb3 = $dadosDb3->get();

        $totalHoras = 0;
        $totalMinutos = 0;
        $contador = 0;

        foreach($dadosDb3 as $valor3){   
            if($valor3->dataHoraSaida != null){
                $valor3->tempoGasto = $this->calculaTempoVisita($valor3->dataHora, $valor3->dataHoraSaida);
            } else {
                $valor3->tempoGasto = $this->calculaTempoVisita($valor3->dataHora, date('Y-m-d H:i:s'));
            }

            $totalHoras = $totalHoras + $valor3->tempoGasto[1];
            $totalMinutos = $totalMinutos + $valor3->tempoGasto[2];

            if(($valor3->tempoGasto[1] != 0) || ($valor3->tempoGasto[2] != 0)){
                $contador++;
            }
        }

        $horasAux = $totalHoras * 60;
        $tempoTotal = $horasAux + $totalMinutos;

        $tempoMedio = $tempoTotal/$contador;
        $tempoMedio = (int)$tempoMedio;

        return View('relatorios/relatorioVisitantesSEMFA',compact('dadosDb', 'dadosDb2', 'dadosDb3', 'tempoMedio'));
    }

    //POST
    public function relatorioVisitantesPorSetor(Request $request){

        if($request->dataInicial == null){
            return redirect('relatorioVisitantesPorSetor')->with('message', 'Por favor selecione uma Data Inicial!');
        }

        if($request->dataFinal == null){
            return redirect('relatorioVisitantesPorSetor')->with('message', 'Por favor selecione uma Data Final!');
        }

        if($request->dataFinal < $request->dataInicial){
            return redirect('relatorioVisitantesPorSetor')->with('message', 'A Data Final deve ser maior que a Data Inicial!');
        }

        if ((string)$request->dataInicial > date('Y-m-d')){
            return redirect('relatorioVisitantesPorSetor')->with('message', 'A Data Inicial não pode ser um dia que ainda não aconteceu!');
        }

        $dadosDb = VisitaModel::orderBy('nomeLocal');
        $dadosDb->selectRaw('COUNT(*) as quantidade, nomeLocal');
        $dadosDb->whereBetween('dataHora', [$request->dataInicial . ' 00:00:01', $request->dataFinal . ' 23:59:59']);
        $dadosDb->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb->groupBy('nomeLocal');
        $dadosDb = $dadosDb->get();
        //dd($dadosDb);

        if ($dadosDb->isEmpty()){
            return redirect('relatorioVisitantesPorSetor')->with('message', 'Nenhum dado encontrado.');
        }

        $dadosDb2 = VisitaModel::orderBy('visitaID');
        $dadosDb2->selectRaw('COUNT(*) as quantidade, nomeVisitante');
        $dadosDb2->whereBetween('dataHora', [$request->dataInicial . ' 00:00:01', $request->dataFinal . ' 23:59:59']);
        $dadosDb2->join('local', 'visita.localID', '=', 'local.localID');
        $dadosDb2->join('visitante', 'visita.visitanteID', '=', 'visitante.visitanteID');
        $dadosDb2->groupBy('nomeVisitante');
        $dadosDb2 = $dadosDb2->get();

        return View('relatorios/relatorioVisitantesPorSetor',compact('dadosDb', 'dadosDb2'));
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

    public function ajeitaDataHora2($dataHora){
        $elemento = explode("/", $dataHora);
        $dia = $elemento[0];
        $mes = $elemento[1];
        $resto = $elemento[2];

        $elemento2 = explode(" ", $resto);
        $ano = $elemento2[0];
        $resto2 = $elemento2[1];

        $elemento3 = explode(":", $resto2);
        $hora = $elemento3[0];
        $minuto = $elemento3[1];
        $segundo = $elemento3[2];

        $resultado = $ano . "-" . $mes . "-" . $dia . " " . $hora . ":" . $minuto . ":" . $segundo;

        return $resultado;
    }

    public function ajeitaDataUrl($data){
        $elemento = explode("-", $data);

        $ano = $elemento[0];
        $mes = $elemento[1];
        $dia = $elemento[2];

        $resultado = $dia . "-" . $mes . "-" . $ano;
        
        return $resultado;
    }

    public function ajeitaDataUrl2($data){
        $elemento = explode("-", $data);

        $dia = $elemento[0];
        $mes = $elemento[1];
        $ano = $elemento[2];

        $resultado = $ano . "-" . $mes . "-" . $dia;
        
        return $resultado;
    }

    public function calculaTempoVisita($dataInicial, $dataFinal){
        
        if($dataInicial > $dataFinal){
            $tempoGasto[0] = "A data/hora de entrada é maior que a data/hora da saída";
            $tempoGasto[1] = 0;
            $tempoGasto[2] = 0;

            return $tempoGasto;
        }

        $elemento = explode(" ", $dataInicial);
        $horaInicial = $elemento[1];
        $data1 = $elemento[0];

        $elemento2 = explode(" ", $dataFinal);
        $horaFinal = $elemento2[1];
        $data2 = $elemento2[0];
        
        $elemento3 = explode(":", $horaInicial);
        $horaInicialAux = $elemento3[0];
        $minutoInicialAux = $elemento3[1];

        $elemento4 = explode(":", $horaFinal);
        $horaFinalAux = $elemento4[0];
        $minutoFinalAux = $elemento4[1];

        if($data2 != $data1){
            $tempoGasto[0] = "Data de saída é diferente da data de entrada";
            $tempoGasto[1] = 0;
            $tempoGasto[2] = 0;

            return $tempoGasto;
        }

        if($minutoInicialAux > $minutoFinalAux){
            $minutoFinalAux = $minutoFinalAux + 60;
            $horaFinalAux = $horaFinalAux - 1;
        } 

        $diferencaMinuto = $minutoFinalAux - $minutoInicialAux;
        $diferencaHora = $horaFinalAux - $horaInicialAux;

        $tempoGasto[1] = $diferencaHora;
        $tempoGasto[2] = $diferencaMinuto;

        if($diferencaHora == 0 ){
            $tempoGasto[0] = $diferencaMinuto . " min";
        } else {
            if($tempoGasto[2] == 0 ){
                $tempoGasto[0] = $diferencaHora . " h";
            } else {
                $tempoGasto[0] = $diferencaHora . " h e " . $diferencaMinuto . " min";
            }
        }
        
        return $tempoGasto;
    }

    public static function remontarCPF($cpf){
        
        $cpf = substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-' . substr($cpf,9,2);
                
        return $cpf;
    }
}
