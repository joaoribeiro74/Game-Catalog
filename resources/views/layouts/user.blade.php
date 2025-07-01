<!DOCTYPE html>
<html>
    
@include('shared.head')

    <body class="bg-[#1C2C3C] text-[#BABABA]">
        <x-header />

        <h1>@yield('title')</h1>

        <x-flash />

        @yield('content')
    </body>
</html>
