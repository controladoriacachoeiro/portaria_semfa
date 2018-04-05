<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;

use App\Models\TesteModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class UploadController extends Controller
{
    public function upload(Request $request){
        $files = $request->file('file');
 
        $dadosDb = TesteModel::orderBy('nome');

        $files2 = Storage::files('/');
        
        if (!empty($files)){
            foreach ($files as $file){
                
                foreach ($files2 as $file2){
                    // Comparando se o nome e o tamanho dos arquivos já existem. Talvez se comparasse o conteúdo do arquivo fosse mais eficaz pra saber se os arquivos são realmente duplicados, mas talvez demore um pouco mais para fazer essa comparação
                    if ($file->getClientOriginalName() == $file2 && $file->getClientSize() == Storage::size($file2)){
                        //Implementar o método de sobrescrever os arquivos

                        //talvez armazenar um valor em uma variável caso a opção selecionada seja "ok", e verificar essa variável lá em baixo, antes de dar o return
                        echo "<script>descricao = confirm('Arquivo já existente, deseja substituí-lo?');if(descricao == false){}</script>";
                        
                        //return redirect()->back()->with('message', "Arquivo já existente!");
                    } 

                    // somente formato pdf
                    if ($file->getClientMimeType() != "application/pdf"){
                        return redirect()->back()->with('message', 'Formato de arquivo inválido! Por favor envie somente PDF!');
                    }
                }

                Storage::put($file->getClientOriginalName(), file_get_contents($file));
                $dadosDb->insert(['nome' => $file->getClientOriginalName(), 'nomeExibicao' => $request->nomeExibicao]);
            }
        }
        return redirect()->back()->with('message', 'Arquivo Enviado com Sucesso!');
        //return \Response::json(array('success' => true));
    }

    public function mostrarArquivos(){
        $dadosDb = TesteModel::orderBy('nome');
        
        $dadosDb = $dadosDb->get();        
                                
        return View('arquivos',compact('dadosDb'));
    }
}
