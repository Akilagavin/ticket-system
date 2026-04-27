@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Open New Ticket</h1>
    <div class="m-5">

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf <div class="row justify-content-center">
                <div class="col-lg-6">

                    {{-- Customer Name --}}
                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="customer_name">Your Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="phone">Phone</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="description">Description</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="row">
                        <div class="col-md-8 offset-md-4 text-md-right">
                            <input type="submit" value="Submit Ticket" class="btn btn-success px-4">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
</div>
@endsection