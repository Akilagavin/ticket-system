@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="font-weight-bold">All Tickets</h1>
        <p class="text-muted">Manage and respond to customer inquiries.</p>
    </div>

    <!-- START: Search and Sort Form - Styled to match reference image -->
    <div class="my-4">
        <form action="{{ route('tickets.index') }}" method="get">
            <div class="form-row align-items-center">
                <div class="col-md-3 mb-3">
                    <select class="form-control" name="sort">
                        <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Customer Name</option>
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Opened Time</option>
                        <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Last Updated Time</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <select class="form-control" name="sort_dir">
                        <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-5 mb-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Reference, customer name or phone number">
                </div>
                <div class="col-md-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold">Search</button>
                </div>
            </div>
        </form>
    </div>
    <!-- END: Search and Sort Form -->

    <div class="mt-2">
        @if($tickets->isNotEmpty())
        <div class="table-responsive shadow-sm">
            <table class="table table-hover table-bordered bg-white">
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
                            <a href="{{ route('tickets.show', $ticket->ref) }}" class="font-weight-bold text-info text-decoration-none">
                                {{ $ticket->customer_name }}
                            </a>
                        </td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ $ticket->phone ?? 'N/A' }}</td>
                        <td>{{ $ticket->created_at->format('d/M/Y H:i') }}</td>
                        <td>
                            @if($ticket->status == 0)
                                <span class="badge badge-primary px-3 py-2">Open</span>
                            @elseif($ticket->status == 1)
                                <span class="badge badge-warning px-3 py-2">Pending</span>
                            @else
                                <span class="badge badge-success px-3 py-2">Resolved</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('tickets.show', $ticket->ref) }}" class="btn btn-sm btn-outline-info px-3">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>

        @else
        <div class="alert alert-light border text-center shadow-sm py-5">
            <h4 class="text-muted">No tickets found</h4>
            <p class="mb-0">Try adjusting your search criteria or sorting options.</p>
        </div>
        @endif
    </div>
</div>
@endsection