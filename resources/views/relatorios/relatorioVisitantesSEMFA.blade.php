@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = '/home';</script>";
        ?>
    @else
        <?php 
            $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 

        ?>

    <div class="container">
        <h1 style="text-align: center">Relatório de Visitantes da SEMFA</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Relatório de Visitantes da SEMFA</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="/carregarRelatorioVisitantesSEMFA">
                            @csrf

                            <div class="form-group row">
                                <label for="dataInicialLabel" class="col-md-4 col-form-label text-md-right">Data Inicial</label>

                                <div class="col-md-6">
                                    <input id="dataInicial" type="date" class="form-control" name="dataInicial">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dataFinalLabel" class="col-md-4 col-form-label text-md-right">Data Final</label>

                                <div class="col-md-6">
                                    <input id="dataFinal" type="date" class="form-control" name="dataFinal">
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Procurar</button>
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

        <br>
        <?php 
            if (isset($dadosDb)){ 
        ?>
        
        <div class="row justify-content-center resp">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Resultado</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Foto</th>
                                <th scope='col' style='vertical-align:middle'>Nome do Visitante</th>
                                <th scope='col' style='vertical-align:middle'>Tipo de Documento</th>
                                <th scope='col' style='vertical-align:middle'>Número do Documento</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Visita</th>
                                <th scope='col' style='vertical-align:middle'>Data e Hora da Saída</th>
                                <th scope='col' style='vertical-align:middle'>Duração da Visita</th>
                                <th scope='col' style='vertical-align:middle'>Local</th>
                                <th scope='col' style='vertical-align:middle'>Pessoa que Visitou</th>
                                <th scope='col' style='vertical-align:middle'>Assunto</th>
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
                                    echo "<td scope='col'>".$valor->dataHora."</td>";
                                    echo "<td scope='col'>".$valor->dataHoraSaida."</td>";
                                    echo "<td scope='col'>".$valor->tempoGasto[0]."</td>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'>".$valor->visitado."</td>";
                                    echo "<td scope='col'>".$valor->assunto."</td>";
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
                    <ul>
                        <?PHP 
                            if(count($dadosDb3) == 1){
                                echo "<li style='list-style-type: none; padding: 10px'> <p style='text-align: right; font-size:18px'><strong>Total: " . count($dadosDb3) . " Visita</strong></p></li>";
                            } else {
                                echo "<li style='list-style-type: none; padding: 10px'> <p style='text-align: right; font-size:18px'><strong>Total de Visitas: " . count($dadosDb3) . "</strong></p></li>";
                                echo "<li style='text-align: right; list-style-type: none; padding: 10px'> <p style='font-size:18px'><strong>Total de Visitantes: " . count($dadosDb2) ."</strong></p></li>";
                            }

                            if($tempoMedio < 60){
                                echo "<li style='text-align: right; list-style-type: none; padding: 10px'> <p style='font-size:18px'><strong>Duração média das visitas: " . $tempoMedio . " min</strong></p></li>";
                            } else{
                                $tempoMedioHora = $tempoMedio/60;
                                $tempoMedioHora = (int)$tempoMedioHora;
                                $tempoMedioMinuto = $tempoMedio % 60;

                                echo "<li style='text-align: right; list-style-type: none; padding: 10px'> <p style='font-size:18px'><strong>Duração média das visitas: " . $tempoMedioHora . " h e ". $tempoMedioMinuto . " min" ."</strong></p></li>";   
                            }
                        ?>
                    </ul>
                </div>
                <span style="float: right; margin-top: 5px">{{$dadosDb->links()}}</span>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="col-md-8 alert alert-danger" style="font-size:16px">
                {{ session()->get('message') }}
            </div>
        @endif

        <?php 
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