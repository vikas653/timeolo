<!DOCTYPE html>
<html lang="en">
  <!-- [Head] start -->

  @include('admin.commons.header_lib');
  
<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 May 2024 06:47:33 GMT -->
<!-- @include('admin/commons/header_lib'); -->
  <!-- [Head] end -->
  <!-- [Body] Start -->
<style>
/* Fix dropdown hover inside table */
.table .dropdown {
  position: relative;
}

.table .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%; /* show below the button */
  left: 0;
  z-index: 1000;
  min-width: 160px;
  padding: 0.5rem 0;
  margin: 0;
  font-size: 1rem;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: 0.25rem;
  box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
}

/* Show dropdown on hover */
.table .dropdown:hover .dropdown-menu {
  display: block;
}

/* Ensure buttons don’t overlap */
.table .dropdown-toggle {
  cursor: pointer;
  z-index: 1;
}

/* Prevent container from clipping dropdown */
/* .table-responsive {
  overflow: visible !important;
} */

/* Optional: change z-index of card if needed */
.card {
  z-index: auto;
  position: relative;
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
            
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
              <div class="page-block">
                <div class="row align-items-center">
                  <div class="col-md-12">
                    <ul class="breadcrumb">
                      <li class="breadcrumb-item"><router-link to="/dashboard">Home</router-link></li>
                      <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Timesheet Status</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Timesheet Status</h2>
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
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name" class="form-control" >
                        </div>
                        <div class="col-md-3" style="text-align:right;" >
                         
                            <select class="form-control" name="month" id="" >
                                <option value="">Select a month</option>
                                <option value="January" {{ $month == "January" ? 'selected' : ''}}>January</option>
                                <option value="February" {{ $month == "February" ? 'selected' : ''}}>February</option>
                                <option value="March" {{ $month == "March" ? 'selected' : ''}}>March</option>
                                <option value="April" {{ $month == "April" ? 'selected' : ''}}>April</option>
                                <option value="May" {{ $month == "May" ? 'selected' : ''}}>May</option>
                                <option value="June" {{ $month == "June" ? 'selected' : ''}}>June</option>
                                <option value="July" {{ $month == "July" ? 'selected' : ''}}>July</option>
                                <option value="August" {{ $month == "August" ? 'selected' : ''}}>August</option>
                                <option value="September" {{ $month == "September" ? 'selected' : ''}}>September</option>
                                <option value="October" {{ $month == "October" ? 'selected' : ''}}>October</option>
                                <option value="November" {{ $month == "November" ? 'selected' : ''}}>November</option>
                                <option value="December" {{ $month == "December" ? 'selected' : ''}}>December</option>
                            </select>
                      </div>
                      <div class="col-md-2">
                        <select name="year" id="year" class="form-control">
                          <option value="">Select a Year</option>
                          @foreach ($years as $item)
                              <option value="{{ $item->year }}" {{ request('year') == $item->year ? 'selected' : '' }}>
                                  {{ $item->year }}
                              </option>
                          @endforeach
                        </select>
                        
                      </div>
                      <div class="col-md-2">
                      <select name="status" id="" class="form-control">
                        <option value="">Select a Status</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Rejected</option>

                      </select>

                      </div>
                        <div class="col-md-10" style="text-align: right; margin-top:10px">
                          <button type="submit" class="btn btn-success" >Filter</button>
                          </div>
      
                        <div class="col-md-1" style="text-align:right; margin-top:10px;">
                          <a href="{{ route('timesheet_status_export', ['month' => $month, 'year' =>$year, 'status' => $status]) }}" class="btn btn-primary">
                              Export
                          </a>
                          {{-- <a href="{{ route('timesheet_status_export', ['month' => request('month') ?: '', 'years' => request('year') ?: '', 'status' => request('status') ?: '']) }}" class="btn btn-primary">
                              Export
                          </a> --}}
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
                                        <td class="text-white" >User</td>
                                        <td class="text-white" >Month</td>
                                        <td class="text-white" >Year</td>
                                        <td class="text-white" >Total Hours</td>
                                        <td class="text-white" >Status</td>
                                        <td class="text-white">Approved At</td>
                                       
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                  @php
                                      $startingNumber = ($timesheet_status->currentPage() - 1) * $timesheet_status->perPage() + 1;
                                  @endphp
                                  @foreach($timesheet_status as $key=>$data)
                                  <tr>
                                      <td>{{ $startingNumber + $key }}</td>
                              
                                     
                                      <td id="{{ $data->id }}">{{ $data->user_name }} </td>
                                      <td id="{{ $data->id }}">{{ $data->month }} </td>
                                      <td id="{{ $data->id }}">{{ $data->year }} </td>
                                      <td id="{{$data->id}}"> {{$data->total_hours}}  </td>
                                      <td id="{{ $data->id }}">
                                        @if($data->status == 1)
                                        Approved
                                        @elseif($data->status == 2)
                                        Rejected
                                        @elseif($data->status == 0)
                                        Pending
                                        @else
                                        @endif
                                      </td>
                                      <td>
                                        {{ $data ? $data->approved_at : '—' }}
                                    </td>
                                  <td>
                                    <div class="dropdown">
                                      <button class="btn btn-primary dropdown-toggle" type="button" id="actionMenu{{ $data->id }}">
                                        Actions
                                      </button>

                                      <ul class="dropdown-menu" aria-labelledby="actionMenu{{ $data->id }}">
                                        
                                        @if(!empty($data->attachment))
                                        <li>
                                          <a class="dropdown-item" href="{{ route('timesheet.download_attachment', ['timesheet_id' => $data->timesheet_id, 'user_id' => $data->user_id]) }}" download>
                                            📎 Download Attachment
                                          </a>
                                        </li>
                                        @endif

                                        <li>
                                          <a class="dropdown-item" href="{{ route('timesheet_status_view_report', [$data->timesheet_id, $data->user_id]) }}">
                                            📄 View Report
                                          </a>
                                        </li>

                                        <li>
                                          <form action="{{ route('timesheet_status_update', [$data->timesheet_id, $data->user_id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="dropdown-item">✅ Approve</button>
                                          </form>
                                        </li>

                                        <li>
                                          {{-- <form action="{{ route('timesheet_status_update', [$data->timesheet_id, $data->user_id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="2">
                                            <button type="submit" class="dropdown-item">❌ Reject</button>
                                          </form> --}}
                                           <button type="button" class="dropdown-item my-import2" data-bs-toggle="modal" data-bs-target="#import_excel2_{{ $data->id }}">❌ Reject</button>
                                        </li>

                                      </ul>
                                    </div>
                                  </td>
                                      <div class="modal fade" id="import_excel2_{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h1 class="modal-title fs-5" id="exampleModalLabel">Reject</h1>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                    
                                        <div class="modal-body">
                                    
                                          <form action="{{ route('timesheet_status_update', [$data->timesheet_id, $data->user_id]) }}" method="POST" class="d-inline">
                                          @csrf
                                              <div class="form-group">
                                              <label>Why Reject?</label>
                                              <textarea class="form-control" type="text" name="reason" ></textarea>
                                                                  </div>
                                              <input type="hidden" name="status" value="2">
                                              
                                              <button class="btn btn-primary" type="submit">Reject</button>
                                            
                                          </form>        
                                        </div>
                                      
                                      </div>
                                    </div>
                                  </div>

                                  </tr> 
                                  @endforeach
                              </tbody>
                              
                            </table>
                            {{ $timesheet_status->links() }}
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


