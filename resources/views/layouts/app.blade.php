<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portaria SEMFA</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


</head>
<body>
    @guest

    @else
        <?php 
            $dados = Auth::user()->where('id', '=', Auth::user()->id)->join('grupousuario', 'users.idGrupo', '=', 'grupousuario.idGrupo')->get(); 
            
            foreach ($dados as $dado){
                $nomeExibicao = $dado->nome;
            }
        ?>
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="background: lightblue; padding: 0px">
            <div class="container">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-top: 0px; padding-bottom: 0px">
                            <b>{{ Auth::user()->name }}</b> <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    @endguest
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="/home">Portaria SEMFA</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @else
                            <li><a class="nav-link" href="/home">Home</a></li>
                            <li><a class="nav-link" href="/cadastroVisitante">Cadastrar Visitante</a></li>
                            <?php  
                                if($nomeExibicao == "Administrador"){
                            ?>
                            <li class="nav-item dropdown">
                                <a id="pesquisarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Listagens <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/pesquisarLocal">Setor</a>
                                    <a class="dropdown-item" href="/pesquisarVisitantes">Visitante</a>
                                    <a class="dropdown-item" href="/pesquisarVisitas">Visitas</a>
                                    <a class="dropdown-item" href="/pesquisarUsuarios">Usuário</a>
                                    <a class="dropdown-item" href="/carregarTodasVisitasAtivas">Visitas Ativas</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="cadastrarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Cadastrar <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/cadastroLocal">Setor</a>
                                    <a class="dropdown-item" href="{{ route('register') }}">Usuário</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="relatoriosDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Relatórios <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/relatorioVisitantesSEMFA">
                                        Listagem de Visitantes
                                    </a>
                                    <a class="dropdown-item" href="/relatorioVisitantesPorSetor">
                                        Quantitativo por Setor
                                    </a>
                                </div>
                            </li>
                            <?php  
                                } else {

                                
                            ?>

                            <li class="nav-item dropdown">
                                <a id="pesquisarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Listagens <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/pesquisarVisitantes">Visitante</a>
                                    <a class="dropdown-item" href="/pesquisarVisitas">Visitas</a>
                                    <a class="dropdown-item" href="/carregarTodasVisitasAtivas">Visitas Ativas</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="relatoriosDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Relatórios <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/relatorioVisitantesSEMFA">
                                        Listagem de Visitantes
                                    </a>
                                    <a class="dropdown-item" href="/relatorioVisitantesPorSetor">
                                        Quantitativo por Setor
                                    </a>
                                </div>
                            </li>

                            <?php  
                                }
                            ?>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4" id="rodape">
            @yield('content')
        </main>
    </div>

    <footer class="border-top">
        <div class="row justify-content-center">
          <p>Desenvolvido pela equipe do Portal da Transparência - {{date('Y')}} - v-1.3.2</p>
        </div>
    </footer>

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

        var $seuCampoNumeroCracha = $("#numeroCracha");
        $seuCampoNumeroCracha.mask('00');

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
</html>
