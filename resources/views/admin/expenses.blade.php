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
                      <li class="breadcrumb-item" aria-current="page">Expenses</li>
                    </ul>
                  </div>
                  <div class="col-md-12">
                    <div class="page-header-title">
                      <h2 class="mb-0">Expenses</h2>
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
                      {{-- <a href="{{route('client_template')}}" class=" btn btn-secondary ">Download Template</a>
                      @if(auth()->user()->role_id == 1)
                      <a href="{{ route('client_list_export') }}" class="btn btn-info">Export</a>
                  @endif --}}
                    {{-- <button type="button" class="btn btn-success " data-bs-toggle="modal" rel="" data-bs-target="#import_excel">Import Excel</button> --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client">
                      Add Expenses
                      </button>
                   
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td class="text-white" >S No.</td>
                                        <td class="text-white" >Destination</td>
                                        <td class="text-white" >Amount</td>
                                        <td class="text-white" >Travel Date</td>
                                        <td class="text-white" >Purpose</td>
                                        <td class="text-white"> Receipts</td>
                                        <td class="text-white">Status</td>
                                        <td class="text-white" >Action</td>
                                    </tr>	 	 	 	
                                </thead>
                                <tbody>
                                @php
                                    $startingNumber = ($expenses->currentPage() - 1) * $expenses->perPage() + 1;
                                @endphp
                                    @foreach($expenses as $key=>$data)
                                    <tr>
                                        <td>{{ $startingNumber + $key }}</td>
                                        <td id="destination-{{ $data->id }}" >{{ $data->destination }}</td>
                                       
                                        <td id="amount-{{ $data->id }}" >{{ $data->amount }}</td>
                                        <td id="travel_date-{{ $data->id }}" >{{ $data->travel_date }}</td>
                                          <td id="purpose-{{ $data->id }}" >{{ $data->purpose }}</td>
                                          <td>
                                            <img src="{{ url('public/' . $data->receipts) }}" alt="image" width="100">



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


<!-- Add  Modal -->
<div class="modal fade" id="add_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('add_expenses') }}" enctype="multipart/form-data" method="POST">
        @csrf
                    <div class="row">
                        <div class="col">
                            <label for="name">Destination</label>
                            <input placeholder="Write your destination" name="destination" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="travel_date">Travel Date</label>
                            <input name="travel_date" class="form-control" type="date">
                        </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                          <label for="">Amount</label>
                          <input placeholder="Write amount" name="amount" class="form-control" type="text" required>
                      </div>
                      <div class="col">
                          <label for="">Purpose</label>
                          <input name="purpose" class="form-control" type="text" placeholder="Write your purpose">
                          
                      </div>
                  </div><br>
                  <div class="row">
                    <div class="col">
                        <label for="">Receipts</label>
                        <input placeholder="Write receipts" name="receipts" class="form-control" type="file" required>
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Expenses</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="{{ route('edit_expenses') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
                        <div class="col">
                            <label for="name">Destination</label>
                            <input placeholder="Write your destination" id="destination" name="destination" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="travel_date">Travel Date</label>
                            <input name="travel_date" id="travel_date" class="form-control" type="date" required>
                        </div>
                    </div><br>
                    <div class="row">
                      <div class="col">
                          <label for="">Amount</label>
                          <input placeholder="Write amount" name="amount" class="form-control" id="amount" type="text" required>
                      </div>
                      <div class="col">
                          <label for="">Purpose</label>
                          <input name="purpose" id="purpose" class="form-control" type="text" placeholder="Write purpose">
                          
                      </div>
                  </div><br>
                 
                 
                      <div class="row">
                        <div class="col">
                            <label for="receipts">receipts</label>
                            <input name="receipts" id="receipts" class="form-control" type="file">
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

<div class="modal fade" id="import_excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{-- <form action="{{ route('import_client_excel') }}" enctype="multipart/form-data" method="POST"> --}}
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
            var destination = $('#destination-'+id).text();
            var travel_date = $('#travel_date-'+id).text();
            var amount = $('#amount-'+id).text();
            var purpose = $('#purpose-'+id).text();
            var receipts = $('#receipts-'+id).text();
         
            $('#destination').val(destination);
            $('#travel_date').val(travel_date);
            $('#amount').val(amount);
            $('#purpose').val(purpose);
            $('#receipts').val(receipts);
          
            $('#id').val(id);
        });
        $('.delete_btn').click(function(){
            var id = $(this).val();
            $('#delete_id').val(id);
        });
    });
</script>


