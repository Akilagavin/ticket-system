@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Login</h1>
    
    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger col-md-4 mx-auto">
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="m-5">
        <!-- FORM START -->
        <!-- Fix: Changed 'authenticate' to 'login.post' to match web.php -->
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="email">Email Address</label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="form-control" 
                                   required 
                                   autofocus>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-4 text-md-right">
                            <label for="password">Password</label>
                        </div>
                        <div class="col-md-8">
                            <input type="password" 
                                   name="password" 
                                   class="form-control" 
                                   required>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8 offset-md-4 text-md-left">
                            <input type="submit" value="Sign In" class="btn btn-primary px-4">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- FORM END -->
    </div>
</div>
@endsection