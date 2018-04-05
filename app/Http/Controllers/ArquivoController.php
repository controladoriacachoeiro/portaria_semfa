<?php

namespace App\Http\Controllers;


use Storage;

use App\Models\TesteModel;
use Illuminate\Http\Request;

class ArquivoController extends Controller
{
    public function abrirArquivo($nomeArquivo){
        
        $file_path = public_path('../storage/app/'.$nomeArquivo);
        
        return response()->file($file_path);
    }

    public function excluirArquivo($nomeArquivo){

        $dadosDb = TesteModel::orderBy('nome');

        Storage::delete($nomeArquivo);
        
        $dadosDb->where('nome', '=', $nomeArquivo);
        $dadosDb->delete();


        return redirect()->back();

    }
}
