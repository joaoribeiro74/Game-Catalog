@if (session('success'))
    <div style="color: green">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="color: #E10000">
        {{ session('error') }}
    </div>
@endif