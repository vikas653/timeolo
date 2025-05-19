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
                      <li class="breadcrumb-item" aria-current="page">Projects</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Projects</h2>
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
                      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add_project">
                        Assign Project
                        </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                    Add New Project
                    </button>
                    
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Code</td>
                                        <td class="text-white" >Name</td>
                                        <td class="text-white" >Project Start</td>
                                        <td class="text-white" >Project End</td>
                                        {{-- <td class="text-white" >Billrate</td> --}}
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($project->currentPage() - 1) * $project->perPage() + 1;
                                @endphp
                                    @foreach($project as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                    
                                      
                                        <td id="code-{{ $data->id }}" >{{ $data->code }}</td>
                                        <td id="name-{{ $data->id }}" >{{ $data->name }}</td>
                                        <td id="start-{{ $data->id }}" >{{ $data->start }}</td>
                                        <td id="end-{{ $data->id }}" >{{ $data->end }}</td>
                                        {{-- <td >{{ $data->bill_rate ?? '' }}</td> --}}
                                        <span class="d-none" id="approver_name-{{ $data->id }}">  {{ $data->approver_name }} </span>
                                         
                                        <span class="d-none" id="approver_email-{{ $data->id }}" >{{ $data->approver_email }}</span>
                                        <span class="d-none" id="approver_phone-{{ $data->id }}" >{{ $data->approver_phone }}</span>
                                        <span class="d-none" id="bill_by-{{ $data->id }}" >{{ $data->bill_by }}</span>
                                        {{-- <span class="d-none" id="pay_rate_currency-{{ $data->id }}" >{{ $data->pay_rate_currency }}</span> --}}
                                        <td>
                                        <button type="button" value="{{ $data->id }}" class="btn btn-success mx-2 edit_btn" data-bs-toggle="modal" data-bs-target="#edit_client">
                                            Edit
                                        </button>
                                        {{-- <button type="button" value="{{ $data->id }}" class="btn btn-danger delete_btn" data-bs-toggle="modal" data-bs-target="#delete_client">
                                            Delete
                                        </button> --}}
                                        </td>
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $project->links() }}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('user_project_add') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                       
                        <div class="col">
                            <label for="name">Project Code</label>
                            <input placeholder="Write your project code" name="code" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                          <label for="name">Project Name</label>
                          <input name="name" placeholder="Project Name" class="form-control" type="text">
                      </div>
                    </div><br>
                    <div class="row">
                       
                        <div class="col">
                            <label for="name">Project Start</label>
                            <input placeholder="" name="start" class="form-control" type="date" required>
                        </div>
                        <div class="col">
                          <label for="name">Project End</label>
                          <input name="end" class="form-control" type="date">
                      </div>
                    </div><br>
                    <div class="row">
                       
                        <div class="col">
                            <label for="name">Approver Name</label>
                            <input  name="approver_name" class="form-control" type="text"  placeholder="Write Approver name">
                        </div>
                        <div class="col">
                          <label for="name">Approver Email</label>
                          <input name="approver_email" class="form-control" type="text" placeholder="Write Approver Email">
                      </div>
                    </div><br>
                    <div class="row">
                         
                      <div class="col">
                          <label for="name">Approver Phone</label>
                          <input  name="approver_phone" class="form-control" type="text"  placeholder="Write Approver Phone">
                      </div>
                      {{-- <div class="col">
                        <label for="name">Pay Rate Currency</label>
                        <input  name="pay_rate_currency"  class="form-control" type="text"  placeholder="Write Pay Rate Currency">
                    </div> --}}
                  </div><br>
                   
                
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Save</button>
      </div>
        </form>
    </div>
  </div>
 </div>
</div>

<div class="modal fade" id="add_project" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Choose Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('choose_project') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                       
                        <div class="col">
                            <label for="name">Choose Project</label>
                           <select name="project_id" id="" class="form-control">
                            <option value=""></option>
                            @foreach ($choose_project as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                                
                            @endforeach

                           </select>
                        </div>
                       
                    </div><br>   
                    <div class="row">
                       
                      <div class="col">
                          <label for="name">Start Date</label>
                          <input type="date" name="start" class="form-control">
                      </div>
                      <div class="col">
                        <label for="name">End Date</label>
                        <input type="date" name="end" class="form-control">
                    </div>
                  </div><br>       
                   
                
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Save</button>
      </div>
        </form>
    </div>
  </div>
 </div>
</div>
<!-- Edit Modal -->
<div class="modal fade" id="edit_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('user_project_edit') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
           
            <div class="col">
                <label for="name">Project Code</label>
                <input placeholder="Write your project code" name="code" id="code" class="form-control" type="text" required>
            </div>
            <div class="col">
                <label for="name">Project Name</label>
                <input name="name" placeholder="Project Name" id="name" class="form-control" type="text">
            </div>
        </div><br>
        <div class="row">
           
            <div class="col">
                <label for="name">Project Start</label>
                <input placeholder="" name="start" id="start" class="form-control" type="date" required>
            </div>
            <div class="col">
                <label for="name">Project End</label>
                <input name="end" class="form-control" id="end" type="date">
            </div>
        </div><br>
        <div class="row">
                       
            <div class="col">
                <label for="name">Approver Name</label>
                <input  name="approver_name" id="approver_name" class="form-control" type="text"  placeholder="Write Approver name">
            </div>
            <div class="col">
              <label for="name">Approver Email</label>
              <input name="approver_email" id="approver_email" class="form-control" type="text" placeholder="Write Approver Email">
          </div>
        </div><br>
        <div class="row">
             
          <div class="col">
              <label for="name">Approver Phone</label>
              <input  name="approver_phone" id="approver_phone" class="form-control" type="text"  placeholder="Write Approver Phone">
          </div>
          {{-- <div class="col">
            <label for="name">Pay Rate Currency</label>
            <input  name="pay_rate_currency" id="pay_rate_currency" class="form-control" type="text"  placeholder="Write Pay Rate Currency">
        </div> --}}
      </div><br>
            
                    <input type="hidden" name="id" id="id" />
                
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Update</button>
      </div>
        </form>
    </div>
  </div>
</div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this project ?<h4>
      </div>
      <form action="{{ route('user_project_delete') }}" method="post" >
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

    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 3000);
        $('.edit_btn').click(function(){
            var id = $(this).val();
            var client_id = $('#client_id-'+id).text();
            var code = $('#code-'+id).text();
            var name = $('#name-'+id).text();
            var start = $('#start-'+id).text();
            var end = $('#end-'+id).text();
            // var bill_rate = $('#bill_rate-'+id).text();
            var approver_name = $('#approver_name-'+id).text();
            var approver_email = $('#approver_email-'+id).text();
            var approver_phone = $('#approver_phone-'+id).text();
            // var pay_rate_currency = $('#pay_rate_currency-'+id).text();

            
            $('#client_id').val(client_id);
            $('#code').val(code);
            $('#name').val(name);
            $('#start').val(start);
            $('#end').val(end);
            // $('#bill_rate').val(bill_rate);
            $('#approver_name').val(approver_name);
            $('#approver_email').val(approver_email);
            $('#approver_phone').val(approver_phone);
            // $('#pay_rate_currency').val(pay_rate_currency);

            $('#id').val(id);
        });
        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>


