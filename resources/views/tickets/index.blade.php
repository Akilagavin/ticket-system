@extends('layouts.app')

@section('content')
<div class="container-fluid px-5 mt-5">
    <div class="text-center mb-5">
        <h1 class="display-4 font-weight-normal">All Tickets</h1>
    </div>

    <!-- Search and Sort Form -->
    <div class="mb-4">
        <form action="{{ route('tickets.index') }}" method="get">
            <div class="form-row">
                <div class="col-md-3">
                    <select class="form-control form-control-lg text-muted" name="sort" style="font-size: 0.9rem;">
                        <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Customer Name</option>
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Opened Time</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control form-control-lg text-muted" name="sort_dir" style="font-size: 0.9rem;">
                        <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-lg text-muted" placeholder="Reference, customer name or phone number" style="font-size: 0.9rem;">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold" style="font-size: 0.9rem;">Search</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white" style="font-size: 0.85rem;">
            <thead class="bg-white">
                <tr class="text-center font-weight-bold">
                    <th style="width: 12%">Customer</th>
                    <th style="width: 20%">Email</th>
                    <th style="width: 15%">Phone</th>
                    <th style="width: 20%">Opened Time</th>
                    <th style="width: 13%">Handled By</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr class="text-center">
                    <td>
                        <a href="{{ route('tickets.show', $ticket->ref) }}" class="text-primary text-decoration-none">
                            {{ $ticket->customer_name }}
                        </a>
                    </td>
                    <td>{{ $ticket->email }}</td>
                    <td>{{ $ticket->phone ?? 'N/A' }}</td>
                    <td>{{ $ticket->created_at->format('d/M/Y H:i:s') }}</td>
                    <td>{{-- Add logic for handled_by if available --}}</td>
                    <td>
                        @if($ticket->status == 0) Open @elseif($ticket->status == 1) Pending @else Resolved @endif
                    </td>
                    <td>
                        {{-- Action buttons --}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No tickets found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>
@endsection