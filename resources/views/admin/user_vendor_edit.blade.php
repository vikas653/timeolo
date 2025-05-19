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
                      <li class="breadcrumb-item" aria-current="page">Vendor</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Vendor</h2>
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
                  
                    <form action="{{ route('vendor_update') }}" enctype="multipart/form-data" method="POST">
                      @csrf
                    
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="name">Company Name</label>
                                    <input placeholder="Write your company name" id="company_name" value="{{$vendor->company_name ?? ''}}" name="company_name" class="form-control" type="text" required>
                                </div>
                                <div class="col">
                                    <label for="">First Name</label>
                                    <input name="first_name" value="{{$vendor->first_name ?? ''}}" id="first_name" class="form-control" type="text" required >
                                </div>
                            </div><br>
                        
                                 <div class="row">
                    
                                        <div class="col">
                                          <label for="name">Last Name</label>
                                          <input placeholder="Write your last name" value="{{$vendor->last_name ?? ''}}" id="last_name" name="last_name" class="form-control" type="text" >
                                      </div>
                                      <div class="col">
                                          <label for="mobile_no">Mobile No</label>
                                          <input name="phone" id="phone" value="{{$vendor->phone ?? ''}}" class="form-control" type="text"  >
                                      </div>
                                  </div><br>
                                  <div class="row">
                    
                                    <div class="col">
                                      <label for="">Address</label>
                                      <input placeholder="Write your address" id="address" name="address" value="{{$vendor->address ?? ''}}"  class="form-control" type="text" >
                                  </div>
                                  <div class="col">
                                      <label for="">Email</label>
                                      <input name="email" value="{{$vendor->email ?? ''}}"  id="email" class="form-control" placeholder="Write your email" type="text"  >
                                  </div>
                              </div><br>
                                  <div class="row">
                    
                                    <div class="col">
                                      <label for="name">Tax Id Number</label>
                                      <input placeholder="Write your tak id number" value="{{$vendor->tax_id_number ?? ''}}"  id="tax_id_number" name="tax_id_number" class="form-control" type="text" >
                                  </div>
                                  <div class="col">
                                    <label for="currency">Consultant Number</label>
                                    <input name="consultant_name" value="{{$vendor->consultant_name ?? ''}}"  id="consultant_name" class="form-control" type="text" >
                                </div>
                              </div><br>
                                
                                <div class="row">
                                  <div class="col">
                                    <label for="name">Pay Rate Currency</label>
                                    <input placeholder="Write your Pay Rate currency" id="pay_rate_currency" value="{{$vendor->pay_rate_currency ?? ''}}"   name="pay_rate_currency" class="form-control" type="text" >
                                  </div>
                                       
                                    
                                      <div class="col">
                                        <label for="name">Pay Rate </label>
                                        <input placeholder="Write your Pay Rate" value="{{$vendor->pay_rate ?? ''}}"  id="pay_rate"  name="pay_rate" class="form-control" type="text" >
                                    </div>
                                  </div><br>
                                 
                            <div class="row">
                    
                              <div class="col">
                                <label for="name">Bank Name</label>
                                <input placeholder="Write your Bank name" value="{{$vendor->bank_name ?? ''}}"   name="bank_name" id="bank_name" class="form-control" type="text" >
                              </div>
                              <div class="col">
                              <label for="name">Bank Branch Address </label>
                              <input placeholder="Write your Bank Branch Address" id="bank_branch_address" value="{{$vendor->bank_branch_address ?? ''}}"   name="bank_branch_address" class="form-control" type="text" >
                              </div>
                              </div><br>
                    
                              <div class="row">
                    
                                <div class="col">
                                  <label for="name">Account Holder's Name</label>
                                  <input placeholder="Write your Account Holder's Name" id="account_holder_name" value="{{$vendor->account_holder_name ?? ''}}"   name="account_holder_name" class="form-control" type="text" >
                                </div>
                                <div class="col">
                                <label for="name">Bank Account Number </label>
                                <input placeholder="Write your Bank Account Number" id="bank_account_number" value="{{$vendor->bank_account_number ?? ''}}"   name="bank_account_number" class="form-control" type="text" >
                                </div>
                                </div><br>
                    
                                <div class="row">
                    
                                  <div class="col">
                                    <label for="name">Swift/BIC Code</label>
                                    <input placeholder="Write your Swift Bic Code" id="swift_bic_code" name="swift_bic_code" value="{{$vendor->swift_bic_code ?? ''}}"  class="form-control" type="text" >
                                  </div>
                                  <div class="col">
                                  <label for="name">IBAN Number </label>
                                  <input placeholder="Write your IBAN Number" id="iban_number" name="iban_number" value="{{$vendor->iban_number ?? ''}} "  class="form-control" type="text" >
                                  </div>
                                  </div><br>
                    
                                  <div class="row">
                    
                                    <div class="col">
                                      <label for="name">Bank Routing Number</label>
                                      <input placeholder="Write your bank routing number" id="bank_routing_number"  name="bank_routing_number" class="form-control" type="text" value="{{$vendor->bank_routing_number ?? ''}}"  >
                                    </div>
                                    <div class="col">
                                        <label for="name">Terms</label>
                                        <input placeholder="Write your terms" id="terms"  name="terms" class="form-control" type="text" value="{{$vendor->terms ?? ''}}"  >
                                      </div>
                                    </div><br>
                                        <input type="hidden" name="id" id="id" />
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div> 
                          </div>
                    </div>
                </form>
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


                 
               
                
                              
                    
                






<!-- Edit Modal -->
<div class="modal fade" id="edit_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Update</button>
      </div>
        </form>
    </div>
  </div>
</div>










       


      


