@extends('layouts.app')

@section('content')

@guest
    <?php  
        echo "<script> window.location.href = '/home';</script>";
    ?>
@else

<div class="container">
    <?php 
        $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 

        foreach ($dados as $dado){
            $nomeExibicao = $dado->nome;
        }

        if ($nomeExibicao == "Administrador" || $nomeExibicao == "Moderador"){
    ?>

    <h1 style="text-align: center">Cadastro de Usuário</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tela de Cadastro de Usuário</div>
                @if(isset($dadosDb))
                <div class="card-body">
                    <form method="POST" id="idForm" action="/editarUsuario" data-toggle="validator" role="form">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$dadosDb[0]->name}}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$dadosDb[0]->email}}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar Senha</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
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
                            <label for="numeroDocumentoLabel" class="col-md-4 col-form-label text-md-right">Número do Documento</label>

                            <div class="col-md-6">
                                <input id="numeroDoc" type="text" class="form-control{{ $errors->has('numeroDoc') ? ' is-invalid' : '' }}" name="numeroDoc" value="{{$dadosDb[0]->numeroDoc}}" required>
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>

                            @if ($errors->has('numeroDoc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('numeroDoc') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="tipoDocumentoLabel" class="col-md-4 col-form-label text-md-right">Grupo de Usuário</label>

                            <div class="col-md-6">
                                <select id="idGrupo" class="form-control" name="idGrupo">
                                    <option value="1">Administrador</option>
                                    <option value="3">Usuário</option>
                                </select>
                            </div>
                        </div>

                        <input id="id" type="hidden" class="form-control" name="id" value='{{$dadosDb[0]->id}}'>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Alterar</button>
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
                @else
                <div class="card-body">
                    <form method="POST" id="idForm" action="/cadastrarUsuario" data-toggle="validator" role="form">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar Senha</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
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
                            <label for="numeroDocumentoLabel" class="col-md-4 col-form-label text-md-right">Número do Documento</label>

                            <div class="col-md-6">
                                <input id="numeroDoc" type="text" class="form-control{{ $errors->has('numeroDoc') ? ' is-invalid' : '' }}" name="numeroDoc" value="{{ old('numeroDoc') }}" required>
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>

                            @if ($errors->has('numeroDoc'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('numeroDoc') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="tipoDocumentoLabel" class="col-md-4 col-form-label text-md-right">Grupo de Usuário</label>

                            <div class="col-md-6">
                                <select id="idGrupo" class="form-control" name="idGrupo">
                                    <option value="1">Administrador</option>
                                    <option value="3">Usuário</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
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
                @endif
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

        var $seuCampoNome = $("#name");
        $seuCampoNome.mask('Z',{translation: {'Z': {pattern: /[a-zA-ZáàâãéèêíìïóòôõöúçñÁÀÂÃÉÈÊÍÌÏÓÒÔÕÖÚÇÑ ]/, recursive: true}}});

        <?php  
            if(isset($dadosDb)){
                
        ?>

            $("#tipoDoc").val($('option:contains({{$dadosDb[0]->tipoDoc}})').val());
            
            if('{{$dadosDb[0]->tipoDoc}}' == "carteiraTrabalho"){
                $("#tipoDoc").val($('option:contains(Carteira de Trabalho)').val());
            }

            $("#idGrupo").val($('option:contains({{$dadosDb[0]->nome}})').val());
        
        <?php
            }
        ?>

    </script>
@endsection
