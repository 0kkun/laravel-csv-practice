@extends('layouts.layout')

@section('content')
    @if (session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="container-fluid">
        CSV Practice Page
    </div>
    <form action="{{ route('csv.download') }}" method="post">
        {{ csrf_field()}}
        <button type="submit" class="btn btn-success p-1 m-2">CSV DOWNLOAD</button>
    </form>
@endsection