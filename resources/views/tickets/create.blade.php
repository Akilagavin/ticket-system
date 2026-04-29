@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Open New Ticket</h1>
    <p class="text-muted">Please fill in the details below and we will get back to you shortly.</p>
    
    <div class="m-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Exercise 2: Global Error Summary --}}
                {{-- This block loops through all validation errors and displays them in a single list --}}
                @if ($errors->any())
                    <div class="alert alert-danger text-left">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tickets.store') }}" method="POST" class="mt-4">
                    @csrf 
                    
                    <div class="row justify-content-center">
                        <div class="col-md-10">

                            {{-- Exercise 1: Customer Name with Inline Validation --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="customer_name" class="col-form-label">Your Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="customer_name" 
                                           value="{{ old('customer_name') }}" 
                                           class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" 
                                           placeholder="Enter your full name">
                                    @if($errors->has('customer_name'))
                                        <div class="invalid-feedback text-left">
                                            {{ $errors->first('customer_name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Exercise 1: Email with Inline Validation --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="email" class="col-form-label">Email Address</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email" 
                                           value="{{ old('email') }}" 
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                           placeholder="example@mail.com">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback text-left">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Exercise 1: Phone (Optional but with Error Handling) --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="phone" class="col-form-label">Phone Number</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="phone" 
                                           value="{{ old('phone') }}" 
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
                                           placeholder="+94 ...">
                                    @if($errors->has('phone'))
                                        <div class="invalid-feedback text-left">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Exercise 1: Description with Inline Validation --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="description" class="col-form-label">Issue Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                                              rows="5" placeholder="Briefly describe your issue...">{{ old('description') }}</textarea>
                                    @if($errors->has('description'))
                                        <div class="invalid-feedback text-left">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="row mt-4">
                                <div class="col-md-8 offset-md-4 text-md-right">
                                    <a href="{{ url('/') }}" class="btn btn-link text-secondary">Cancel</a>
                                    <input type="submit" value="Submit Ticket" class="btn btn-success px-5 shadow-sm">
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection