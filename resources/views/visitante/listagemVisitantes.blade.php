@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = '/home';</script>";
        ?>
    @else
    <div class="container">
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <?php 
            $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 

            foreach ($dados as $dado){
                $nomeExibicao = $dado->nome;
            }
        ?>

        <h1 style="text-align: center">Listagem de Visitantes</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pesquisar Visitante</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="/procurarVisitante">
                            @csrf
                            <div class="form-group row">
                                <label for="visitadoLabel" class="col-md-4 col-form-label text-md-right">Nome ou Nº Documento</label>

                                <div class="col-md-6">
                                    <input id="nomeOuDocumentoVisitante" type="text" class="form-control" name="nomeOuDocumentoVisitante" placeholder="Apenas Letras ou Números">
                                </div>
                                <button type="submit" class="btn btn-primary">Procurar</button>
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
                                <br>
                                <div class="form-group row mb-0 alert alert-success" style="font-size:20px">
                                    {{ session()->get('sucesso') }}
                                </div>
                            @endif
                            <!--Fim sucesso-->

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <?php 
            if (isset($dadosDb)){ 
        ?>
        
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Resultado</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Foto</th>
                                <th scope='col' style='vertical-align:middle'>Nome</th>
                                <th scope='col' style='vertical-align:middle'>Tipo de Documento</th>
                                <th scope='col' style='vertical-align:middle'>Número do Documento</th>
                                <th scope='col' style='vertical-align:middle'></th>
                                <th scope='col' style='vertical-align:middle'></th>
                                @if($nomeExibicao == "Administrador")
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
                                    echo "<td scope='col'> <img src='/abrir/". $valor->urlFoto ."' id='fotoVisitante' name='fotoVisitante' width='50px' height='50px' data-toggle='modal' data-target='#myModal". $aux ."'> </td>";
                                    echo "<td scope='col'><a href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) ."'>". $valor->nomeVisitante ."</a></td>"; 
                                    echo "<td scope='col'>".$tipoDocumento."</td>";
                                    echo "<td scope='col'>".$valor->numeroDoc."</td>";
                                    echo "<td scope='col'><a class='btn btn-primary' href='". route('cadastroVisita', ['nome' => $valor->nomeVisitante, 'visitanteID' => $valor->visitanteID]) . "' role='button'>Cadastrar Visita</a></td>";
                                    echo "<td scope='col'><a class='btn btn-primary' id='buttonEditar' href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) . "' role='button'>Editar</a></td>";
                                    if($nomeExibicao == "Administrador"){
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
                                        echo "<p>Deseja mesmo apagar o(a) visitante " . $valor->nomeVisitante . "?</p>";
                                        echo "</div>";
                                        echo "<div class='modal-footer'>";
                                        echo "<a class='btn btn-primary' href='". route('apagarVisitante', ['visitanteID' => $valor->visitanteID]) . "' role='button'>Sim</a>";
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