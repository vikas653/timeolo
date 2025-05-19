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
              <img src="../assets/images/authentication/img-auth-fporgot-password.png" alt="images" class="img-fluid mb-3">
              <h4 class="f-w-500 mb-1">Forgot Password</h4>
              <p class="mb-3">Back to <a href="{{ route('dashboard') }}" class="link-primary ms-1">Dashboard</a></p>
            </div>
            <form method="post" action="{{ route('update_password') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="floatingInput" placeholder="Enter Password">
                </div>
                    <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary">Change password</button>
                </div>
            </form>
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