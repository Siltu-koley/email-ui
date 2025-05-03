<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable"
    data-theme="default" data-topbar="light" data-bs-theme="light">

@include('layout/header')

<body>


@include('layout/topbar')
@include('layout/footer')
@yield('content')


</body>

</html>