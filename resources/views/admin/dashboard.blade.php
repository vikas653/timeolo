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
    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                  <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Dashboard</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="container">
                <div class="card text-center">
                    <div class="card-header" style="padding: 10px;text-align:left;">
                    <form action="">
                      <div class="row">
                        <div class="col-md-3" style="text-align:right;">
                            <select class="form-control" name="user" id="">
                                <option value="">Select a user</option>
                                @foreach($users as $data)
                                    <option value="{{ $data['id'] }}" {{ $data['id'] == $user ? 'selected' : ''}}>{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" style="text-align:right;">
                            <select class="form-control"  name="client" id="">
                                <option value="">Select a client</option>
                                @foreach($clients as $data)
                                    <option value="{{ $data['id'] }}" {{ $data['id'] == $client ? 'selected' : ''}}>{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" style="text-align:right;">
                        <select class="form-control" name="month" id="" required>
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
                        <div class="col-md-3" style="text-align:right;">
                            <select class="form-control"  name="year" id="" required>
                                <option value="">Select a year</option>
                                @foreach($years as $data)
                                    <option value="{{ $data['year'] }}" {{ $data['year'] == $year ? 'selected' : ''}}>{{ $data['year'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                        <button type="submit" class="btn btn-success" >Filter</button>
                        </div>
                        <div class="col" style="text-align:right;">
                        
                        <a href="{{ route('timesheet_export_filter',['user' => $user , 'client' => $client , 'month' => $month , 'year' => $year ]) }}" class="btn btn-primary">Export</a>
                       
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
                                        <td class="text-white" >Client</td>
                                        <td class="text-white" >Month</td>
                                          <td class="text-white" >Hours</td>
                                        <td class="text-white" >Task</td>
                                      
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($timesheets->currentPage() - 1) * $timesheets->perPage() + 1;
                                @endphp
                                    @foreach($timesheets as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        <td >{{ $data->user_name }}</td>
                                        <td >{{ $data->client_name }}</td>
                                        <td  >{{ $data->month }}</td>
                                         <td>{{ $data->regular_hours }}</td>
                                        <td>{{ $data->activity }}</td>
                                       
                                    </tr> 
                                    @endforeach 
                                    <tr class="bg-secondary">
                                      <td colspan="4" class="text-white">Total Working Hours</td>
                                      <td class="text-white">{{ $totalWorkingHours }}</td>
                                      <td></td>
                                    </tr>
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
 
    @include('admin.commons.settings')
    <!-- [Page Specific JS] start -->
    @include('admin/commons/footer_lib');
    
  </body>
  <!-- [Body] end -->

<!-- Mirrored from html.phoenixcoded.net/light-able/bootstrap/dashboard/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 May 2024 06:47:39 GMT -->
</html>

<script>
  $(document).ready(function() {
        setTimeout(function() {
            $('#success-alert').fadeOut('slow');
            $('#error-alert').fadeOut('slow');
        }, 2000);
    });
</script>
