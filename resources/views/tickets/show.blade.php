@extends('layouts.app')

@section('content')
<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Ticket Details</h3>
        <span class="badge badge-info">Ref: {{ $ticket->ref }}</span>
    </div>
    <div class="card-body">
        <h5>Customer: {{ $ticket->customer_name }}</h5>
        <p><strong>Status:</strong> New</p>
        <hr>
        <p class="lead">{{ $ticket->description }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary">Return to Home</a>
    </div>
</div>
@endsection
