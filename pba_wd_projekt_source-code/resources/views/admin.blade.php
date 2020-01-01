@extends('html')

@section('body')
    @auth
        <main>
            <div id="app">
                
            </div>
        </main>
        <!-- Scripts -->
        <script src="{{ asset('js/adminApp.js') }}" defer></script>
    @endauth

    @guest
    <script>
        window.location.href = "./../login";
    </script>
    @endguest
@endsection
