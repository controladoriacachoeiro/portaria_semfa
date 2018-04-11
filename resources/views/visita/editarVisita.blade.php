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

            if ($nomeExibicao == "Administrador" || $nomeExibicao == "Moderador"){
        ?>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Informações sobre a Visita</div>

                    <div class="card-body">
                        <form method="POST" id="idForm" action="/editarVisita" data-toggle="validator" role="form">
                            @csrf

                                <div class="form-group row">
                                    <label for="fotoLabel" class="col-md-4 col-form-label text-md-right"></label>

                                    <div class="col-md-6">
                                        <img src='/abrir/{{$dadosDb2[0]->urlFoto}}' id="fotoVisitante" name="fotoVisitante" width="100px" height="100px" data-toggle="modal" data-target="#myModal">
                                    </div>
                                </div>

                            <div class="form-group row">
                                <label for="nome" class="col-md-4 col-form-label text-md-right">Nome</label>

                                <div class="col-md-6">
                                    <input id="nome" type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" name="nome" value= '{{$dadosDb2[0]->nomeVisitante}}' readonly>

                                    @if ($errors->has('nome'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="localLabel" class="col-md-4 col-form-label text-md-right">Local</label>

                                <div class="col-md-6">
                                    <select id="localID" class="form-control" name="localID">
                                    
                                    </select>
      
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="visitadoLabel" class="col-md-4 col-form-label text-md-right">Visitado</label>

                                <div class="col-md-6">
                                    <input id="visitado" type="text" class="form-control" name="visitado" value="{{$dadosDb[0]->visitado}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="assuntoLabel" class="col-md-4 col-form-label text-md-right">Assunto</label>

                                <div class="col-md-6">
                                    <input id="assunto" type="text" class="form-control" name="assunto" value="{{$dadosDb[0]->assunto}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dataHora" class="col-md-4 col-form-label text-md-right">Data e Hora Entrada</label>

                                <div class="col-md-6">
                                    <input id="dataHora" type="text" class="form-control" name="dataHora" value="{{$dadosDb[0]->dataHora}}" required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dataHoraSaida" class="col-md-4 col-form-label text-md-right">Data e Hora Saída</label>

                                <div class="col-md-6">
                                    <input id="dataHoraSaida" type="text" class="form-control" name="dataHoraSaida" value="{{$dadosDb[0]->dataHoraSaida == null ? date('d-m-Y H:i') : $dadosDb[0]->dataHoraSaida}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="numeroCrachaLabel" class="col-md-4 col-form-label text-md-right">Número do Crachá</label>

                                <div class="col-md-6">
                                    <input id="numeroCracha" type="text" class="form-control" name="numeroCracha" value="{{$dadosDb[0]->numeroCracha}}">
                                </div>
                            </div>

                            <input id="grupoUsuario" type="hidden" class="form-control" name="grupoUsuario" value='{{$nomeExibicao}}'>
                            
                            <input id="visitaID" type="hidden" class="form-control" name="visitaID" value='{{$dadosDb[0]->visitaID}}'>

                            <input id="visitanteID" type="hidden" class="form-control" name="visitanteID" value='{{$dadosDb[0]->visitanteID}}'>
                                
                            
                            <input id="userID" type="hidden" class="form-control" name="userID" value='{{Auth::user()->id}}'>
                                

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
                                <div class="form-group row mb-0 alert alert-success" style="font-size:16px">
                                    {{ session()->get('sucesso') }}
                                </div>
                            @endif
                            <!-- Fim sucesso -->

                        </form>
                    </div>

                    <div class='modal fade' id='myModal' role='dialog'>
                        <div class='modal-dialog row justify-content-center'>
                            <img src='/abrir/{{$dadosDb2[0]->urlFoto}}' width='400px' height='400px'>
                        </div>
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
    
    // LoadPage
    $(function () {
        $(document).ready(function() {
            var x = <?php echo $dadosDb3 ?>;
            
            var option = "";
            for(var i = 0; i < x.length; i++){
                option = option + "<option value='" + x[i].localID.toString() + "'>"+x[i].nomeLocal+"</option>";                
            }
            $('#localID').html(option).show();
            $("#localID").val($('option:contains({{$dadosDb[0]->nomeLocal}})').val());
        });
    });

    $("#buttonVoltar").click(function() {
        window.history.go(-1);
    });

</script>
@endsection
