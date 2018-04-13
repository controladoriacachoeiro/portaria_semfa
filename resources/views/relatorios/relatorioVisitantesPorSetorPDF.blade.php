<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">        
    <!-- jQuery -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/estilo-pdf.css') }}" />
    
    <title>Relatório de Visitantes por Setor</title>
</head>

<body>
    <div id="app">
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
            <h3 style="text-align: center">Período: {{ $dataInicial }} - {{$dataFinal}}</h3>

            <?php 
                if (isset($dadosDb)){ 
            ?>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <table id="tabela" class='table table-bordered'>
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
                    </div>
                    <ul>
                        <?PHP 
                            if(count($dadosDb) == 1){
                                echo "<li style='list-style-type: none; padding: 10px'> <p style='text-align: right; font-size:18px'><strong>Total: " . $total . " Visita</strong></p></li>";
                            } else {
                                echo "<li style='list-style-type: none; padding: 10px;'> <p style='text-align: right; font-size:18px'><strong>Total de Visitas: " . $total . " </strong></p></li>";
                                echo "<li style='text-align: right; list-style-type: none; padding: 10px'> <p style='font-size:18px'><strong>Total de Visitantes: " . count($dadosDb2) . "</strong></p></li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <?php 
                } 
            ?>
        </div>

        @endguest
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>

    <script src="{{ asset('js/validator.min.js') }}"></script>

    <!-- Máscara do cpf -->
    <script>

        $(document).ready(function () { 
            if($('#tipoDoc').val() == "CPF"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('000.000.000-00');
            } else if($('#tipoDoc').val() == "RG"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('0000000000');
            } else if ($('#tipoDoc').val() == "carteiraTrabalho"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('0000000000000000');
            }
        });

        $("#tipoDoc").change(function() {
            if($('#tipoDoc').val() == "CPF"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('000.000.000-00');
            }

            if ($('#tipoDoc').val() == "RG"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('0000000000');
            } 

            if ($('#tipoDoc').val() == "carteiraTrabalho"){
                var $seuCampoCpf = $("#numeroDoc");
                $seuCampoCpf.mask('0000000000000000');
            }
        });

        var $seuCampoDataHora = $("#dataHora");
        $seuCampoDataHora.mask('00/00/0000 00:00');

        var $seuCampoDataHoraSaida = $("#dataHoraSaida");
        $seuCampoDataHoraSaida.mask('00/00/0000 00:00');

        var $seuCampoTelefone = $("#telefone");
        $seuCampoTelefone.mask('00000000');

        $("#rodape").css("min-height", $(document).height()*0.8);

        $(function(){
            $('#idForm').submit(function(){
                $("button[type='submit']", this).html("Aguarde...").attr('disabled', 'disabled');
                return true;
            });
        });
        
    </script>
        
    @yield('scriptAdd')
</body>

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