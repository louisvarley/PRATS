<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Local Style Sheets -->
	<link rel="stylesheet" href="/css/colorpicker/jquery.minicolors.css">
	<link rel="stylesheet" href="/css/style.css">

	<!-- Other Style Sheets and Fonts -->
	<link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet" />	
	
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />	
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
		
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500&display=swap" rel="stylesheet"> 
		
	<!-- Datatables -->
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">	

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

	<!-- Bootstrap -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
	
	<!-- SS Menu -->
	<script src="/js/jquery.ss.menu.js"></script>

	<!-- Chart JS -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	
	<!-- Select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	
	<!-- GoJS -->
	<script src="/js/gojs/release/go.js"></script>
	<script src="/js/gojs/release/ZoomSlider.js"></script>	

	<!-- Moment JS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
	
	<!-- Datatables -->
	<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>		
	<script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
	
	<!-- jQuery Plugins -->
	<script src="/js/jquery.ajax.fileupload.js"></script>
	<script src="/js/jquery.toolbar.min.js"></script>	
	<script src="/js/jquery.minicolors.min.js"></script>
	<script src="/js/jquery.password.strength.min.js"></script>
	
	<!-- Local Libs -->
	<script src="/js/rs.base.js"></script>	
	<script src="/js/rs.forms.js"></script>	
	<script src="/js/rs.toasts.js"></script>	
	<script src="/js/rs.datatables.js"></script>	
	<script src="/js/rs.ajax.js"></script>	
	<script src="/js/rs.familytree.js"></script>		
	<script src="/js/rs.logic.js"></script>			
		
	<style>
	
	.chart { height: 100%; width: 100%; margin: 5px; margin: 15px auto; border: 3px solid #DDD; border-radius: 3px; }

.evolution-tree {
    padding: 2px;
    width: 40px; height: 40px;
    border-radius: 3px;
    font-size: 10px;
}

.evolution-tree .node-name { text-align: center; position: absolute; width: 88px; left: -50%; }
.evolution-tree img {  margin-right:  10px; float: none !important; }

.evolution-tree.the-parent { border-radius: 50%; background-color: #000; width: 3px; height: 3px; }
.evolution-tree.the-parent .node-name { width: auto; margin-top: -7px; text-indent: 12px; font-weight: bold; }

</style>



</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
