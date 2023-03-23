@extends('welcome')
@section('main')
    <div class="home">
        <div class="container">
            <h1 class="text-center">Vyhľadať v databáze obcí</h1>
            <div class="row justify-content-center">
                <div class="col-5">
                    <input type="text" id="search" class="typeahead form-control p-3" placeholder="Zadajte názov">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        var path = "{{ route('search') }}";
        
        $('#search').typeahead({
            source: function(query, process) {
                return $.get(path, {
                    query: query
                }, function(data) {
                    return process(data);
                });
            },
            afterSelect: function(data) {
                console.log(data.id);
                window.location.href = "/city/"+[data.id];
            }
        });
        
        
        </script>
@endsection
