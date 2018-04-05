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

            if ($nomeExibicao == "Administrador" || $nomeExibicao == "Moderador"){
        ?>

        <h1 style="text-align: center">Listagem de Usuários</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pesquisar Usuário</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="/procurarUsuario">
                            @csrf
                            <div class="form-group row">
                                <label for="visitadoLabel" class="col-md-4 col-form-label text-md-right">Nome ou Nº Documento</label>

                                <div class="col-md-6">
                                    <input id="nomeOuDocumentoUsuario" type="text" class="form-control" name="nomeOuDocumentoUsuario" placeholder="Apenas Letras ou Números">
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
            <div class="col-md-13">
                <div class="card">
                    <div class="card-header">Resultado</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Nome</th>
                                <th scope='col' style='vertical-align:middle'>Tipo de Documento</th>
                                <th scope='col' style='vertical-align:middle'>Número do Documento</th>
                                <th scope='col' style='vertical-align:middle'>E-mail</th>
                                <th scope='col' style='vertical-align:middle'>Grupo de Usuário</th>
                                <th scope='col' style='vertical-align:middle'>Status</th>
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
                                    echo "<td scope='col'><a href='". route('verPerfilUsuario', ['id' => $valor->id]) ."'>". $valor->name ."</a></td>"; 
                                    echo "<td scope='col'>".$tipoDocumento."</td>";
                                    echo "<td scope='col'>".$valor->numeroDoc."</td>";
                                    echo "<td scope='col'>".$valor->email."</td>";
                                    echo "<td scope='col'>".$valor->nome."</td>";
                                    echo "<td scope='col'>".$valor->status."</td>";
                                    echo "<td scope='col'><a class='btn btn-primary' id='buttonEditar' href='". route('verPerfilUsuario', ['id' => $valor->id]) . "' role='button'>Editar</a></td>";
                                    if($nomeExibicao == "Administrador"){
                                        echo "<td scope='col'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalConfirmar" . $aux . "'>Apagar</button></td>";
                                    }
                                    echo "</tr>";

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
                                        echo "<p>Deseja mesmo desativar o usuário " . $valor->name . "?</p>";
                                        echo "</div>";
                                        echo "<div class='modal-footer'>";
                                        echo "<a class='btn btn-primary' href='". route('apagarUsuario', ['id' => $valor->id]) . "' role='button'>Sim</a>";
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

    <?php
        
        } else{
             echo "<script> window.location.href = '/home';</script>";
        }
    ?>
    
@endguest


@endsection