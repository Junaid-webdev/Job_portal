@extends('front.layouts.app')
@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="" method="post" name="registrationForm" id="registrationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Please Confirm  Password">
                            <p></p>
                        </div> 
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
    <script>

        $('#registrationForm').submit(function(e){
            e.preventDefault();

            $.ajax({
                url: '{{ route("account.process") }}',
                type: 'post',
                data: $('#registrationForm').serialize(),
                datatype: 'json',
                success: function(response){
                    if(response.status == false){
                        var errors = response.errors;
                        if(errors.name){
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name)
                        }
                        if(errors.email){
                            $('#email').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors.email)
                        }
                        if(errors.password){
                            $('#password').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors.password)
                        }
                        if(errors.confirm_password){
                            $('#confirm_password').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors.confirm_password)
                        }
                    }else{
                        window.location.href='{{ route('account.login') }}';
                    }
                }
        });
        // Input change hote hi error remove karo
$('#registrationForm input').on('input', function() {
    $(this)
        .removeClass('is-invalid')        // red border hatao
        .siblings('p')
        .html('')                         // error text empty karo
        .removeClass('invalid-feedback'); // red text class hatao
});

        });

    </script>
@endsection