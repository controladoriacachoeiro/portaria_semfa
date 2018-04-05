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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tela de Cadastro</div>

                    <div class="card-body">
                        <form method="POST" id="idForm" action="/editarLocal" enctype="multipart/form-data" data-toggle="validator" role="form">
                            @csrf

                            <div class="form-group row">
                                <label for="nome" class="col-md-4 col-form-label text-md-right">Nome</label>

                                <div class="col-md-6">
                                    <input id="nomeLocal" type="text" class="form-control" name="nomeLocal" value="{{$dadosDb[0]->nomeLocal}}" required autofocus>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="telefoneLabel" class="col-md-4 col-form-label text-md-right">Telefone</label>

                                <div class="col-md-6">
                                    <input id="telefone" type="text" class="form-control" name="telefone" value="{{$dadosDb[0]->telefone}}" required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <input type="hidden" id="localID" name="localID" value="{{$dadosDb[0]->localID}}"></button>
                                </div>
                            </div>

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
