@extends('transactions::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('transactions.name') !!}</p>
@endsection
