@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Support System</h1>
    
    <div class="mt-5">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-lg">Open New Ticket</a>
    </div>

    <div class="mt-5 row justify-content-center">
        <div class="col-md-6">
            <p class="text-muted">Check the status of your ticket:</p>
            
            <form action="{{ route('tickets.search') }}" method="GET" class="input-group mb-3 shadow-sm">
                <input type="text" 
                       name="reference" 
                       class="form-control form-control-lg" 
                       placeholder="Enter ticket reference" 
                       required>
                <div class="input-group-append">
                    <button class="btn btn-success px-4" type="submit">View Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection