@php
    /* Se agrego para actualizar los permisos */
    //PermissionHelper::load();
@endphp
<!DOCTYPE html>
<html lang="en">

@include('Partials.Head')

<body>
    @include('Partials.Header')
  <!-- ======= Sidebar ======= -->
  @include('Partials.Sidebar')

  <main id="main" class="main">

    @yield('page-title')

    <section class="section dashboard">
      @yield('content')
    </section>

  </main><!-- End #main -->

  @include('Partials.Footer')

</body>

</html>