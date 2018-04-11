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
        <h1 style="text-align: center">Listagem de Visitas Ativas</h1>
        
        <?php 
            if (isset($dadosDb)){
                if(!$dadosDb->isEmpty()){
        ?>
        
        <div class="row justify-content-center">
            <div class="col-md-14">
                <div class="card">
                    <div class="card-header">Listagem de Visitas Ativas</div>
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
                                    echo "<td scope='col'> <img src='/abrir/". $valor->urlFoto ."' id='fotoVisitante' name='fotoVisitante' width='50px' height='50px' data-toggle='modal' data-target='#myModal". $aux ."'> </td>";
                                    echo "<td scope='col'><a href='". route('verPerfilVisitante', ['visitanteID' => $valor->visitanteID]) ."'>". $valor->nomeVisitante ."</a></td>"; 
                                    echo "<td scope='col'>".$tipoDocumento."</td>";
                                    echo "<td scope='col'>".$valor->numeroDoc."</td>";
                                    echo "<td scope='col'>".$valor->dataHora."</td>";
                                    echo "<td scope='col'>".$valor->numeroCracha."</td>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalConfirmarSaida" . $aux . "'>Registrar Saída</button></td>";
                                    
                                    if($nomeExibicao == "Administrador"){
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

                                    //Modal Confirmar Registro de Saída
                                    echo "<div id='modalConfirmarSaida" . $aux . "' class='modal fade' role='dialog'>";
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
                                
                                    $aux++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <span style="float: right; margin-top: 5px">{{$dadosDb->links()}}</span>
            </div>
        </div>
        <br>
        <?php 
                } else{
                    echo "<div class='form-group row mb-0 alert alert-danger' style='font-size:20px'>Nenhuma Visita Ativa no momento </div>";
                }
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