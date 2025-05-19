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
                      <li class="breadcrumb-item" aria-current="page">Type of Employments</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Type of Employments</h2>
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
                       
                        <div class="col-md-12" style="text-align:right;">
                        
                         
                         
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                            Add Employment
                            </button> 
       
                       
                        </div>
                      </div>
                    </form>
                    </div>
                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Type of Employment</td>
                                       
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($get->currentPage() - 1) * $get->perPage() + 1;
                                @endphp
                                    @foreach($get as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        
                                      
                                        <td id="code-{{ $data->id }}" >{{ $data->name }}</td>
                                       
                                       <td>
                                        <button type="button" value="{{ $data->id }}" class="btn btn-danger delete_btn" data-bs-toggle="modal" data-bs-target="#delete_client">
                                            Delete
                                        </button>
                                        </td>
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $get->links() }}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Type of Employment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('employment_add') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                       
                        <div class="col">
                            <label for="name">Employment Name</label>
                           <input type="text" class="form-control" name="name">
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





<!-- Delete Modal -->
<div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Type of Employment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this type of Employment ?<h4>
      </div>
      <form action="{{ route('employment_delete') }}" method="post" >
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
       
        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>




