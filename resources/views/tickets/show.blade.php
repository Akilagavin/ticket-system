@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h1 class="display-4">Support Ticket</h1>
        <p class="text-muted">Below are the details for your support request.</p>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 1. Ticket Details Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ticket Information</h5>
                    <span class="badge badge-light p-2">Ref: {{ $ticket->ref }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%" class="bg-light">Customer Name</th>
                                <td>{{ $ticket->customer_name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Status</th>
                                <td>
                                    @if($ticket->status == 0)
                                        <span class="badge badge-info">Open</span>
                                    @elseif($ticket->status == 1)
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-success">Closed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Description</th>
                                <td class="lead">{{ $ticket->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. Add a Reply Form --}}
            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Add a Reply</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        
                        {{-- Hidden field to link comment to this ticket --}}
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        
                        <div class="form-group">
                            <label for="content" class="font-weight-bold">Your Response:</label>
                            <textarea 
                                name="content" 
                                id="content" 
                                class="form-control @error('content') is-invalid @enderror" 
                                rows="4" 
                                required 
                                placeholder="Write your update here..."></textarea>
                            
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-paper-plane mr-1"></i> Post Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="text-center mb-5">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Return to Home</a>
                <button onclick="window.print()" class="btn btn-primary ml-2">Print Ticket</button>
            </div>

        </div>
    </div>
</div>
@endsection