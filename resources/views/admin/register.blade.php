<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->


<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/pages/register-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 May 2024 05:28:59 GMT -->
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
              <li>{{ $error }}</li>
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
            <img src="{{ url('public/assets/images/authentication/img-auth-register.png') }}" alt="images" class="img-fluid mb-3">
              <h4 class="f-w-500 mb-1">Register with your email</h4>
              <p class="mb-3">Already have an Account? <a href="{{ route('login_view') }}" class="link-primary">Log in</a></p>
            </div>
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                </div>
                <div class="mb-3">
                <input type="number" name="mobile_no" class="form-control" placeholder="Phone number">
                </div>
                <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                    <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                    <label class="form-check-label text-muted" for="customCheckc1">I agree to all the Terms & Condition</label>
                </div>
                </div>
                <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
             <div class="saprator my-3">
              <span>Or continue with</span>
            </div>
            <div class="text-center">
              <ul class="list-inline mx-auto mt-3 mb-0">
                <li class="list-inline-item">
                  <a href="https://www.facebook.com/" class="avtar avtar-s rounded-circle bg-facebook" target="_blank">
                    <i class="fab fa-facebook-f text-white"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://twitter.com/" class="avtar avtar-s rounded-circle bg-twitter" target="_blank">
                    <i class="fab fa-twitter text-white"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://myaccount.google.com/" class="avtar avtar-s rounded-circle bg-googleplus" target="_blank">
                    <i class="fab fa-google text-white"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      

    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
@include('admin.commons.footer_lib')

</div>
</body>
<!-- [Body] end -->


<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/pages/register-v1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 May 2024 05:29:00 GMT -->
</html>
<script>
  $(document).ready(function() {
     setTimeout(function() {
         $('#success-alert').fadeOut('slow');
         $('#error-alert').fadeOut('slow');
     }, 2000);
 });
</script>