<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->


<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/pages/login-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 May 2024 05:28:44 GMT -->
@include('admin.commons.header_lib');
<!-- [Head] end -->
<!-- [Body] Start -->

@if (session('success'))
                <div id="success-alert" class="alert alert-success alert_show">
                    {{ session('success') }}
                </div>
            @endif  
            @if (session('error'))
                <div id="error-alert" class="alert alert-danger alert_show">
                    {{ session('error') }}
                </div>
            @endif
<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main v1">
    <div class="auth-wrapper">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <div class="text-center">
              <img src="{{ url('public/assets/images/authentication/img-auth-login.png') }}" alt="images" class="img-fluid mb-3">
              <h4 class="f-w-500 mb-1">Reset Your Password</h4>
              <p class="mb-3">If you are a new user, register yourself as a new user: <a href="{{ route('register_view') }}" class="link-primary ms-1">Create Account</a></p>
            </div>
			<form action="{{route('new_password.action')}}" method="POST" class="login100-form validate-form">
                @csrf
                <div class="mb-3">
                <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email Address" required>
                </div>
                <div class="mb-3">
                <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="Password" required>
                </div>
                <div class="mb-3">
				
				<input class="form-control" type="password" name="conform_password" placeholder="Conform Password">
			</div>
				
                <div class="d-flex mt-1 justify-content-between align-items-center">
              
                </div>
                 {{-- <a href="{{ route('forget_password') }}"><h6 class="f-w-400 mb-0">Forgot Password?</h6></a>  --}}
                </div>
                <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
             <div class="saprator my-3">
              <span>Or continue with</span>
            </div>
            <div class="text-center">
              
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  @include('admin.commons.footer_lib');
   
</div>
</body>
<!-- [Body] end -->


<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/pages/login-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 May 2024 05:28:49 GMT -->
</html>

<script>
     $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 2000);
    });
</script>








		<form action="{{route('new_password.action')}}" method="POST" class="login100-form validate-form">
			@csrf
			<div class="wrap-input100" data-validate="Email is required">
				<input class="input100" type="email" name="email" placeholder="Email">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-envelope" aria-hidden="true"></i>
				</span>
			</div>
			<div class="wrap-input100 validate-input" data-validate="Password is required">
				<input class="input100" type="password" name="password" placeholder="Password">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			<div class="wrap-input100 validate-input" data-validate="Password is required">
				<input class="input100" type="password" name="conform_password" placeholder="Conform Password">
				<span class="focus-input100"></span>
				<span class="symbol-input100">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</span>
			</div>
			<div class="container-login100-form-btn pt-2">
				<button type="submit" name="submit" class="login100-form-btn btn-primary">
					Reset Password
				</button>
				
			</div>
			<div class="text-center pt-2">
				<span class="txt1">
					I Forgot
				</span>
				<a class="txt2" href="{{url('forgot-password')}}">
					Give me Hint
				</a>
			</div>
		</form>