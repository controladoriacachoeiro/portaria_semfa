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
        <h1 style="text-align: center">Cadastro de Visita</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tela de Cadastro</div>

                    <div class="card-body">
                        <form method="POST" id="idForm" action="/cadastrarVisita" data-toggle="validator" role="form">
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
                                    <input id="nome" type="text" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" name="nome" value= '{{$nome}}' required readonly>

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
                                    <input id="visitado" type="text" class="form-control" name="visitado">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="assuntoLabel" class="col-md-4 col-form-label text-md-right">Assunto</label>

                                <div class="col-md-6">
                                    <input id="assunto" type="text" class="form-control" name="assunto">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="numeroCrachaLabel" class="col-md-4 col-form-label text-md-right">Número do Crachá</label>

                                <div class="col-md-6">
                                    <input id="numeroCracha" type="text" class="form-control" name="numeroCracha">
                                </div>
                            </div>
                            
                            <?php  
                                if ($nomeExibicao == "Administrador"){

                                
                            ?>

                            <div class="form-group row">
                                <label for="dataHora" class="col-md-4 col-form-label text-md-right">Data e Hora</label>

                                <div class="col-md-6">
                                    <input id="dataHora" type="text" class="form-control" name="dataHora" value='{{date('d/m/Y H:i')}}' required>
                                    <div class="help-block with-errors alert-danger" style="font-size: 14px; text-align: center; margin-top: 5px; border-radius: 5px"></div>
                                </div>
                            </div>
                            
                            <?php  
                                } else{

                            ?>

                            <div class="form-group row">
                                <label for="dataHora" class="col-md-4 col-form-label text-md-right">Data e Hora</label>

                                <div class="col-md-6">
                                    <input id="dataHora" type="text" class="form-control" name="dataHora" value='{{date('d/m/Y H:i')}}' readonly>
                                </div>
                            </div>
                            
                            <?php  
                                }
                            ?>

                            <input id="visitanteID" type="hidden" class="form-control" name="visitanteID" value='{{$visitanteID}}'>
                                
                            
                            <input id="userID" type="hidden" class="form-control" name="userID" value='{{Auth::user()->id}}'>
                                

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                                    <button type="button" class="btn btn-primary" style="float: right" id="buttonVoltar">Voltar</button>
                                </div>
                            </div>
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

@endguest

@endsection


@section('scriptAdd')
<script>
    
    // LoadPage
    $(function () {
        $(document).ready(function() {
            var x = <?php echo $dadosDb ?>;
            var nome = '{{$nome}}';
            var visitanteID = {{$visitanteID}};
            var option = "";
            for(var i = 0; i < x.length; i++){
                option = option + "<option value='" + x[i].localID.toString() + "'>"+x[i].nomeLocal+"</option>";                
            }
            $('#localID').html(option).show();
        });
    });

    $("#buttonVoltar").click(function() {
        window.history.go(-1);
    });

</script>
@endsection
