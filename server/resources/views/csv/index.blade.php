@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        CSV Practice Page
    </div>
    <div class="btn btn-success p-1 m-2"><a href="{{ route('csv.export') }}">CSV DOWNLOAD</a></div>
@endsection