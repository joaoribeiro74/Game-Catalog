<!DOCTYPE html>
<html>
    
@include('shared.head')

    <body class="bg-gradient-to-r from-[#100C1C] to-[#1C6CA4] min-h-screen text-[#BABABA] flex flex-col">
        <x-header />

        <main class="flex-1 container max-w-[940px] mx-auto pt-10">
        @yield('content')
        </main>

        <x-footer />
    </body>
</html>
