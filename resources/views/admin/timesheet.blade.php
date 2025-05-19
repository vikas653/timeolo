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
                      <li class="breadcrumb-item" aria-current="page">Timesheet</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Timesheet</h2>
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
                    @if(auth()->user()->role_id == 1)
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_blogs">
                      Generate Link
                      </button>
                     
                    @else

                    @endif
                    </div>
                    <div class="card-body">
                      <p style="color:red;">Note:Use the Timesheet Template for Excel uploads.
                        ✅ Select all values from the dropdowns in the file.
                        ⚠️ Manual edits may cause the upload to fail.
                        Go to Actions → Import Excel when ready.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Month</td>
                                        <td class="text-white" >Year</td>
                                        <td class="text-white" >Total Hours</td>
                                        @if(auth()->user()->role_id == 3)
                                        <td class="text-white">Status</td>
                                        <td class="text-white">Approved At</td>
                                    @endif
                                        <td class="text-white" >Action</td>
                                       

                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($timesheets->currentPage() - 1) * $timesheets->perPage() + 1;
                                @endphp
                                    @foreach($timesheets as $key=>$data)
                                    @php
                                    $hours = \App\Models\TimesheetReport::where(['user_id' => auth()->user()->id, 'timesheet_id' => $data->id])->sum('regular_hours');
                                    @endphp
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        <td >{{ $data->month }}</td>
                                        <td >{{ $data->year }}</td>
                                        <td>{{ auth()->user()->role_id == 1 ? $data->total_hours : $hours }}</td>
                                        @if(auth()->user()->role_id == 3)
                                        <td>
                                          @php
                                              $approval = \App\Models\TimesheetApproval::where([
                                                  'user_id' => auth()->user()->id,
                                                  'timesheet_id' => $data->id
                                              ])->first();
                                          @endphp
                                      
                                          @if ($approval)
                                              @if ($approval->status == 1)
                                                  Approved
                                              @elseif ($approval->status == 2)
                                                  Rejected
                                              @elseif ($approval->status == 0)
                                                  Pending
                                              @else
                                                  Unknown
                                              @endif
                                          @else
                                              Not Submitted
                                          @endif
                                      </td>
                                      <td>
                                        {{ $approval ? $approval->approved_at : '—' }}
                                    </td>
                                    
                                      @endif
                                      
                                            
                                        <td>
                                          <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                              Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                              @if(auth()->user()->role_id == 1)
                                              <input type="hidden" id="description-{{ $data->id }}" value="{{ $data->notes }}"/>
                                              <li><a href="javascript:void(0)" rel="{{ $data->id }}" data-bs-toggle="modal" data-bs-target="#edit_record" class="dropdown-item btn btn-warning btn-sm">Edit</a></li>
                                              <li><a href="{{ route('export_complete',['id' => $data->id ]) }}" class="dropdown-item btn btn-primary btn-sm">Export Report</a></li>
                                              <li><a href="#" data-bs-toggle="modal" data-bs-target="#show_link" class="dropdown-item btn btn-success btn-sm copy_link" rel="{{ route('report',['id' => $data->unique_id]) }}">Copy Link</a></li>
                                              <li><button type="button" value="{{ $data->id }}" class="dropdown-item btn btn-danger btn-sm delete_btn" data-bs-toggle="modal" data-bs-target="#delete_blogs">Delete</button></li>
                                              <li><a href="{{ route('show_report',['id' => $data->id]) }}" class="dropdown-item btn btn-info btn-sm">View</a></li>
                                              <li><a href="{{ route('download',['id' => $data->id ])}}" class="dropdown-item btn btn-secondary btn-sm">Download Template</a></li>
                                              <li>
                                                <button type="button" class="dropdown-item btn btn-primary btn-sm my-import" data-bs-toggle="modal" rel="{{ $data->id }}" data-bs-target="#import_excel">Import Excel</button>
                                              </li>
                                              @else
                                              <li><a href="{{ route('report',['id' => $data->unique_id]) }}" class="dropdown-item btn btn-success btn-sm">Add Timesheet Data</a></li>
                                              <form action="{{ route('timesheet_approval',['id'=>$data->id])}}" method="POST" >
                                                @csrf
                                                <input type="hidden" name="status" value="0">
                                                <li><button type="submit" class="dropdown-item btn btn-success btn-sm">Send For Approval </button></li>
                                              </form>
                                           
                                              <li><a href="{{ route('user_export',['id' => $data->id ]) }}" class="dropdown-item btn btn-primary btn-sm">Export Report</a></li>
                                              <li><a href="{{ route('show_user_report',['user_id' => auth()->user()->id ,'id' => $data->id ]) }}" class="dropdown-item btn btn-info btn-sm">View</a></li>
                                              <li><button type="button" value="{{ $data->id }}" class="dropdown-item btn btn-danger btn-sm delete_btn" data-bs-toggle="modal" data-bs-target="#delete_blogs">Delete</button></li>
                                              <li><a href="{{ route('download',['id' => $data->id ])}}" class="dropdown-item btn btn-secondary btn-sm">Download Timesheet Template</a></li>
                                              <li>
                                               <button type="button" class="dropdown-item btn btn-primary btn-sm my-import3" data-bs-toggle="modal" rel="{{ $data->id }}" data-bs-target="#import_excel3">Copy Data</button>
                                              </li>
                                              <li>
                                                <button type="button" class="dropdown-item btn btn-primary btn-sm my-import2" data-bs-toggle="modal" rel="{{ $data->id }}" data-bs-target="#import_excel2">Import Timesheet from Excel</button>
                                              </li>
                                              @endif
                                            </ul>
                                          </div>
                                        </td>
                                        
                                       
                                    </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $timesheets->links() }}
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
        <div class="modal fade" id="import_excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('import_excel') }}" enctype="multipart/form-data" method="POST">
        @csrf
      <div class="modal-body">
            <div class="form-group">
              <label>Select User</label>
              <select name="user_id" class="form-control" required>
                <?php
                foreach($users as $user)
                {
                  echo "<option value='".$user->id."'>".$user->name."</option>";
                }
                ?>
              </select>
            </div>
            <br/>
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


<!-- Add  Modal -->
<div class="modal fade" id="add_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Generate Timesheet</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('add_timesheet') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                        <div class="col">
                            <label for="address">Month</label>
                            <select class="form-control" name="month" id="" required>
                                <option value="">Select a month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="address">Year</label>
                            <select class="form-control" name="year" id="" required>
                                <option value="">Select a year</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                               
                            </select>
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

<!-- Delete Modal -->
<div class="modal fade" id="delete_blogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Timesheet Delete</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this Timesheet ?<h4>
      </div>
      <form action="{{ route('delete_timesheet') }}" method="post" >
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

<!-- Delete Modal -->
<div class="modal fade" id="show_link" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">Link</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="timesheet_link">
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
     
      </div>
    </div>
  </div>
</div>

   <!-- Add  Modal -->
   <div class="modal fade" id="import_excel2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
   
      <div class="modal-body">
  
   
      <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label>Select Excel File</label>
            <input class="form-control" type="file" name="file" >
                                </div>
            <input type="hidden" value="" name="timesheet_id" id="timesheet_id2" > 
            
            <button class="btn btn-primary" type="submit">Upload</button>
           
        </form>        
      </div>
    
    </div>
  </div>
</div>
<div class="modal fade" id="import_excel3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Copy Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
   
      <div class="modal-body">
  
   
      <form action="{{ route('copy_timesheet_data') }}" method="POST" enctype="multipart/form-data">
            @csrf
           <div class="form-group row">
            <div class="col-lg-6">
                   <label for="address">Month</label>
                            <select class="form-control" name="month" id="" required>
                                <option value="">Select a month</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                            </select>
            </div>
            
            <div class="col-lg-6">
                <label>Select Data From Year</label>
                <select name="year" id="" class="form-control">
                  <option value="">Select a Year</option>
                @foreach ($year as $item)
                              <option value="{{ $item->year }}" {{ request('year') == $item->year ? 'selected' : '' }}>
                                  {{ $item->year }}
                              </option>
                              @endforeach
                </select>
            </div>
          </div>

            <input type="hidden" value="" name="timesheet_id" id="timesheet_id3" > 
            
            <button class="btn btn-primary" type="submit">copy</button>
           
        </form>        
      </div>
    
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="edit_record" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">Update Record</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="col">
      <form action="{{ route('timesheet.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
                            <label for="description">Notes</label>
                            <textarea name="description" id="edit_description" placeholder="Write a description" rows="5" cols="10" ></textarea><br/>
                            <input type="hidden" value="" name="timesheet_id" id="edit_timesheet_id" > 
                            <button class="btn btn-primary" type="submit">Submit</button>
                            
                            &nbsp;<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
           
                        </div>
              </form>
      </div>
      
    </div>
  </div>
</div>

<script>
 CKEDITOR.replace('edit_description');

    $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 30000);
        $('.delete_btn').click(function(){
                    var id = $(this).val();
                    $('#delete_id').val(id);
                });

                $(".copy_link").click(function(){
                  var link = $(this).attr("rel");
                  $("#timesheet_link").html(link)
                });

                $(".edit_record").click(function(){
                  var id = $(this).attr("rel");
                  $("#edit_timesheet_id").val(id);
                  var description = $("#description-"+id).val();
                  CKEDITOR.instances['edit_description'].setData(description);
                });
                
            });
</script>

<script>
       $(document).ready(function() {

        $(".my-import").click(function(){

          var timesheet_id = $(this).attr("rel");
          $("#timesheet_id").val(timesheet_id);

        });

        $(".my-import2").click(function(){
        var timesheet_id = $(this).attr("rel");
        $("#timesheet_id2").val(timesheet_id);});

        $(".my-import3").click(function() {
        var timesheet_id = $(this).attr("rel");
        $("#timesheet_id3").val(timesheet_id);
    });
       });
   </script>