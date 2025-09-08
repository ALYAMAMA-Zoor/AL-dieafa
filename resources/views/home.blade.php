@extends('layouts.footer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card"style="margin-top:170px; margin-bottom:150px ;background-color:#7A9F5C;color:white;align-items:center;font-size:30px">
                <div class="card-body" styel="">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
