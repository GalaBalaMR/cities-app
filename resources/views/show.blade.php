@extends('welcome')
@section('main')
<div class="show">
    <div class="container">
        <h1 class="text-center">Detail obce</h1>
        <div class="row flex-column flex-md-row">
            <div class="info col-12 col-md-6">
                <p>Meno starostu: <span>{{ $city->Mayor_name }}</span></p>
                <p>Adresa obecného úradu:<span> {{ $city->City_hall_address }}</span></p>
                <p>Telefón:<span> {{ $city->Phone }}</span></p>
                <p>Fax:<span> {{ $city->Fax }}</span></p>
                <p>Email:<span> {{ $city->E_mail }}</span></p>
                <p>Web:<span> {{ $city->Web_address }}</span></p>
                <p>Zemepisné súradnice:<span> {{ $city->latitude }},  {{ $city->longitude }}</span></p>
            </div>
            <div class="erb col-12 col-md-6 d-flex flex-column justify-content-center align-items-center">
                <img class="" src="{{ Storage::url($city->Image) }}" alt="">
                <p class="text-center">{{ $city->name }}</p>
            </div>
        </div>
        
    </div>
</div>
    
@endsection