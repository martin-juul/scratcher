@extends('layouts.app')

@section('styles')
    <style>
        html, body {
            background-color: #1b1e21;
        }

        h1 {
            color: #ccc;
            font-size: 2rem;
            font-family: -apple-system, -system-ui, sans-serif;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <h1>{{ config('app.name') }}</h1>
@endsection
