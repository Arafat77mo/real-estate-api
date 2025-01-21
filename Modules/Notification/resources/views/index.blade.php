@extends('notification::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('notification.name') !!}</p>
@endsection
