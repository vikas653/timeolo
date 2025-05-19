<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->

  @include('admin.commons.header_lib');
  
<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 May 2024 06:47:33 GMT -->
<!-- @include('admin/commons/header_lib'); -->
  <!-- [Head] end -->
  <!-- [Body] Start -->
  <style>
    /* Show dropdown on hover */
    .hover-dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0; /* remove small gap */
    }
    
    .hover-dropdown .dropdown-toggle::after {
        display: none; /* optional: remove arrow */
    }
    </style>
    
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
                    <form action="">
                      <div class="row">
                        <div class="col-md-3" style="text-align:right;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name" class="form-control" >
                        </div>
                        <div class="col-md-3" style="text-align:right;">
                          <select class="form-control" name="user">
                            <option value="">Select a user</option>
                            @foreach($filter as $data)
                              <option value="{{ $data->id }}" {{ request('user') == $data->id ? 'selected' : '' }}>{{ $data->name }}</option>
                            @endforeach
                          </select>
                      </div>
                        <div class="col-md-1">
                        <button type="submit" class="btn btn-success" >Filter</button>
                        </div>
                        <div class="col" style="text-align:right;">
                     
                          <a href="{{route('user_list_export')}}" class=" btn btn-info ">Export </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_blogs">
                           Add User
                           </button> 
                       
                        </div>
                      </div>
                    </form>
                    <div class="row">
                    
                    
                      <div class="col" style="text-align:right;">
                        <a href="{{route('user_download')}}" class=" btn btn-secondary ">Download Template</a>
                        <button type="button" class="btn btn-success " data-bs-toggle="modal" rel="" data-bs-target="#import_excel">Import Excel</button>
                        
                       
     
                     
                      </div>
                    </div>
                    </div>
                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Name</td>
                                        <td class="text-white" >Email</td>
                                        <td class="text-white" >Mobile No</td>
                                        <td class="text-white" >Address</td>
                                        <td class="text-white" >Status</td>
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                             
                                  @php
                                  $startingNumber = ($users->currentPage() - 1) * $users->perPage() + 1;
                              @endphp
                                    @foreach($users as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        <td id="name-{{ $data->id }}" >{{ $data->name }}</td>
                                        <td id="email-{{ $data->id }}" >{{ $data->email }}</td>
                                        <td id="mobile_no-{{ $data->id }}" >{{ $data->mobile_no }}</td>
                                        <td id="address-{{ $data->id }}" >{{ $data->address }}</td>
                                        <td>
                                          @if($data->role_id == 3)
                                          Active
                                      @else
                                          InActive
                                      @endif
                                      
                                        </td>
                                        <span id="pay_rate-{{ $data->id }}" class="d-none" >{{ $data->pay_rate }}</span>
                                        <span id="currency-{{ $data->id }}" class="d-none">{{ $data->currency }}</span>
                                      
                                        <span id="job_title-{{ $data->id }}" class="d-none" >{{ $data->job_title }}</span>
                                        <span id="dob-{{ $data->id }}" class="d-none">{{ $data->dob }}</span>
                                        <span id="ssn-{{ $data->id }}" class="d-none">{{ $data->ssn }}</span>
                                        <span id="employment_id-{{ $data->id }}" class="d-none">{{ $data->employment_id }}</span>
                                        <span id="system_access_id-{{ $data->id }}" class="d-none" >{{ $data->system_access_id }}</span>
                                        <span id="start_date-{{ $data->id }}" class="d-none">{{ $data->start_date }}</span>
                                        <span id="end_date-{{ $data->id }}" class="d-none">{{ $data->end_date }}</span>
                                        <span id="bank_name-{{ $data->id }}" class="d-none">{{ $data->bank_name }}</span>
                                        <span id="bank_branch_address-{{ $data->id }}" class="d-none">{{ $data->bank_branch_address }}</span>
                                        <span id="account_holder_name-{{ $data->id }}" class="d-none">{{ $data->account_holder_name }}</span>
                                        <span id="bank_account_number-{{ $data->id }}" class="d-none">{{ $data->bank_account_number }}</span>
                                        <span id="swift_bic_code-{{ $data->id }}" class="d-none">{{ $data->swift_bic_code }}</span>
                                        <span id="iban_number-{{ $data->id }}" class="d-none">{{ $data->iban_number }}</span>
                                        <span id="bank_routing_number-{{ $data->id }}" class="d-none">{{ $data->bank_routing_number }}</span>
                                        <span id="work_email-{{ $data->id }}" class="d-none">{{ $data->work_email }}</span>
                                        <span id="vendor_id-{{ $data->id }}" class="d-none">{{ $data->vendor_id }}</span>
                                        <span id="location-{{ $data->id }}" class="d-none">{{ $data->location }}</span>
                                        <span id="pay_rate_currency-{{ $data->id }}" class="d-none">{{ $data->pay_rate_currency }}</span>
                                        <td>
                                          <input id="client-{{ $data->id }}" type="hidden" value="{{ $data->client_id }}">
                                      
                                          <div class="dropdown hover-dropdown">
                                              <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenu{{ $data->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                  Actions
                                              </button>
                                              <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $data->id }}">
                                                  <li><a class="dropdown-item" href="{{ route('vendors', $data->id) }}">Vendor</a></li>
                                                  <li><button type="button" class="dropdown-item edit_btn" value="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#edit_blogs">Edit</button></li>
                                                  <li><button type="button" class="dropdown-item delete_btn" value="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#delete_blogs">Delete</button></li>
                                                  <li>
                                                      <form action="{{ route('user_status') }}" method="POST" class="d-inline">
                                                          @csrf
                                                          <input type="hidden" name="id" value="{{ $data->id }}">
                                                          <input type="hidden" name="role_id" value="3">
                                                          <button type="submit" class="dropdown-item">Activate</button>
                                                      </form>
                                                  </li>
                                                  <li>
                                                      <form action="{{ route('user_status') }}" method="POST" class="d-inline">
                                                          @csrf
                                                          <input type="hidden" name="id" value="{{ $data->id }}">
                                                          <input type="hidden" name="role_id" value="0">
                                                          <button type="submit" class="dropdown-item">Inactivate</button>
                                                      </form>
                                                  </li>
                                              </ul>
                                          </div>
                                      </td>
                                      
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
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


<!-- Add  Modal -->
<div class="modal fade" id="add_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('add_user') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                        <div class="col">
                            <label for="name">Name</label>
                            <input placeholder="Write your name" name="name" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="mobile_no">Mobile No.</label>
                            <input name="mobile_no" class="form-control" type="text" placeholder="Write your Moblie number" >
                        </div>
                    </div><br>
                    <div class="row">
                    <div class="col">
                      <label for="email">Email</label>
                      <input placeholder="Write your email" name="email" class="form-control" type="text" required>
                      @error('email')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
                    </div>
                    <div class="col">
                      <label for="mobile_no">Password</label>
                      <input name="password" class="form-control" type="text" placeholder="Write your password" required >
                    </div>
                    </div><br>
                    <div class="row">

                      {{-- <div class="col">
                        <label for="">Vendor Company Name</label>
                        <select name="vendor_id" id="" class="form-control">
                        <option value=""></option>
                        @foreach ( $vendor as  $vendors)
                          <option value="{{$vendors->id}}">{{$vendors->company_name}}</option>
                        @endforeach
                      </select>
                    </div> --}}
                   
                </div><br>
                    <div class="row">
                    <div class="col">
                      <label for="work_email">Work Email</label>
                      <input placeholder="Write your work email" name="work_email" class="form-control" type="text" >
                      @error('work_email')
                          <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="col">
                      <label for="">Location</label>
                      <input name="location" class="form-control" type="text" placeholder="Write Your Location"  >
                  </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                          <label for="name">Pay Rate</label>
                          <input placeholder="Write your pay_rate"  name="pay_rate" class="form-control" type="text" >
                      </div>
                      <div class="col">
                          <label for="currency">Currency</label>
                          <input name="currency"  class="form-control" type="text" >
                      </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                        <label for="name">Pay Term</label>
                        <input placeholder="Write your Pay Term"  name="pay_rate_currency" class="form-control" type="text" >
                      </div>
                            
                          <div class="col">
                            <label for="name">Job </label>
                            <input placeholder="Write your job title"  name="job_title" class="form-control" type="text" >
                        </div>
                      </div><br>
                        <div class="row">
                         
                          <div class="col">
                            <label for="name">Address</label>
                            <input placeholder="Write your Address"  name="address" class="form-control" type="text" >
                        </div>
                        <div class="col">
                          <label for="name">SSN </label>
                          <input placeholder="Write your ssn"  name="ssn" class="form-control" type="text" >
                      </div>
                       </div><br>

                      <div class="row">

                        <div class="col">
                          <label for="name">Type of Employment</label>
                          <select name="employment_id" id="" class="form-control">
                            <option value=""></option>
                            @foreach ( $employment as $data )
                            <option value="{{$data->id}}">{{$data->name}}</option>
                              
                            @endforeach

                          </select>
                      </div>
                      <div class="col">
                        <label for="name">Type of System Access</label>
                        <select name="system_access_id" id="" class="form-control">
                          <option value=""></option>
                          @foreach ( $system_access as $data )
                          <option value="{{$data->id}}">{{$data->name}}</option>
                            
                          @endforeach

                        </select>
                    </div>
                    </div><br>
                    <div class="row">

                    <div class="col">
                      <label for="name">Start Date</label>
                      <input placeholder="Write your start date"  name="start_date" class="form-control" type="date" >
                    </div>
                    <div class="col">
                    <label for="name">End Date </label>
                    <input placeholder="Write your end date"  name="end_date" class="form-control" type="date" >
                    </div>
                    </div><br>

                    <div class="row">

                      <div class="col">
                        <label for="name">Bank Name</label>
                        <input placeholder="Write your Bank name"  name="bank_name" class="form-control" type="text" >
                      </div>
                      <div class="col">
                      <label for="name">Bank Branch Address </label>
                      <input placeholder="Write your Bank Branch Address"  name="bank_branch_address" class="form-control" type="text" >
                      </div>
                      </div><br>

                      <div class="row">

                        <div class="col">
                          <label for="name">Account Holder's Name</label>
                          <input placeholder="Write your Account Holder's Name"  name="account_holder_name" class="form-control" type="text" >
                        </div>
                        <div class="col">
                        <label for="name">Bank Account Number </label>
                        <input placeholder="Write your Bank Account Number"  name="bank_account_number" class="form-control" type="text" >
                        </div>
                        </div><br>

                        <div class="row">

                          <div class="col">
                            <label for="name">Swift/BIC Code</label>
                            <input placeholder="Write your Swift Bic Code"  name="swift_bic_code" class="form-control" type="text" >
                          </div>
                          <div class="col">
                          <label for="name">IBAN Number </label>
                          <input placeholder="Write your IBAN Number"  name="iban_number" class="form-control" type="text" >
                          </div>
                          </div><br>

                          <div class="row">

                            <div class="col">
                              <label for="name">Bank Routing Number</label>
                              <input placeholder="Write your bank routing number"  name="bank_routing_number" class="form-control" type="text" >
                            </div>
                            <div class="col">
                              <label for="name">Date of Birth</label>
                              <input placeholder="Write your Date of birth"  name="dob" class="form-control" type="date" >
                          </div>
                            </div><br>
                 
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Save</button>
      </div>
        </form>
    </div>
  </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="edit_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('edit_user') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
                        <div class="col">
                            <label for="name">Name</label>
                            <input placeholder="Write your name" id="name" name="name" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="mobile_no">Mobile No.</label>
                            <input name="mobile_no" id="mobile_no" class="form-control" type="text" >
                        </div>
                    </div><br>
             <div class="row">

                    <div class="col">
                      <label for="email">Email</label>
                      <input placeholder="Write your email" id="email" name="email" class="form-control" type="text" required>
                      @error('email')
                      <small class="text-danger">{{ $message }}</small>
                  @enderror
                  </div>
                  <div class="col">
                      <label for="mobile_no">Password</label>
                      <input name="password" id="password" class="form-control" type="text"  >
                  </div>
              </div><br>
              <div class="row">

                {{-- <div class="col">
                  <label for="">Vendor Company Name</label>
                  <select name="vendor_id" id="vendor_id" class="form-control">
                  <option value=""></option>
                  @foreach ( $vendor as  $vendors)
                    <option value="{{$vendors->id}}">{{$vendors->company_name}}</option>
                  @endforeach
                </select>
              </div> --}}
             
          </div><br>
              <div class="row">

                <div class="col">
                  <label for="work_email">Work Email</label>
                  <input placeholder="Write your work email" id="work_email" name="work_email" class="form-control" type="text" >
                  @error('work_email')
                          <small class="text-danger">{{ $message }}</small>
                      @enderror
              </div>
              <div class="col">
                <label for="">Location</label>
                <input name="location" id="location" class="form-control" placeholder="Write your Location" type="text"  >
            </div>
          </div><br>
              <div class="row">
                <div class="col">
                    <label for="name">Pay Rate</label>
                    <input placeholder="Write your pay_rate" name="pay_rate" id="pay_rate" class="form-control" type="text" >
                </div>
                <div class="col">
                    <label for="currency">Currency</label>
                    <input name="currency" id="currency" class="form-control" type="text" >
                </div>
            </div><br>
            <div class="row">
              <div class="col">
                <label for="name">Pay Term</label>
                <input placeholder="Write your Pay Term" id="pay_rate_currency"  name="pay_rate_currency" class="form-control" type="text" >
              </div>
                   
                
                  <div class="col">
                    <label for="name">Job </label>
                    <input placeholder="Write your job title" id="job_title"  name="job_title" class="form-control" type="text" >
                </div>
              </div><br>
              <div class="row">
                <div class="col">
                  <label for="name">Address</label>
                  <input placeholder="Write your Address" id="address" name="address" class="form-control" type="text" >
              </div>
               
             
              <div class="col">
                <label for="name">SSN </label>
                <input placeholder="Write your ssn"  name="ssn" id="ssn" class="form-control" type="text" >
            </div>
          </div><br>
          <div class="row">

            <div class="col">
              <label for="name">Type of Employment</label>
              <select name="employment_id" id="employment_id" class="form-control">
                <option value=""></option>
                @foreach ( $employment as $data )
                <option value="{{$data->id}}">{{$data->name}}</option>
                  
                @endforeach

              </select>
          </div>
          <div class="col">
            <label for="name">Type of System Access</label>
            <select name="system_access_id" id="system_access_id" class="form-control">
              <option value=""></option>
              @foreach ( $system_access as $data )
              <option value="{{$data->id}}">{{$data->name}}</option>
                
              @endforeach

            </select>
        </div>
        </div><br>
        <div class="row">

        <div class="col">
          <label for="name">Start Date</label>
          <input placeholder="Write your start date" id="start_date"  name="start_date" class="form-control" type="date" >
        </div>
        <div class="col">
        <label for="name">End Date </label>
        <input placeholder="Write your end date" id="end_date"  name="end_date" class="form-control" type="date" >
        </div>
        </div><br>
        <div class="row">

          <div class="col">
            <label for="name">Bank Name</label>
            <input placeholder="Write your Bank name"  name="bank_name" id="bank_name" class="form-control" type="text" >
          </div>
          <div class="col">
          <label for="name">Bank Branch Address </label>
          <input placeholder="Write your Bank Branch Address" id="bank_branch_address"  name="bank_branch_address" class="form-control" type="text" >
          </div>
          </div><br>

          <div class="row">

            <div class="col">
              <label for="name">Account Holder's Name</label>
              <input placeholder="Write your Account Holder's Name" id="account_holder_name"  name="account_holder_name" class="form-control" type="text" >
            </div>
            <div class="col">
            <label for="name">Bank Account Number </label>
            <input placeholder="Write your Bank Account Number" id="bank_account_number"  name="bank_account_number" class="form-control" type="text" >
            </div>
            </div><br>

            <div class="row">

              <div class="col">
                <label for="name">Swift/BIC Code</label>
                <input placeholder="Write your Swift Bic Code" id="swift_bic_code" name="swift_bic_code" class="form-control" type="text" >
              </div>
              <div class="col">
              <label for="name">IBAN Number </label>
              <input placeholder="Write your IBAN Number" id="iban_number" name="iban_number" class="form-control" type="text" >
              </div>
              </div><br>

              <div class="row">

                <div class="col">
                  <label for="name">Bank Routing Number</label>
                  <input placeholder="Write your bank routing number" id="bank_routing_number"  name="bank_routing_number" class="form-control" type="text" >
                </div>
                <div class="col">
                  <label for="name">Date of Birth</label>
                  <input placeholder="Write your Date of birth" id="dob" name="dob" class="form-control" type="date" >
              </div>
                </div><br>
                    <input type="hidden" name="id" id="id" />
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Update</button>
      </div>
        </form>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="delete_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this user ?<h4>
      </div>
      <form action="{{ route('delete_user') }}" method="post" >
        <input type="hidden" name="id" id="delete_id" value=""/>
        @csrf
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Delete</button>
      </div>
        </form>
    </div>
  </div>
</div>


<div class="modal fade" id="import_excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('import_user_excel') }}" enctype="multipart/form-data" method="POST">
        @csrf
      <div class="modal-body">
           
            <div class="form-group">
            <label>Select Excel File</label>
            <input class="form-control" type="file" required name="file">
            </div>
           
            {{-- <input type="hidden" value="" name="timesheet_id" id="timesheet_id" >  --}}
          
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Upload</button>
      </div>
        </form> 
    </div>
  </div>
</div>


<script>

$(document).ready(function() {
  setTimeout(function() {
        $('#success-alert').fadeOut('slow');
        $('#error-alert').fadeOut('slow');
    }, 3000);

    $('.edit_btn').click(function() {
        var id = $(this).val();

        // Get visible text from table cells
        var name = $('#name-' + id).text();
        var mobile_no = $('#mobile_no-' + id).text();
        var address = $('#address-' + id).text();
        var email = $('#email-' + id).text();
        var work_email = $('#work_email-' + id).text();


        var pay_rate = $('#pay_rate-' + id).text();
        var currency = $('#currency-' + id).text();
        var bank_details = $('#bank_details-' + id).text();
        var job_title = $('#job_title-' + id).text();
        var dob = $('#dob-' + id).text();
        var ssn = $('#ssn-' + id).text();
        var employment_id = $('#employment_id-' + id).text();
        var system_access_id = $('#system_access_id-' + id).text();
        var start_date = $('#start_date-' + id).text();
        var end_date = $('#end_date-' + id).text();
        var client = $('#client-' + id).val();
        var bank_name = $('#bank_name-' + id).text();
        var bank_branch_address = $('#bank_branch_address-' + id).text();
        var account_holder_name = $('#account_holder_name-' + id).text();
        var bank_account_number = $('#bank_account_number-' + id).text();
        var swift_bic_code = $('#swift_bic_code-' + id).text();
        var iban_number = $('#iban_number-' + id).text();
        var bank_routing_number = $('#bank_routing_number-' + id).text();
        var pay_rate_currency = $('#pay_rate_currency-' + id).text();
        var vendor_id = $('#vendor_id-' + id).text();
        var location = $('#location-' + id).text();


       
        $('#name').val(name);
        $('#mobile_no').val(mobile_no);
        $('#address').val(address);
        $('#email').val(email);
        $('#work_email').val(work_email);

        $('#pay_rate').val(pay_rate);
        $('#currency').val(currency);
        $('#bank_details').val(bank_details);
        $('#job_title').val(job_title);
        $('#dob').val(dob);
        $('#ssn').val(ssn);
        $('#employment_id').val(employment_id);
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
        $('#system_access_id').val(system_access_id);
        $('#bank_name').val(bank_name);
        $('#bank_branch_address').val(bank_branch_address);
        $('#account_holder_name').val(account_holder_name);
        $('#bank_account_number').val(bank_account_number);
        $('#swift_bic_code').val(swift_bic_code);
        $('#iban_number').val(iban_number);
        $('#bank_routing_number').val(bank_routing_number);
        $('#pay_rate_currency').val(pay_rate_currency);
        $('#vendor_id').val(vendor_id);
        $('#location').val(location);

        $('#client').val(client);
        $('#id').val(id);
    });

        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>


