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
                      <li class="breadcrumb-item" aria-current="page">Assign Projects To Client</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Assign Projects To Client</h2>
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
                            {{-- <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name" class="form-control" > --}}
                        </div>
                      
                        <div class="col-md-1">
                        {{-- <button type="submit" class="btn btn-success" >Filter</button> --}}
                        </div>
                        <div class="col-md-8" style="text-align:right;">
                        
                          {{-- <a href="{{route('project_assign_download')}}" class=" btn btn-secondary ">Download Template</a>
                          <button type="button" class="btn btn-primary " data-bs-toggle="modal" rel="" data-bs-target="#import_excel">Import Excel</button> --}}
                         
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                            Assign Project
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
                                        {{-- <td class="text-white" >Project</td> --}}
                                        <td class="text-white" >Client</td>
                                        <td class="text-white" >Project</td>

                                        <td class="text-white" >Start Date</td>
                                        <td class="text-white" >End Date</td>
                                       
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                  <tbody>
                                  @php
                                      $startingNumber = ($asign->currentPage() - 1) * $asign->perPage() + 1;
                                  @endphp
                                  @foreach($asign as $key=>$data)
                                  <tr>
                                      <td>{{ $startingNumber + $key }}</td>
                              
                                      <!-- Store project_id and user_id in data attributes -->
                                      {{-- <td id="project_id-{{ $data->id }}" data-id="{{ $data->project->id ?? '' }}">
                                          {{ $data->project->code ?? '' }}
                                      </td> --}}
                                      <td id="user_id-{{ $data->id }}" >
                                          {{ $data->user->name }}
                                      </td>
                                      <td id="project_id-{{ $data->id }}" data-id="{{ $data->project->id }}">
                                        {{ $data->project->name }}
                                    </td>
                                      <td id="start-{{ $data->id }}" >{{ $data->start }}</td>
                                      <td id="end-{{ $data->id }}" >{{ $data->end }}</td>
                                      <td>
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
                            {{ $asign->links() }} 
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Assign Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('client_asign_add', ['id'=> $user->id]) }}" method="POST">
        @csrf
                    <div class="row">
                       
                       
                        <div class="col">
                            <label for="name">Project</label>
                            <select name="project_id" id="" class="form-control">
                                <option value=""></option>
                                @foreach ($project as $projects)
                                <option value="{{ $projects->id }}">
                                  {{ $projects->name }}
                              </option>
                            @endforeach
                            
                            </select>
                        </div>
                    </div><br>
                    <div class="row">
                       
                      <div class="col">
                          <label for="name">Start Date</label>
                         <input type="date" name="start" class="form-control">
                          
                          </select>
                      </div>
                      <div class="col">
                          <label for="name">End Date</label>
                        <input type="date" name="end" class="form-control">
                          
                          </select>
                      </div>
                  </div><br>
                   
                
      <div class="modal-footer">
        {{-- <input type="hidden" name="project_id" value="{{ $project->id }}"> --}}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Asign Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('client_asign_edit') }}" enctype="multipart/form-data" method="POST">
        @csrf
      <div class="row">

        <div class="col">
          <label for="name">Project</label>
          <select name="project_id" id="project_id" class="form-control">
            <option value="">-- Select Project --</option>
            @foreach ($project as $projects)
            <option value="{{ $projects->id }}" {{ old('project_id') == $projects->id ? 'selected' : '' }}>
              {{ $projects->name }}
            </option>
          @endforeach
        </select>
        
      </div>
      </div><br>
      <div class="row">
                       
        <div class="col">
            <label for="name">Start Date</label>
           <input type="date" name="start" id="start" class="form-control">
            
            </select>
        </div>
        <div class="col">
            <label for="name">End Date</label>
          <input type="date" name="end" id="end" class="form-control">
            
            </select>
        </div>
    </div><br>
    {{-- <input type="hidden" name="project_id" value="{{ $project->id }}"> --}}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Assign Project</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this assign project ?<h4>
      </div>
      <form action="{{ route('client_asign_delete') }}" method="post" >
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
      {{-- <form action="{{ route('import_project_assign_excel') }}" enctype="multipart/form-data" method="POST"> --}}
        @csrf
      <div class="modal-body">
           
            <div class="form-group">
            <label>Select Excel File</label>
            <input class="form-control" type="file" required name="file">
            </div>
           
            <input type="hidden" value="" name="timesheet_id" id="timesheet_id" > 
          
                
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
        var project_id = $('#project_id-' + id).data('id');

        var start = $('#start-'+id).text();
        var end = $('#end-'+id).text();


        $('#project_id').val(project_id);
        $('#start').val(start);
        $('#end').val(end);

        $('#id').val(id);
    });

    $('.delete_btn').click(function() {
        var id = $(this).val();
        $('#delete_id').val(id);
    });
});

</script>


