<!DOCTYPE html>
<html>
    
@include('shared.head')

    <body class="bg-[#1C2C3C] text-[#BABABA]">
        <x-header />

        <div class="container mx-auto flex px-5 py-24 items-center justify-center flex-col">
        @yield('content')
        </div>
    </body>
</html>
