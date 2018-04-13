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
                $nomeExibicao = $dado->nome;
            }
        ?>

    <div class="container">
        <h1 style="text-align: center">Listagem de Visitas</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pesquisar Visita</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="/procurarVisita">
                            @csrf

                            <div class="form-group row">
                                <label for="dataInicialLabel" class="col-md-4 col-form-label text-md-right">Data Inicial</label>

                                <div class="col-md-6">
                                    <input id="dataInicial" type="date" class="form-control" name="dataInicial">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dataFinalLabel" class="col-md-4 col-form-label text-md-right">Data Final</label>

                                <div class="col-md-6">
                                    <input id="dataFinal" type="date" class="form-control" name="dataFinal">
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Procurar</button>
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

                            <!-- Sucesso -->
                            @if(session()->has('sucesso'))
                                <div class="form-group row mb-0 alert alert-success" style="font-size:16px">
                                    {{ session()->get('sucesso') }}
                                </div>
                            @endif
                            <!-- Fim sucesso -->
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <?php 
            if (isset($dadosDb)){ 
        ?>
        
        <div class="row justify-content-center resp">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Resultado</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Foto</th>
                                <th scope='col' style='vertical-align:middle'>Nome do Visitante</th>
                                <th scope='col' style='vertical-align:middle'>Documento</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Visita</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Saída</th>
                                <th scope='col' style='vertical-align:middle'>Local</th>
                                <th scope='col' style='vertical-align:middle'>Pessoa que Visitou</th>
                                <th scope='col' style='vertical-align:middle'>Assunto</th>
                                @if($nomeExibicao == "Administrador")
                                    <th scope='col' style='vertical-align:middle'>Porteiro</th>
                                    <th scope='col' style='vertical-align:middle'></th>
                                    <th scope='col' style='vertical-align:middle'></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $aux = 1;
                                foreach ($dadosDb as $valor) {
                                    $tipoDocumento = $valor->tipoDoc;   
                                    if($valor->tipoDoc == "carteiraTrabalho"){
                                        $tipoDocumento = "Carteira de Trabalho";
                                    }                 
                                    echo "<tr>";
                                    echo "<td scope='col'> <img src='/abrir/". $valor->urlFoto ."' width='50px' height='50px' data-toggle='modal' data-target='#myModal". $aux ."'> </td>";
                                    echo "<td scope='col'><a href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) ."'>". $valor->nomeVisitante ."</a></td>"; 
                                    echo "<td scope='col'>". $tipoDocumento . " - " . $valor->numeroDoc."</td>";
                                    echo "<td scope='col'>".$valor->dataHora."</td>";
                                    echo "<td scope='col'>".$valor->dataHoraSaida."</td>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'>".$valor->visitado."</td>";
                                    echo "<td scope='col'>".$valor->assunto."</td>";
                                    
                                    if($nomeExibicao == "Administrador"){
                                        echo "<td scope='col'>".$valor->name."</td>";
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


                                    if($nomeExibicao == "Administrador"){
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
                <span style="float: right; margin-top: 5px">{{$dadosDb->links()}}</span>
            </div>
        </div>

        <?php 
            } 
        ?>
    </div>

    @endguest

@endsection

@section('scriptAdd')

    <script>
        var d = new Date();
        
        var dia = d.getDate();
        var mes = d.getMonth() + 1;
        var ano = d.getFullYear();

        if(mes < 10){
            mes = "0" + mes;
        }

        if(dia < 10){
            dia = "0" + dia;
        }

        var dataInicial = ano + "-" + mes + "-" + "01";
        var dataFinal = ano + "-" + mes + "-" + dia;

        $(function () {
            $('#dataInicial').val(dataInicial);
            $('#dataFinal').val(dataFinal);
        }); 
        

    </script>

@endsection