@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Support System</h1>
    <div class="mt-5">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">Open New Ticket</a>
    </div>
</div>
@endsection