@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = '/';</script>";
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
            
            // Verificação se o usuário está ativado ou desativado
            if(isset($dados)){
                if($dados[0]->status == "Desativado"){
                    echo "<script> alert('Não é possível acessar o sistema pois este usuário está desativado!'); </script>";
                    echo "<script> $('#logout-form').submit(); </script>";
                }
            }

            foreach ($dados as $dado){
                $nomeExibicao = $dado->nome;
            }
        ?>

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

                        <form method="POST" action="/procurarVisitanteHome">
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
        
        <div class="row justify-content-center resp">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header" style="color: red; font-size: 16"><b>Resultado da Busca</b></div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Foto</th>
                                <th scope='col' style='vertical-align:middle'>Nome</th>
                                <th scope='col' style='vertical-align:middle'>Tipo de Documento</th>
                                <th scope='col' style='vertical-align:middle'>Número do Documento</th>
                                <th scope='col' style='vertical-align:middle'></th>
                                <th scope='col' style='vertical-align:middle'></th>
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
                                    echo "<td scope='col'>".$tipoDocumento."</td>";
                                    echo "<td scope='col'>".$valor->numeroDoc."</td>";
                                    echo "<td scope='col'><a class='btn btn-primary' href='". route('cadastroVisita', ['nome' => $valor->nomeVisitante, 'visitanteID' => $valor->visitanteID]) . "' role='button'>Cadastrar Visita</a></td>";
                                    echo "<td scope='col'><a class='btn btn-primary' href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) . "' role='button'>Editar</a></td>";
                                    echo "</tr>";

                                    //Modal
                                    echo "<div class='modal fade' id='myModal". $aux ."' role='dialog'>";
                                    echo "<div class='modal-dialog row justify-content-center'>";
                                    echo "<img src='/abrir/" . $valor->urlFoto . "' width='400px' height='400px'>";
                                    echo "</div>";
                                    echo "</div>";
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

        <br>
        <?php 
            if (isset($dadosDb2)){ 
                if(!$dadosDb2->isEmpty()){
        ?>
        
        <div class="row justify-content-center resp">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="color: red; font-size: 16"><b>Visitas ATIVAS</b></div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Foto</th>
                                <th scope='col' style='vertical-align:middle'>Nome do Visitante</th>
                                <th scope='col' style='vertical-align:middle'>Tipo de Documento</th>
                                <th scope='col' style='vertical-align:middle'>Número do Documento</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Visita</th>
                                <th scope='col' style='vertical-align:middle'>Número do Crachá</th>
                                <th scope='col' style='vertical-align:middle'>Local</th>
                                @if($nomeExibicao == "Administrador")
                                    <th scope='col' style='vertical-align:middle'></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $aux1 = 1;
                                foreach ($dadosDb2 as $valor) {
                                    $tipoDocumento = $valor->tipoDoc;   
                                    if($valor->tipoDoc == "carteiraTrabalho"){
                                        $tipoDocumento = "Carteira de Trabalho";
                                    }                 
                                    echo "<tr>";
                                    echo "<td scope='col'> <img src='/abrir/". $valor->urlFoto ."' width='50px' height='50px' data-toggle='modal' data-target='#myModal". $aux1 ."'> </td>";
                                    echo "<td scope='col'><a href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) ."'>". $valor->nomeVisitante ."</a></td>"; 
                                    echo "<td scope='col'>".$tipoDocumento."</td>";
                                    echo "<td scope='col'>".$valor->numeroDoc."</td>";
                                    echo "<td scope='col'>".$valor->dataHora."</td>";
                                    echo "<td scope='col'>".$valor->numeroCracha."</td>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalConfirmarSaida" . $aux1 . "'>Registrar Saída</button></td>";
                                    echo "</tr>";

                                    //Modal
                                    echo "<div class='modal fade' id='myModal". $aux1 ."' role='dialog'>";
                                    echo "<div class='modal-dialog row justify-content-center'>";
                                    echo "<img src='/abrir/" . $valor->urlFoto . "' width='400px' height='400px'>";
                                    echo "</div>";
                                    echo "</div>";

                                    //Modal Confirmar Registro de Saída
                                    echo "<div id='modalConfirmarSaida" . $aux1 . "' class='modal fade' role='dialog'>";
                                    echo "<div class='modal-dialog'>";
                                    //Conteúdo do Modal
                                    echo "<div class='modal-content'>";
                                    echo "<div class='modal-header'>";
                                    echo "<h4 class='modal-title'>Confirmar Registro de Saída</h4>";
                                    echo "</div>";
                                    echo "<div class='modal-body'>";
                                    echo "<p>Deseja mesmo registrar a saída desse visitante?</p>";
                                    echo "</div>";
                                    echo "<div class='modal-footer'>";
                                    echo "<a class='btn btn-primary' href='". route('registrarSaida', ['visitaID' => $valor->visitaID]) . "' role='button'>Sim</a>";
                                    echo "<button type='button' class='btn btn-primary' data-dismiss='modal'>Cancelar</button>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    
                                    $aux1++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <span style="float: right; margin-top: 5px">{{$dadosDb2->links()}}</span>
            </div>
        </div>

        <?php 
                }
            } 
        ?>
        
    </div>  

    @endguest

@endsection