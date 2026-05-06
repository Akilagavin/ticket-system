@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Open New Ticket</h1>
    <p class="text-muted">Please fill in the details below and we will get back to you shortly.</p>

    <div class="m-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Global Error Summary --}}
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

                <form action="{{ route('tickets.store') }}" method="POST" class="mt-4 needs-validation" novalidate>
                    @csrf 
                    
                    <div class="row justify-content-center">
                        <div class="col-md-10">

                            {{-- Customer Name --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="customer_name" class="col-form-label">Your Name</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="customer_name" id="customer_name"
                                           value="{{ old('customer_name') }}" 
                                           class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" 
                                           placeholder="Enter your full name" required maxlength="255">
                                    <div class="invalid-feedback text-left">
                                        Please provide your full name.
                                    </div>
                                </div>
                            </div>

                            {{-- Email Address --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="email" class="col-form-label">Email Address</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email') }}" 
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                           placeholder="example@mail.com" required>
                                    <div class="invalid-feedback text-left">
                                        Please provide a valid email address (e.g., name@domain.com).
                                    </div>
                                </div>
                            </div>

                            {{-- Phone Number --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="phone" class="col-form-label">Phone Number</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="phone" id="phone"
                                           value="{{ old('phone') }}" 
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
                                           placeholder="+94 ...">
                                </div>
                            </div>

                            {{-- Ticket Category --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="category_id" class="col-form-label">Category</label>
                                </div>
                                <div class="col-md-8 text-left">
                                    <select name="category_id" id="category_id" 
                                            class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                                        <option value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback text-left">
                                        Please select a category.
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-group row">
                                <div class="col-md-4 text-md-right">
                                    <label for="description" class="col-form-label">Issue Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea name="description" id="description"
                                              class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                                              rows="5" placeholder="Briefly describe your issue..." required minlength="10"></textarea>
                                    <div class="invalid-feedback text-left">
                                        Please describe your issue (min. 10 characters).
                                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.needs-validation');
    const inputs = form.querySelectorAll('input, select, textarea');

    // 1. Instant Validation as you type/interact
    inputs.forEach(input => {
        input.addEventListener('input', function () {
            if (input.checkValidity()) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        });

        // Also check on blur (when you leave the field)
        input.addEventListener('blur', function () {
            if (!input.checkValidity()) {
                input.classList.add('is-invalid');
            }
        });
    });

    // 2. Final Check on Submit
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
});
</script>
@endsection