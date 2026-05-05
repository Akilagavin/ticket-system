@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Ticket Status Card --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Update Ticket Status: {{ $ticket->ref }}</h4>
                </div>
                <div class="card-body">
                    <h5>Customer: {{ $ticket->customer_name }}</h5>
                    <p class="text-muted">{{ $ticket->description }}</p>
                    <hr>

                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label for="status" class="form-label font-weight-bold">Set New Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="0" {{ $ticket->status == 0 ? 'selected' : '' }}>Open</option>
                                <option value="1" {{ $ticket->status == 1 ? 'selected' : '' }}>In Progress</option>
                                <option value="2" {{ $ticket->status == 2 ? 'selected' : '' }}>Resolved</option>
                                <option value="3" {{ $ticket->status == 3 ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <small class="form-text text-info">
                                Note: Resolving or Cancelling a ticket will lock it from further edits.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Back to List</a>
                            <button type="submit" class="btn btn-success px-4">Update Ticket Status</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Comments/Replies Section --}}
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Ticket Conversation</h5>
                </div>
                <div class="card-body">
                    {{-- Display Existing Comments --}}
                    <div class="comment-list mb-4">
                        @forelse($ticket->comments as $comment)
                            <div class="p-3 mb-2 rounded {{ $comment->user_id ? 'bg-light border-left border-primary' : 'bg-white border' }}">
                                <strong>{{ $comment->user_id ? 'Agent (' . $comment->user->name . ')' : 'Customer' }}</strong>
                                <small class="text-muted float-right">{{ $comment->created_at->diffForHumans() }}</small>
                                <p class="mb-0 mt-2">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-center text-muted">No replies yet.</p>
                        @endforelse
                    </div>

                    <hr>

                    {{-- Add New Comment Form --}}
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        
                        <div class="form-group">
                            <label for="content" class="font-weight-bold">Add a Reply</label>
                            <textarea name="content" id="content" rows="3" class="form-control @error('content') is-invalid @enderror" placeholder="Type your message here..." required></textarea>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary">Post Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection