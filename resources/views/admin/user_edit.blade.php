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
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
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
                      <li class="breadcrumb-item" aria-current="page">Users</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Users</h2>
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
                  
                    <form action="{{ route('user_update', $user->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST') 
                    
                    <div class="card-body">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <label for="name">Name</label>
                                    <input placeholder="Write your name" id="name" value="{{$user->name}}" name="name" class="form-control" type="text" required>
                                </div>
                                <div class="col">
                                    <label for="mobile_no">Mobile No.</label>
                                    <input name="mobile_no" value="{{$user->mobile_no}}" id="mobile_no" class="form-control" type="number" >
                                </div>
                            </div><br>
                        
                                 <div class="row">
                    
                                        <div class="col">
                                          <label for="email">Email</label>
                                          <input placeholder="Write your email" value="{{$user->email}}" id="email" name="email" class="form-control" type="text" required>
                                          @error('email')
                                          <small class="text-danger">{{ $message }}</small>
                                      @enderror
                                      </div>
                                      <div class="col">
                                          <label for="mobile_no">Password</label>
                                          <input name="password" class="form-control" type="password">

                                      </div>
                                  </div><br>
                                  <div class="row">
                    
                                    {{-- <div class="col">
                                      <label for="">Vender Company</label>
                                      <input placeholder="Write your Vender Name" id="vender_name" name="vender_name" value="{{$user->vender_name}}"  class="form-control" type="text" required>
                                  </div> --}}
                                  <div class="col">
                                      <label for="">Location</label>
                                      <input name="location" value="{{$user->location}}"  id="location" class="form-control" placeholder="Write your Location" type="text"  >
                                  </div>
                              </div><br>
                                  <div class="row">
                    
                                    <div class="col">
                                      <label for="work_email">Work Email</label>
                                      <input placeholder="Write your work email" value="{{$user->work_email}}"  id="work_email" name="work_email" class="form-control" type="text" >
                                      @error('work_email')
                                      <small class="text-danger">{{ $message }}</small>
                                  @enderror
                                  </div>
                                 
                              </div><br>
                                  <div class="row">
                                   
                                    <div class="col">
                                        <label for="currency">Currency</label>
                                        <input name="currency" value="{{$user->currency}}"  id="currency" class="form-control" type="text" >
                                    </div>
                                </div><br>
                                <div class="row">
                                  {{-- <div class="col">
                                    <label for="name">Pay Rate Unit</label>
                                    <input placeholder="Write your Pay Rate Unit" id="pay_rate_currency" value="{{$user->pay_rate_currency}}"   name="pay_rate_currency" class="form-control" type="text" >
                                  </div> --}}
                                       
                                    
                                      <div class="col">
                                        <label for="name">Job </label>
                                        <input placeholder="Write your job title" value="{{$user->job_title}}"  id="job_title"  name="job_title" class="form-control" type="text" >
                                    </div>
                                  </div><br>
                                  <div class="row">
                                    <div class="col">
                                      <label for="name">Address</label>
                                      <input placeholder="Write your Address" value="{{$user->address}}"  id="address" name="address" class="form-control" type="text" >
                                  </div>
                                   
                                 
                                  <div class="col">
                                    <label for="name">SSN </label>
                                    <input placeholder="Write your ssn" value="{{$user->ssn}}"   name="ssn" id="ssn" class="form-control" type="text" >
                                </div>
                              </div><br>
                            
                             
                            <div class="row">
                    
                            <div class="col">
                              <label for="name">Start Date</label>
                              <input placeholder="Write your start date" id="start_date"  name="start_date" class="form-control" value="{{$user->start_date}}"  type="date" >
                            </div>
                            <div class="col">
                            <label for="name">End Date </label>
                            <input placeholder="Write your end date" value="{{$user->end_date}}"  id="end_date"  name="end_date" class="form-control" type="date" >
                            </div>
                            </div><br>
                            <div class="row">
                    
                              <div class="col">
                                <label for="name">Bank Name</label>
                                <input placeholder="Write your Bank name" value="{{$user->bank_name}}"   name="bank_name" id="bank_name" class="form-control" type="text" >
                              </div>
                              <div class="col">
                              <label for="name">Bank Branch Address </label>
                              <input placeholder="Write your Bank Branch Address" id="bank_branch_address" value="{{$user->bank_branch_address}}"   name="bank_branch_address" class="form-control" type="text" >
                              </div>
                              </div><br>
                    
                              <div class="row">
                    
                                <div class="col">
                                  <label for="name">Account Holder's Name</label>
                                  <input placeholder="Write your Account Holder's Name" id="account_holder_name" value="{{$user->account_holder_name}}"   name="account_holder_name" class="form-control" type="text" >
                                </div>
                                <div class="col">
                                <label for="name">Bank Account Number </label>
                                <input placeholder="Write your Bank Account Number" id="bank_account_number" value="{{$user->bank_account_number}}"   name="bank_account_number" class="form-control" type="text" >
                                </div>
                                </div><br>
                    
                                <div class="row">
                    
                                  <div class="col">
                                    <label for="name">Swift/BIC Code</label>
                                    <input placeholder="Write your Swift Bic Code" id="swift_bic_code" name="swift_bic_code" value="{{$user->swift_bic_code}}"  class="form-control" type="text" >
                                  </div>
                                  <div class="col">
                                  <label for="name">IBAN Number </label>
                                  <input placeholder="Write your IBAN Number" id="iban_number" name="iban_number" value="{{$user->iban_number}}"  class="form-control" type="text" >
                                  </div>
                                  </div><br>
                    
                                  <div class="row">
                    
                                    <div class="col">
                                      <label for="name">Bank Routing Number</label>
                                      <input placeholder="Write your bank routing number" id="bank_routing_number"  name="bank_routing_number" class="form-control" type="text" value="{{$user->bank_routing_number}}"  >
                                    </div>
                                    <div class="col">
                                      <label for="name">Date of Birth</label>
                                      <input placeholder="Write your Date of birth" id="dob" name="dob" class="form-control" type="date"value="{{$user->dob}}"  >
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










       


      


