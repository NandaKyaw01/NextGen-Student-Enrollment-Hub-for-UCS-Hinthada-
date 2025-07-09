@extends('UI.master')
@section('content')
    <div class="container">
        <div style="height: 50px"></div>
        <div class="row justify-content-center">
            <div class="col-1 ">
                <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm mb-2">Back</a>
            </div>
            @php
                use App\Helper\Facades\File;
            @endphp
            <div class="col-11 mb-5">
                <img src="{{ asset(File::GetStudentDataPath() . '/' . $image) }}" class="img-fluid" style="height: 500px;width:800px">
            </div>
        </div>
    </div>
@endsection
