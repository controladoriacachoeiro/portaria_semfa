@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = '/home';</script>";
        ?>
    @else
<div class="container">
    <h1 style="text-align: center">Cadastro de Visitante</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tela de Cadastro</div>

                <div class="card-body">
                    <form method="POST" id="idForm" action="/cadastrarVisitante" enctype="multipart/form-data" data-toggle="validator" role="form">
                        @csrf

                        <div class="form-group row">
                            <label for="nome" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <input id="nome" type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" name="nome" value="{{ old('nome') }}" autofocus placeholder="Digite o nome do visitante." required>
                                <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>

                                @if ($errors->has('nome'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                @endif
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
                                <input id="numeroDoc" type="text" class="form-control" name="numeroDoc" placeholder="Digite o número do documento." value="{{ old('numeroDoc') }}" required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fileLabel" class="col-md-4 col-form-label text-md-right">Foto</label>

                            <div class="col-md-6">
                                <input type="file" name="file[]" id="file">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fileLabel" class="col-md-4 col-form-label text-md-right">Cadastrar Visita agora?</label>

                            <div class="col-md-6">
                                <input type="checkbox" name="checkboxVisita" id="checkboxVisita"> Sim
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
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
</div>

@endguest

@endsection

@section('scriptAdd')

    <script>
        $("#buttonVoltar").click(function() {
            window.history.go(-1);
        }); 

        var $seuCampoNome = $("#nome");
        $seuCampoNome.mask('Z',{translation: {'Z': {pattern: /[a-zA-ZáàâãéèêíìïóòôõöúçñÁÀÂÃÉÈÊÍÌÏÓÒÔÕÖÚÇÑ ]/, recursive: true}}});


    </script>

@endsection
