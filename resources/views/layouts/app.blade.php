<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Naive Bayes</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{url('assets/images/favicon.ico')}}">
    <!-- DataTables -->
    <link href="{{url('assets/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('assets/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{url('assets/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />


    <link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('assets/css/icons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{url('assets/css/style.css')}}" rel="stylesheet" type="text/css">
</head>

<body>

<!-- Begin page -->
<div id="wrapper">

    @include('layouts.header')

    @include('layouts.sidebar')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        @yield('content')
        <!-- content -->

        @include('layouts.footer')

    </div>

    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->

</div>
<!-- END wrapper -->

<!-- jQuery  -->
<script src="{{url('assets/js/jquery.min.js')}}"></script>
<script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('assets/js/metisMenu.min.js')}}"></script>
<script src="{{url('assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('assets/js/waves.min.js')}}"></script>

<!-- Required datatable js -->
<script src="{{url('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('assets/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- peity JS -->
<script src="{{url('assets/plugins/peity-chart/jquery.peity.min.js')}}"></script>

<!-- Datatable init js -->
<script src="{{url('assets/pages/datatables.init.js')}}"></script>
<!-- App js -->
<script src="{{url('assets/js/app.js')}}"></script>

</body>

</html>
