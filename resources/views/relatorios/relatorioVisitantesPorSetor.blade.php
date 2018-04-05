@extends('layouts.app')

@section('content')
    @guest
        <?php  
            echo "<script> window.location.href = 'home';</script>";
        ?>
    @else
        <?php 
            $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 

        ?>
    
    <div class="container">
        <h1 style="text-align: center">Relatório de Visitantes por Setor</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Relatório de Visitantes por Setor</div>

                    <div class="card-body">
                        <form method="POST" action="/carregarRelatorioVisitantesSetor">
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

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Resultado</div>
                    <table id="tabela" class="table table-bordered table-striped" summary="Resultado da pesquisa">
                        <thead>
                            <tr>
                                <th scope='col' style='vertical-align:middle'>Setor</th>
                                <th scope='col' style='vertical-align:middle'>Quantidade de Visitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                                $total = 0;
                                foreach ($dadosDb as $valor) {                    
                                    echo "<tr>";
                                    echo "<td scope='col'>".$valor->nomeLocal."</td>";
                                    echo "<td scope='col'>".$valor->quantidade."</td>";
                                    echo "</tr>";
                                    $total += $valor->quantidade;
                                }
                            ?>
                        </tbody>
                    </table>
                    <ul>
                        <?PHP 
                            if(count($dadosDb) == 1){
                                echo "<li style='list-style-type: none; padding: 10px'> <p style='text-align: right; font-size:18px'><strong>Total: " . $total . " Visita</strong></p></li>";
                            } else {
                                echo "<li style='list-style-type: none; padding: 10px'> <p style='text-align: right; font-size:18px'><strong>Total de Visitas: " . $total . " </strong></p></li>";
                                echo "<li style='text-align: right; list-style-type: none; padding: 10px'> <p style='font-size:18px'><strong>Total de Visitantes: " . count($dadosDb2) . "</strong></p></li>";
                            }
                        ?>
                    </ul>
                </div>
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