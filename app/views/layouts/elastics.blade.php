<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Cinsay Elastic Search Demo</title>
        <script src="http://cdn.alloyui.com/3.0.0/aui/aui-min.js"></script>
        {{--<link href="http://cdn.alloyui.com/3.0.0/aui-css/css/bootstrap.min.css" rel="stylesheet">--}}
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">

       {{-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">--}}
        <style>
            table form { margin-bottom: 0; }
            form ul { margin-left: 0; list-style: none; }
            .error { color: red; font-style: italic; }
            body { padding-top: 60px; }
            .welcome {
                position: absolute;
                left: 1%;
                top: 1%;
                margin-left: 0px;
                margin-top: 0px;
            }
            .copyright{
                text-shadow:1px 1px white;
                width:90%;
                max-width:988px;
                margin:0 auto;
                text-align:center;
                font-size:13px;
                color:#999;
                font-weight:300;
                margin-bottom:80px;
            }
            .float-buttons {
                position: absolute;
                right: 1%;
                top: 1%;
                margin-left: 0px;
                margin-top: 10px;
            }
        </style>
        <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}" />
</head>

<body>
<div class="welcome">
    <a href="/" title="Cinsay: eCommerce Redefined">
        <img src="{{ asset('image/cinsay_logo.png') }}"/>
    </a>
</div>
<p class="float-buttons">{{-- link_to_route('users.create', 'Add new user') --}}
    {{ link_to_route('cluster.index', 'Clusters', array($ESClient->hosts), array('class' => 'btn btn-info')) }}
    {{ link_to_route('node.index', 'Nodes', array($ESClient->hosts), array('class' => 'btn btn-success')) }}
    {{ link_to_route('indices.index', 'Indices', array($ESClient->hosts), array('class' => 'btn btn-warning')) }}
    {{ link_to_route('search.index', 'Searching', array($ESClient->hosts), array('class' => 'btn btn-primary')) }}
</p>
<div class="container">
    @if (Session::has('message'))
        <div class="flash alert">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif

    @yield('main')
</div>

</body>
<!-- footer-->
<footer>
<div class="copyright">&copy; Cinsay, Inc {{ date('Y') }}. All rights reserved.</div>
</footer>
<!-- end footer-->
</html>