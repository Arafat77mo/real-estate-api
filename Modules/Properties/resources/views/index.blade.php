@extends('properties::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('properties.name') !!}</p>
@endsection
