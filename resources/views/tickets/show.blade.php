@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1 class="display-4">Support Ticket</h1>
    <p class="text-muted">Below are the details for your support request.</p>

    <div class="m-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-left">
                
                {{-- Ticket Information Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ticket Information</h5>
                        <span class="badge badge-light p-2">Ref: {{ $ticket->ref }}</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 30%" class="bg-light">Ticket Reference</th>
                                    <td class="text-primary font-weight-bold">{{ $ticket->ref }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Customer Name</th>
                                    <td>{{ $ticket->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Email Address</th>
                                    <td>{{ $ticket->email }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Phone Number</th>
                                    <td>{{ $ticket->phone ?? 'Not provided' }}</td>
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
                    <div class="card-footer bg-white text-right">
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">Return to Home</a>
                        <button onclick="window.print()" class="btn btn-primary ml-2">Print Ticket</button>
                    </div>
                </div>

                {{-- The Comment Box --}}
                <div class="comments-container mt-4">
                    <div class="comment-editor card shadow-sm p-4 border-0">
                        <h5 class="mb-3">Add a Reply</h5>
                        <form action="{{ route('comments.store') }}" method="post">
                            @csrf
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <div class="form-group mb-3">
                                <textarea name="content" rows="4" class="form-control" placeholder="Type your reply here..." required></textarea>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success px-4">Post Reply</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div> {{-- Closes col-lg-8 --}}
        </div> {{-- Closes row --}}
    </div> {{-- Closes m-5 --}}
</div> {{-- Closes text-center --}}
@endsection