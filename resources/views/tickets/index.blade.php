@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h1>All Tickets</h1>
        <p class="text-muted">Manage and respond to customer inquiries.</p>
    </div>

    <div class="mt-4">
        @if($tickets->isNotEmpty())
        <div class="table-responsive shadow-sm">
            <table class="table table-striped table-bordered bg-white">
                <thead class="thead-dark">
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Opened Time</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>
                            {{-- Updated to use 'ref' for your SHA1 logic --}}
                            <a href="{{ route('tickets.show', $ticket->ref) }}" class="font-weight-bold">
                                {{ $ticket->customer_name }}
                            </a>
                        </td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ $ticket->phone ?? 'N/A' }}</td>
                        <td>{{ $ticket->created_at->format('d/M/Y H:i') }}</td>
                        <td>
                            {{-- Visual Status Indicator --}}
                            @if($ticket->status == 0)
                                <span class="badge badge-primary">Open</span>
                            @elseif($ticket->status == 1)
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-success">Resolved</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('tickets.show', $ticket->ref) }}" class="btn btn-sm btn-outline-info">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>

        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> No tickets found in the system yet.
        </div>
        @endif
    </div>
</div>
@endsection