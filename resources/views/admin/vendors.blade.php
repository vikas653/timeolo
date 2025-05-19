<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->

  @include('admin.commons.header_lib');
  
<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 May 2024 06:47:33 GMT -->
<!-- @include('admin/commons/header_lib'); -->
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
 <!-- [ Sidebar Menu ] start -->
    @include('admin.commons.sidebar');
<!-- [ Sidebar Menu ] end -->
 <!-- [ Header Topbar ] start -->
    @include('admin.commons.header');
<!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pc-container">
          <div class="pc-content">
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
            
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
              <div class="page-block">
                <div class="row align-items-center">
                  <div class="col-md-12">
                    <ul class="breadcrumb">
                      <li class="breadcrumb-item"><router-link to="/dashboard">Home</router-link></li>
                      <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Vendors</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Vendors</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div><br>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="container">
                <div class="card text-center">
                  <div class="card-header" style="padding: 10px;text-align:left;">
                   
                 
                    {{-- </div> --}}
                 
                    <div class="card-body">
                      <form action="{{ route('add_vendor') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $id }}">
                    
                        <div class="row">
                            <div class="col">
                                <label for="company_name">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="{{ $user->company_name ?? '' }}" placeholder="Write your company name" required>
                            </div>
                            <div class="col">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name ?? '' }}" placeholder="Write your first name" required>
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name ?? '' }}" placeholder="Write your last name">
                            </div>
                            <div class="col">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" value="{{ $user->email ?? '' }}" placeholder="Write your email" required>
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="phone">Mobile Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}" placeholder="Write your mobile number" required>
                            </div>
                            <div class="col">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address ?? '' }}" placeholder="Write your address">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="pay_rate">Pay Rate</label>
                                <input type="text" name="pay_rate" class="form-control" value="{{ $user->pay_rate ?? '' }}" placeholder="Write your pay rate">
                            </div>
                            <div class="col">
                                <label for="consultant_name">Consultant Name</label>
                                <input type="text" name="consultant_name" class="form-control" value="{{ $user->consultant_name ?? '' }}">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="terms">Term</label>
                                <input type="text" name="terms" class="form-control" value="{{ $user->terms ?? '' }}" placeholder="Write your terms">
                            </div>
                            <div class="col">
                                <label for="pay_rate_currency">Pay Rate Currency</label>
                                <input type="text" name="pay_rate_currency" class="form-control" value="{{ $user->pay_rate_currency ?? '' }}" placeholder="Write your pay rate currency">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="tax_id_number">Tax ID Number</label>
                                <input type="text" name="tax_id_number" class="form-control" value="{{ $user->tax_id_number ?? '' }}" placeholder="Write your tax ID number">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="bank_name">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ $user->bank_name ?? '' }}" placeholder="Write your bank name">
                            </div>
                            <div class="col">
                                <label for="bank_branch_address">Bank Branch Address</label>
                                <input type="text" name="bank_branch_address" class="form-control" value="{{ $user->bank_branch_address ?? '' }}" placeholder="Write your bank branch address">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="account_holder_name">Account Holder's Name</label>
                                <input type="text" name="account_holder_name" class="form-control" value="{{ $user->account_holder_name ?? '' }}" placeholder="Write account holder's name">
                            </div>
                            <div class="col">
                                <label for="bank_account_number">Bank Account Number</label>
                                <input type="text" name="bank_account_number" class="form-control" value="{{ $user->bank_account_number ?? '' }}" placeholder="Write bank account number">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="swift_bic_code">Swift/BIC Code</label>
                                <input type="text" name="swift_bic_code" class="form-control" value="{{ $user->swift_bic_code ?? '' }}" placeholder="Write Swift/BIC Code">
                            </div>
                            <div class="col">
                                <label for="iban_number">IBAN Number</label>
                                <input type="text" name="iban_number" class="form-control" value="{{ $user->iban_number ?? '' }}" placeholder="Write IBAN Number">
                            </div>
                        </div><br>
                    
                        <div class="row">
                            <div class="col">
                                <label for="bank_routing_number">Bank Routing Number</label>
                                <input type="text" name="bank_routing_number" class="form-control" value="{{ $user->bank_routing_number ?? '' }}" placeholder="Write bank routing number">
                            </div>
                        </div><br>
                        <a href="{{route('users')}}" class="btn btn-primary">Back</a>
                        <button type="submit" class="btn btn-success">Save Vendor</button>
                    </form>
                    
                      </div>
                          
                    </div>
                    </div>
            </div>
            <!-- [ Main Content ] end -->
          </div>
        </div>

    <!-- [ Main Content ] end -->
    @include('admin.commons.footer');
 
    @include('admin.commons.settings');
    <!-- [Page Specific JS] start -->
    @include('admin/commons/footer_lib');
    
  </body>
  <!-- [Body] end -->

<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 May 2024 06:47:39 GMT -->
</html>







<script>

    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 3000);
    
        $('.edit_btn').click(function() {
            var id = $(this).val();
        var company_name = $('#company_name-' + id).text();
        var first_name = $('#first_name-' + id).text();
        var last_name = $('#last_name-' + id).text();
        var email = $('#email-' + id).text();
        var phone = $('#phone-' + id).text();
        var address = $('#address-' + id).text();
        var pay_rate = $('#pay_rate-' + id).text();    
        var pay_rate_currency = $('#pay_rate_currency-' + id).text();
        var consultant_name = $('#consultant_name-' + id).text();
        var terms = $('#terms-' + id).text();
        var tax_id_number = $('#tax_id_number-' + id).text();
        var bank_name = $('#bank_name-' + id).text();
        var bank_branch_address = $('#bank_branch_address-' + id).text();
        var account_holder_name = $('#account_holder_name-' + id).text();
        var bank_account_number = $('#bank_account_number-' + id).text();
        var swift_bic_code =$('#swift_bic_code-' + id).text();
        var iban_number = $('#iban_number-' + id).text();
        var bank_routing_number = $('#bank_routing_number-' + id).text();
        var user_id = $('#user_id-' + id).text();

        $('#user_id').val(user_id);
        $('#company_name').val(company_name);
        $('#first_name').val(first_name);
        $('#last_name').val(last_name);
        $('#email').val(email);
        $('#phone').val(phone);
        $('#address').val(address);
        $('#pay_rate').val(pay_rate);
        $('#pay_rate_currency').val(pay_rate_currency);
        $('#consultant_name').val(consultant_name);
        $('#terms').val(terms);
        $('#tax_id_number').val(tax_id_number);
        $('#bank_name').val(bank_name);
        $('#bank_branch_address').val(bank_branch_address);
        $('#account_holder_name').val(account_holder_name);
        $('#bank_account_number').val(bank_account_number);
        $('#swift_bic_code').val(swift_bic_code);
        $('#iban_number').val(iban_number);
        $('#bank_routing_number').val(bank_routing_number);
        $('#id').val(id);

        });
    
            $('.delete_btn').click(function(){
                var id = $(this).val();
                $('#delete_id').val(id);
            });
        });
    </script>
