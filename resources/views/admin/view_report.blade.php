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
                      <li class="breadcrumb-item" aria-current="page">Report</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Timesheet Report</h2>
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>User</th>
                                        <th>Working Hours</th>
                                        <th>Action</th>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @foreach($reports as $data)
                                <tr>
                                    <td>
                                        {{ $data->user->name }}
                                    </td>
                                    <td>
                                        {{ $data->total_regular_hours }}
                                    </td>
                                    <td>
                                        <a href="{{ route('show_user_report',['user_id' => $data->user_id , 'id' => $data->timesheet_id ]) }}" class="btn btn-info">View</a>
                                        <a href="{{ route('user_export_admin',['id' => $data->user_id, 'timesheet_id' => $data->timesheet_id ]) }}" class="btn btn-primary" >Export Report</a>&nbsp;
                                        <a href="{{ route('user_report_delete',['id' => $data->user_id, 'timesheet_id' => $data->timesheet_id ]) }}" class="btn btn-danger" >Delete</a>
                                       
                                    </td>
                                    
                                </tr>
                            @endforeach
                                </tbody>
                            </table>
                            <!--  -->
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