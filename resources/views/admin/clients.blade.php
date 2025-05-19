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
                      <li class="breadcrumb-item" aria-current="page">Clients</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Clients</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div><br>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="container">
                <div class="card text-center">
                    <div class="card-header" style="padding: 10px;text-align: right;">
                      <a href="{{route('client_template')}}" class=" btn btn-secondary ">Download Template</a>
                      @if(auth()->user()->role_id == 1)
                      <a href="{{ route('client_list_export') }}" class="btn btn-info">Export</a>
                  @endif
                    <button type="button" class="btn btn-success " data-bs-toggle="modal" rel="" data-bs-target="#import_excel">Import Excel</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                      Add Client
                      </button>
                   
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Name</td>
                                        <td class="text-white" >Created By</td>
                                        <td class="text-white" >Mobile No</td>
                                        <td class="text-white" >Address</td>
                                        <td class="text-white" >Client Manager Name</td>
                                        <td class="text-white" >Client Manager Email</td>
                                        <td class="text-white" >Client Manager Mobile No</td>
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($clients->currentPage() - 1) * $clients->perPage() + 1;
                                @endphp
                                    @foreach($clients as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        <td id="name-{{ $data->id }}" >{{ $data->name }}</td>
                                        <td>
                                            @foreach($users as $user)
                                              @if($data->created_by == $user->id)
                                                {{ $user->name }}
                                              @endif
                                            @endforeach
                                        </td>
                                        <td id="mobile_no-{{ $data->id }}" >{{ $data->mobile_no }}</td>
                                        <td id="address-{{ $data->id }}" >{{ $data->address }}</td>
                                          <td id="approver_name-{{ $data->id }}" >{{ $data->approver_name }}</td>
                                          <td id="approver_email-{{ $data->id }}" >{{ $data->approver_email }}</td>
                                          <td id="approver_phone-{{ $data->id }}" >{{ $data->approver_phone }}</td>
                                          <td>
                                            <a href="{{route('asign_project_client', $data->id)}}" class="btn btn-primary" >
                                              Assign Project
                                            </a>
                                        <button type="button" value="{{ $data->id }}" class="btn btn-success mx-2 edit_btn" data-bs-toggle="modal" data-bs-target="#edit_client">
                                            Edit
                                        </button>
                                        <button type="button" value="{{ $data->id }}" class="btn btn-danger delete_btn" data-bs-toggle="modal" data-bs-target="#delete_client">
                                            Delete
                                        </button>
                                        </td>
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $clients->links() }}
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
<div class="modal fade" id="add_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('add_client') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                        <div class="col">
                            <label for="name">Name</label>
                            <input placeholder="Write your name" name="name" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="mobile_no">Mobile No.</label>
                            <input name="mobile_no" class="form-control" type="number">
                        </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                          <label for="">Client Manager Name</label>
                          <input placeholder="Write client manager name" name="approver_name" class="form-control" type="text" required>
                      </div>
                      <div class="col">
                          <label for="">Client Manager Email</label>
                          <input name="approver_email" class="form-control" type="text" placeholder="Write client manager email">
                          
                      </div>
                  </div><br>
                  <div class="row">
                    <div class="col">
                        <label for="">Client Manager Phone</label>
                        <input placeholder="Write client manager phone" name="approver_phone" class="form-control" type="text" required>
                    </div>
                    
                </div><br>
                    <div class="row">
                        <div class="col">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control" id="" cols="30" rows="5"></textarea required>
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
<div class="modal fade" id="edit_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('edit_client') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
                        <div class="col">
                            <label for="name">Name</label>
                            <input placeholder="Write your name" id="name" name="name" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="mobile_no">Mobile No.</label>
                            <input name="mobile_no" id="mobile_no" class="form-control" type="number" required>
                        </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                          <label for="">Client Manager Name</label>
                          <input placeholder="Write approver name" name="approver_name" class="form-control" id="approver_name" type="text" required>
                      </div>
                      <div class="col">
                          <label for="">Client Manager Email</label>
                          <input name="approver_email" id="approver_email" class="form-control" type="text" placeholder="Write approver email">
                          
                      </div>
                  </div><br>
                  <div class="row">
                    <div class="col">
                        <label for="">Client Manager Number</label>
                        <input placeholder="Write client manager number" name="approver_phone" class="form-control" id="approver_phone" type="text" required>
                    </div>
                 
                </div><br>
                    <div class="row">
                        <div class="col">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control" id="" cols="30" rows="5"></textarea>
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
<div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this client ?<h4>
      </div>
      <form action="{{ route('delete_client') }}" method="post" >
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
      <form action="{{ route('import_client_excel') }}" enctype="multipart/form-data" method="POST">
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
        $('.edit_btn').click(function(){
            var id = $(this).val();
            var name = $('#name-'+id).text();
            var approver_name = $('#approver_name-'+id).text();
            var approver_email = $('#approver_email-'+id).text();
            var approver_phone = $('#approver_phone-'+id).text();


            var mobile_no = $('#mobile_no-'+id).text();
            var address = $('#address-'+id).text();
            $('#name').val(name);
            $('#approver_name').val(approver_name);
            $('#approver_email').val(approver_email);
            $('#approver_phone').val(approver_phone);


            $('#mobile_no').val(mobile_no);
            $('#address').val(address);
            $('#id').val(id);
        });
        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>


