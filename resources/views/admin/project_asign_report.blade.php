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
                      <li class="breadcrumb-item" aria-current="page">Assign Projects Report</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Assign Projects Report</h2>
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
                          <div class="col-md-3" style="text-align: right;">
                              <input type="text" name="search" value="{{ request('search') }}"
                                     placeholder="Search by user name" class="form-control">
                          </div>
                          <div class="col-md-3" style="text-align: right;">
                              <select name="user_id" id="user_id" class="form-control">
                                  <option value="">Select a User</option>
                                  @foreach ($users as $item)
                                      <option value="{{ $item->id }}"
                                              {{ request('user_id') == $item->id ? 'selected' : '' }}>
                                          {{ $item->name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-md-3" style="text-align: right;">
                              <select name="project_id" id="project_id" class="form-control">
                                  <option value="">Select a Project</option>
                                  @foreach ($projects as $item)
                                      <option value="{{ $item->id }}"
                                              {{ request('project_id') == $item->id ? 'selected' : '' }}>
                                          {{ $item->code }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="col-md-1">
                              <button type="submit" class="btn btn-success">Filter</button>
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
                                        <td class="text-white">Project</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                 @php
                                     $startingNumber = ($data->currentPage() - 1) * $data->perPage()+ 1;
                                 @endphp
                                @foreach ($data as $key=>$item)

                              <tr>

                                <td>{{$startingNumber + $key}}</td>
                                <td>{{$item->user_name}}</td>
                                <td>{{$item->project_code}}</td>
                              </tr>
                            @endforeach
                              
                              </tbody>
                              
                            </table>
                            {{ $data->links() }}
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




