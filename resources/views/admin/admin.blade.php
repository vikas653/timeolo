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
                      <li class="breadcrumb-item" aria-current="page">Admin</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Admin</h2>
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
                       
                     
                        <div class="col" style="text-align:right;">
                     
                          
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_blogs">
                           Add Admin
                           </button> 
       
                       
                        </div>
                      </div>
                    </form>
                    
                    </div>
                    {{-- </div> --}}
                 
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
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                    @php
                                        $startingNumber = ($admin->currentPage()- 1)* $admin->perPage()+ 1
                                    @endphp
                                    @foreach ($admin as $key=>$item)
                                        <tr>
                                            <td>{{$startingNumber + $key}}</td>
                                            <td id="name-{{$item->id}}"> {{$item->name}} </td>
                                            <td id="email-{{$item->id}}"> {{$item->email}} </td>
                                            <td id="mobile_no-{{$item->id}}"> {{$item->mobile_no}} </td>
                                            <td id="address-{{$item->id}}"> {{$item->address}} </td>
                                            <td>
                                                <button type="button" value="{{$item->id}}" class="btn btn-success edit_btn" data-bs-toggle="modal" data-bs-target="#edit_blogs">
                                                    Edit 
                                                    </button>
                                                <button type="button" value="{{$item->id}}" class="btn btn-danger delete_btn" data-bs-toggle="modal" data-bs-target="#delete_blogs">Delete</button>    
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              
                            </table>
                            {{$admin->links()}}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Admin</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('add_admin') }}" enctype="multipart/form-data" method="POST">
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
                        <div class="col">
                          <label for="email">Address</label>
                          <input placeholder="Write your Address" name="address" class="form-control" type="text" required>
                         
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Admin</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('edit_admin') }}" enctype="multipart/form-data" method="POST">
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

                <div class="col">
                  <label for="email">Address</label>
                  <input placeholder="Write your address" id="address" name="address" class="form-control" type="text" required>
                 
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Admin</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this admin ?<h4>
      </div>
      <form action="{{ route('delete_admin') }}" method="post" >
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





<script>
$(document).ready(function(){
    setTimeout(function() {
        $('#success-alert').fadeOut('slow');
        $('#error-alert').fadeOut('slow');
    }, 3000);

 $('.edit_btn').click(function(){
    var id = $(this).val();
    var name = $('#name-'+ id).text();
    var email = $('#email-'+ id).text();
    var mobile_no = $('#mobile_no-'+ id).text();
    var address = $('#address-'+ id).text();

    $('#name').val(name);
    $('#email').val(email);
    $('#mobile_no').val(mobile_no);
    $('#address').val(address);
    $('#id').val(id)
 });

        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>


