<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->


<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/pages/login-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 May 2024 05:28:44 GMT -->
@include('admin.commons.header_lib');
<!-- [Head] end -->
<!-- [Body] Start -->


<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  
  @if ($errors->any())
  <div class="alert alert-danger" id="error-alert">
      <ul class="mb-0">
          @foreach ($errors->all() as $error)
              <li><h4>{{ $error }}</h4></li>
          @endforeach
      </ul>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger" id="error-alert">
      <ul class="mb-0">
          @foreach ((array) session('error') as $error)
              <li><h4>{{ $error }}</h4></li>
          @endforeach
      </ul>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success" id="success-alert">
      <ul class="mb-0">
          @foreach ((array) session('success') as $success)
              <li><h4>{{ $success }}</h4></li>
          @endforeach
      </ul>
  </div>
@endif


  <div class="auth-main v1">
    <div class="auth-wrapper">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <div class="text-center">
              <img src="{{ url('public/assets/images/authentication/img-auth-login.png') }}" alt="images" class="img-fluid mb-3">
              <h4 class="f-w-500 mb-1">Reset Password with your email</h4>
              <p class="mb-3">If you are a new user, register yourself as a new user: <a href="{{ route('register_view') }}" class="link-primary ms-1">Create Account</a></p>
            </div>
            <form method="post" action="{{ route('forget_password') }}">
                @csrf
                
                <div class="mb-3">
                <input type="text" name="email" class="form-control" id="" placeholder="Email" required>
                </div>
                <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                  
                </div>
                 <a href="{{ route('login') }}"><h6 class="f-w-400 mb-0">Login</h6></a>
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
        }, 9000);
    });
</script>