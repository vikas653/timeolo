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
                      <li class="breadcrumb-item" aria-current="page">Expenses Status</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Expenses Status</h2>
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
                        {{-- <div class="col-md-3" style="text-align:right;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name" class="form-control" >
                        </div>
                      
                        <div class="col-md-1">
                        <button type="submit" class="btn btn-success" >Filter</button>
                        </div> --}}
                        <div class="col-md-8" style="text-align:right;">
                        
                          {{-- <a href="{{route('project_assign_download')}}" class=" btn btn-secondary ">Download Template</a>
                          <button type="button" class="btn btn-primary " data-bs-toggle="modal" rel="" data-bs-target="#import_excel">Import Excel</button>
                         
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                            Assign Project
                            </button>  --}}
       
                       
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
                                        <td class="text-white" >Created By</td>
                                        <td class="text-white" >Destination</td>
                                        <td class="text-white" >Amount</td>
                                        <td class="text-white" >Travel date</td>
                                        <td class="text-white">Purpose</td>
                                        <td class="text-white">Receipts</td>
                                        <td class="text-white">Status</td>

                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                  @php
                                      $startingNumber = ($expenses->currentPage() - 1) * $expenses->perPage() + 1
                                  @endphp
                                  @foreach ($expenses as $key=>$data)
                                      <tr>
                                        <td>{{$startingNumber + $key}}</td>
                                        <td>{{$data->user->name}}</td>
                                        <td id="destination{{$data->id}}">{{$data->destination}}</td>
                                        <td id="amount{{$data->id}}">{{$data->amount}}</td>
                                        <td id="travel_date{{$data->id}}">{{$data->travel_date}}</td>
                                        <td id="purpose{{$data->id}}">{{$data->purpose}}</td>
                                        <td><img src="{{asset ('public/' . $data->receipts )}}" alt="img" width="100">
                                        </td>
                                        <td>
                                       @if($data->status == 0)
                                       Pending
                                       @elseif($data->status == 1)
                                       Approved
                                       @elseif($data->status == 2)
                                       Rejected
                                       @else
                                       @endif
                                      </td>
                                      <td>
                                        <div style="display: flex; gap: 10px;">
                                          {{-- <a href="{{ route('download.receipt', $data->id) }}" class="btn btn-success">
                                            Download Receipt
                                        </a> --}}
                                        
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $data->id }}">
                                            View Receipt
                                        </button>
                                            <form action="{{ route('status_update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="btn btn-primary">
                                                    Approve
                                                </button>
                                            </form>
                                    
                                            <form action="{{ route('status_update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-danger">
                                                    Reject
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-info edit_btn" data-bs-toggle="modal" data-bs-target="#editModal" value="{{$data->id}}">
                                              Edit
                                          </button>
                                          <button type="button" value="{{ $data->id }}" class="btn btn-dark delete_btn" data-bs-toggle="modal" data-bs-target="#delete_client">
                                            Delete
                                        </button>
                                        </div>
                                    </td>
                                    
                                      </tr>
                                      <div class="modal fade" id="receiptModal{{ $data->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $data->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="receiptModalLabel{{ $data->id }}">Receipt for {{ $data->destination }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset('public/' . str_replace('public/', '', $data->receipts)) }}" alt="Receipt" style="width: 100%;">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  @endforeach
                                </tbody>
                              
                                         
                                
                              
                            </table>
                            {{ $expenses->links() }}
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
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="receiptModalLabel">Edit Expense </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <form action="{{route('edit_expenses')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
             <div class="col">
               <label for="">Destination</label>
               <input type="text" name="destination" id="destination" class="form-control">
             </div>
             <div class="col">
               <label for="">Amount</label>
               <input type="text" name="amount" id="amount" class="form-control">
             </div>
            </div>
            <br>
            <div class="row">
             <div class="col">
               <label for="">Travel Date</label>
               <input type="date" name="travel_date" id="travel_date" class="form-control">
             </div>
             <div class="col">
               <label for="">Purpose</label>
               <input type="text" name="purpose" id="purpose" class="form-control">
             </div>
            </div>
            <br>
            <div class="row">
             <div class="col">
               <label for="">Image</label>
               <input type="file" name="receipts" id="image" class="form-control">
             </div>
            
            </div>
         </div>
         <div class="modal-footer">
           <input type="hidden" name="id" id="id">
           <button type="submit" class="btn btn-success" name="submit">Update</button>
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </div>
        </form>
      </div>
  </div>
</div>

<div class="modal fade" id="delete_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Expenses</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Are you really want to delete this Expense ?<h4>
      </div>
      <form action="{{ route('delete_expenses') }}" method="post" >
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

    $('.edit_btn').click(function() {
        var id = $(this).val();

        var destination = $('#destination' + id).text();
        var amount = $('#amount' + id).text();
        var travel_date = $('#travel_date'+id).text();
        var purpose = $('#purpose'+id).text();


        $('#destination').val(destination);
        $('#amount').val(amount);
        $('#travel_date').val(travel_date);
        $('#purpose').val(purpose);

        $('#id').val(id);
    });

    $('.delete_btn').click(function() {
        var id = $(this).val();
        $('#delete_id').val(id);
    });
});

</script>


