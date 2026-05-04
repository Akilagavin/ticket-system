@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1>Login</h1>
    <div class="m-5">

        <!-- FORM START -->
        <form class="" action="{{ route('authenticate') }}" method="post">
            {{ csrf_field() }}

            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="email">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="email" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4 text-md-right">
                            <label for="email">Password</label>
                        </div>
                        <div class="col-md-8">
                            <input type="password" name="password" value="" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 offset-md-4 text-md-right">
                            <input type="submit" value="Submit" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- FORM END -->

    </div>
</div>
@endsection