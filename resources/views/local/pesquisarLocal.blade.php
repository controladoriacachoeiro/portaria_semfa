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

        if ($nomeGrupo == "Administrador" || $nomeGrupo == "Moderador"){

    ?>
    <div class="container">
        <h1 style="text-align: center">Listagem de Setores</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Pesquisar Setor</div>

                    <div class="card-body">
                        <form method="POST" action="/pesquisarLocalNome" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="nome" class="col-md-4 col-form-label text-md-right">Nome</label>

                                <div class="col-md-6">
                                    <input id="nomeLocal" type="text" class="form-control" name="nomeLocal" required autofocus>
                                </div>
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Resultado</div>
                        <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                            <thead>
                                <tr>
                                    <th scope='col' style='vertical-align:middle'>Nome</th>
                                    <th scope='col' style='vertical-align:middle'>Telefone</th>
                                    <th scope='col' style='vertical-align:middle'></th>
                                    <th scope='col' style='vertical-align:middle'></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?PHP
                                    $aux = 1;
                                    foreach ($dadosDb as $valor) {                    
                                        echo "<tr>";
                                        echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                        echo "<td scope='col'>".$valor->telefone."</td>";
                                        echo "<td scope='col'><a class='btn btn-primary' href='". route('verLocal', ['localID' => $valor->localID]) . "' role='button'>Editar</a></td>";
                                        echo "<td scope='col'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalConfirmar" . $aux . "'>Apagar</button></td>";
                                        echo "</tr>";

                                        //Modal Confirmar Exclusão
                                        echo "<div id='modalConfirmar" . $aux . "' class='modal fade' role='dialog'>";
                                        echo "<div class='modal-dialog'>";
                                        //Conteúdo do Modal
                                        echo "<div class='modal-content'>";
                                        echo "<div class='modal-header'>";
                                        echo "<h4 class='modal-title'>Confirmar Exclusão</h4>";
                                        echo "</div>";
                                        echo "<div class='modal-body'>";
                                        echo "<p>Deseja mesmo apagar o setor " . $valor->nomeLocal . "?</p>";
                                        echo "</div>";
                                        echo "<div class='modal-footer'>";
                                        echo "<a class='btn btn-primary' href='". route('apagarLocal', ['localID' => $valor->localID]) . "' role='button'>Sim</a>";
                                        echo "<button type='button' class='btn btn-primary' data-dismiss='modal'>Cancelar</button>";
                                        echo "</div>";
                                        echo "</div>";
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
    </div>

    <?php
        
        } else{
             echo "<script> window.location.href = '/home';</script>";
        }
    ?>

@endguest

@endsection

@section('scriptAdd')

    <script>
        $("#buttonVoltar").click(function() {
            window.history.go(-1);
        }); 
    </script>

@endsection
