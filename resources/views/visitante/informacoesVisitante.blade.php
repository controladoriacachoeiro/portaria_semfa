@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = '/home';</script>";
        ?>
    @else
    <?php

        $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 

        foreach ($dados as $dado){
            $nomeGrupo = $dado->nome;
        }

    ?>
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Informações do Visitante</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" id="idForm" action="/editarVisitante" enctype="multipart/form-data" data-toggle="validator" role="form">
                            @csrf

                            <div class="form-group row">
                                <label for="fotoLabel" class="col-md-4 col-form-label text-md-right"></label>

                                <div class="col-md-6">
                                    <img src='/abrir/{{$dadosDb[0]->urlFoto}}' id="fotoVisitante" name="fotoVisitante" width="100px" height="100px" data-toggle="modal" data-target="#myModal">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="visitadoLabel" class="col-md-4 col-form-label text-md-right">Nome</label>

                                <div class="col-md-6">
                                    <input id="nomeVisitante" type="text" class="form-control" name="nomeVisitante" value='{{$dadosDb[0]->nomeVisitante}}' placeholder="Digite o nome do visitante." required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px; border-radius: 5px"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="tipoDocumentoLabel" class="col-md-4 col-form-label text-md-right">Tipo de Documento</label>

                                <div class="col-md-6">
                                    <select id="tipoDoc" class="form-control" name="tipoDoc">
                                        <option value="CPF">CPF</option>
                                        <option value="RG">RG</option>
                                        <option value="carteiraTrabalho">Carteira de Trabalho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="docVisitanteLabel" class="col-md-4 col-form-label text-md-right">Nº Documento</label>

                                <div class="col-md-6">
                                    <input id="numeroDoc" type="text" class="form-control" name="numeroDoc" value='{{$dadosDb[0]->numeroDoc}}' placeholder="Digite o número do documento." required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fileLabel" class="col-md-4 col-form-label text-md-right">Nova Foto</label>

                                <div class="col-md-6">
                                    <input type="file" name="file[]" id="file">
                                </div>
                            </div>

                            <input id="visitanteID" type="hidden" class="form-control" name="visitanteID" value='{{$dadosDb[0]->visitanteID}}'>

                            <input id="urlFoto" type="hidden" class="form-control" name="urlFoto" value='{{$dadosDb[0]->urlFoto}}'>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Editar</button>

                                    <button type="button" class="btn btn-primary" style="float: right" id="buttonVoltar">Voltar</button>

                                </div>
                            </div>

                            <!-- Erro -->
                            @if(session()->has('message'))
                                <br>
                                <div class="form-group row mb-0 alert alert-danger" style="font-size:20px">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                            <!--Fim erro-->
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row justify-content-center">
            <div class="col-md-14">
                <div class="card">
                    <div class="card-header">Visitas deste Visitante</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Visita</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Saída</th>
                                <th scope='col' style='vertical-align:middle'>Número do Crachá</th>
                                <th scope='col' style='vertical-align:middle'>Local</th>
                                <th scope='col' style='vertical-align:middle'>Pessoa que Visitou</th>
                                <th scope='col' style='vertical-align:middle'>Assunto</th>
                                @if($nomeGrupo == "Administrador")
                                    <th scope='col' style='vertical-align:middle'></th>
                                    <th scope='col' style='vertical-align:middle'></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $aux = 1;
                                foreach ($dadosDb2 as $valor) {
                                    $tipoDocumento = $valor->tipoDoc;   
                                    if($valor->tipoDoc == "carteiraTrabalho"){
                                        $tipoDocumento = "Carteira de Trabalho";
                                    }                 
                                    echo "<tr>";
                                    echo "<td scope='col'>".$valor->dataHora."</td>";
                                    echo "<td scope='col'>".$valor->dataHoraSaida."</td>";
                                    echo "<td scope='col'>".$valor->numeroCracha."</td>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'>".$valor->visitado."</td>";
                                    echo "<td scope='col'>".$valor->assunto."</td>";
                                    
                                    if($nomeGrupo == "Administrador"){
                                        echo "<td scope='col'><a class='btn btn-primary' href='". route('verVisita', ['visitaID' => $valor->visitaID]) . "' role='button'>Editar</a></td>";
                                        echo "<td scope='col'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalConfirmar" . $aux . "'>Apagar</button></td>";
                                    }
                                    
                                    echo "</tr>";

                                    //Modal
                                    echo "<div class='modal fade' id='myModal". $aux ."' role='dialog'>";
                                    echo "<div class='modal-dialog row justify-content-center'>";
                                    echo "<img src='/abrir/" . $valor->urlFoto . "' width='400px' height='400px'>";
                                    echo "</div>";
                                    echo "</div>";


                                    if($nomeGrupo == "Administrador"){
                                        //Modal Confirmar Exclusão
                                        echo "<div id='modalConfirmar" . $aux . "' class='modal fade' role='dialog'>";
                                        echo "<div class='modal-dialog'>";
                                        //Conteúdo do Modal
                                        echo "<div class='modal-content'>";
                                        echo "<div class='modal-header'>";
                                        echo "<h4 class='modal-title'>Confirmar Exclusão</h4>";
                                        echo "</div>";
                                        echo "<div class='modal-body'>";
                                        echo "<p>Deseja mesmo apagar essa visita?</p>";
                                        echo "</div>";
                                        echo "<div class='modal-footer'>";
                                        echo "<a class='btn btn-primary' href='". route('apagarVisita', ['visitaID' => $valor->visitaID]) . "' role='button'>Sim</a>";
                                        echo "<button type='button' class='btn btn-primary' data-dismiss='modal'>Cancelar</button>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    $aux++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <span style="float: right; margin-top: 5px">{{$dadosDb2->links()}}</span>
            </div>
        </div>

    </div>  

    <!--Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog row justify-content-center">
            <img src="/abrir/{{$dadosDb[0]->urlFoto}}" width="400px" height="400px">
        </div>
    </div>

@endguest

@endsection

@section('scriptAdd')
    <script>
        
        // LoadPage
        $(function () {
            $(document).ready(function() {
                var x = <?php echo $dadosDb ?>;
            });
        });

        $("#buttonVoltar").click(function() {
            window.history.go(-1);
        });

        var $seuCampoNome = $("#nomeVisitante");
        $seuCampoNome.mask('Z',{translation: {'Z': {pattern: /[a-zA-ZáàâãéèêíìïóòôõöúçñÁÀÂÃÉÈÊÍÌÏÓÒÔÕÖÚÇÑ ]/, recursive: true}}});

        $("#tipoDoc").val($('option:contains({{$dadosDb[0]->tipoDoc}})').val());
        
        if('{{$dadosDb[0]->tipoDoc}}' == "carteiraTrabalho"){
            $("#tipoDoc").val($('option:contains(Carteira de Trabalho)').val());
        }


    </script>
@endsection