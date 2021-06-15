    @include('layouts.include.header')
    <div id="wrapper">
    @include('layouts.include.left_menu')

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
    @include('layouts.include.top_bar')
        </div>
    <div class="wrapper wrapper-content">
    @yield('main-content')
     </div>    
    
    @include('layouts.include.footer')