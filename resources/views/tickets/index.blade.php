@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h1>All Tickets</h1>
        <p class="text-muted">Manage and respond to customer inquiries.</p>
    </div>

    <!-- START: Search and Sort Form -->
    <div class="my-4">
        <form action="{{ route('tickets.index') }}" method="get">
            <div class="row">
                <div class="col-3">
                    <select class="form-control" name="sort">
                        <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Customer Name</option>
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Opened Time</option>
                        <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Last Updated Time</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-control" name="sort_dir">
                        <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Reference, customer name or phone number">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
    </div>
    <!-- END: Search and Sort Form -->

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
                            {{-- Uses 'ref' for secure SHA1 routing as seen in your Controller --}}
                            <a href="{{ route('tickets.show', $ticket->ref) }}" class="font-weight-bold text-primary">
                                {{ $ticket->customer_name }}
                            </a>
                        </td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ $ticket->phone ?? 'N/A' }}</td>
                        <td>{{ $ticket->created_at->format('d/M/Y H:i') }}</td>
                        <td>
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

        {{-- Pagination Links: .appends() in controller ensures these work with filters --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>

        @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> No tickets found matching your criteria.
        </div>
        @endif
    </div>
</div>
@endsection