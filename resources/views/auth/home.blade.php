@extends('layout.app')

@section('title', 'Home')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Welcome to MyProject</h1>
            <p class="card-text">This is your home page. Add content here to get started.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Go to main site</a>
        </div>
    </div>
</div>
@endsection