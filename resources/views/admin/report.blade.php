@include('admin.commons.header_lib')

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

    <div class="container p-5">
      
      

        @if(auth()->user()->role_id == 1)
        @else
        <form method="post" action="{{ route('generate_report') }}" enctype="multipart/form-data">
            @csrf
            <table id="myTable" class="table table-bordered">
                <thead>
                  <tr>
                   
                  </tr>
                    <tr>
                        <td colspan="1">
                             <a class="btn btn-info" href="{{route('timesheet')}}">
                            Back To Home
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#add_client">
                            Add Client
                            </button>
                             {{-- <button type="button" class="btn btn-secondary my-import" data-bs-toggle="modal"  data-bs-target="#import_excel">
                                        Import Excel
                                        </button>  --}}
                        </td>
                        <td> <a href="{{ route('user_project') }}" class="btn btn-primary">
                        
                          <span class="pc-mtext">Manage Projects</span>
                        </a></td>
                        <td colspan="2">
                          <div style="text-align:center;color:red;"> This is timesheet for {{$monthName}} {{$yearName}}. You should not be able to enter dates outside of {{$monthName}} {{$yearName}}</div>
                       </td>
                       <td>
                       <button type="button" class="btn btn-success my-import2" data-bs-toggle="modal" rel="{{ $records->id }}" data-bs-target="#import_excel2">Import Excel</button>

                       </td>
                       <td >
                       
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#send_email">
                          Email Timesheet
                          </button>
                    </td>
                        <td >
                            <a href="{{ route('user_export',['id' => $records->id ]) }}" class="btn btn-primary" >Export Timesheet</a>&nbsp;
                          
                        </td>
                        <tr>
                          <th>Notes</th>
                          <th colspan="2">Approver Name</th>
                          <th colspan="2">Approver Email</th>
                          <th colspan="2">Attachment</th>

                        </tr>
                    </tr>
                    @if(count($timesheet_remark) != 0 )
                    @foreach ($timesheet_remark as $timesheet )
                      
                  <tr>
                    <td>
                      <input type="text" placeholder="Notes" id="{{ $timesheet->id }}" value="{{$timesheet->notes}}" name="notes" class="form-control" >
                    </td>
                    <td colspan="2">
                      <input type="text" placeholder="Approver Name" id="{{$timesheet->id}}" value="{{$timesheet->approver_name}}" class="form-control" name="approver_name" >
                    </td>
                    <td colspan="2" >
                      <input type="text" name="approver_email" id="{{$timesheet->id}}" value="{{$timesheet->approver_email}}" placeholder="Approver Email" class="form-control">
                    </td>
                    <td colspan="2">
                      <input type="file" name="attachment" placeholder="notes" class="form-control" >
                    </td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                    <td><input type="text" placeholder="Notes" name="notes" class="form-control"></td>
                    <td colspan="2"><input type="text" placeholder="Approver Name" class="form-control" name="approver_name"></td>
                    <td colspan="2" ><input type="text" name="approver_email" placeholder="Approver Email" class="form-control"></td>
                    <td colspan="2"><input type="file" name="attachment" placeholder="notes" class="form-control"></td>
                  </tr>
                  @endif
                    <tr>
                        <th>Client</th>
                        <th>Date</th>
                        <th style="width: 250px">Activity</th>
                        <th>Hours</th>
                     
                        <th>Project Code</th>
                        <th>Billable Check Box</th>


                        <th><button type="button" class="btn btn-success" onclick="addNewRow(this)"><i class="ph-duotone ph-plus"></i></button></th>
                    </tr>
                   
                </thead>
                <tbody>
                    @if(count($reports) != 0 )
                    @foreach($reports as $report)
                        <tr class="firstRow">
                            <td>
                                <select class="form-control client-select" name="client[]" required>
                                    <option value="">Select a client</option>
                                    @foreach($clinets as $data)
                                    <option value="{{ $data->id }}" {{ $data->id == $report->client_id ? 'selected' : '' }} >{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="date[]" required style="width: 140px">
                                    <option value="">Select a date</option>
                                    @foreach($dates as $data)
                                        <option value="{{ $data }}" {{ \Carbon\Carbon::parse($report->date)->format('m/d/Y') == $data ? 'selected' : '' }}>{{ $data }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td >
                                <input type="text"  class="form-control" value="{{ $report->activity }}" name="activity[]" required  >
                            </td>
                            <td>
                                <input class="form-control" value="{{ $report->regular_hours }}" name="regular_hours[]" step="any" type="number" required >
                            </td>
                          
                            <td>
                              <select class="form-control" name="code[]" required style="width: 140px">
                                <option value="">Select Project</option>
                                @foreach($project_asign as $data)
                                <option value="{{ $data->id }}" {{ $data->id == $report->code ? 'selected' : '' }} >{{ $data->code }}</option>
                                @endforeach
                            </select>
                                {{-- <input class="form-control code" value="{{$report->code}}" name="code[]"  type="text" readonly > --}}
                            </td>                             
                            <td>
                                <label class="switch">
                                  <input type="checkbox" class="status-checkbox" data-id="{{ $report->id }}" name="billable[]" value="1" {{ $report->billable ? 'checked' : '' }}>
                                  <span class="slider"></span>
                                </label>
                              </td>
                              
                                                                          

                            <td>
                                <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class="ph-duotone ph-x"></i></button>
                                <button type="button" class="btn btn-success" onclick="addNewRow1(this)"><i class="ph-duotone ph-plus"></i></button>
                              
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr class="firstRow">
                        <td>
                            <select class="form-control client-select" name="client[]" required>
                                <option value="">Select a client</option>
                                @foreach($clinets as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control" name="date[]" required style="width: 140px">
                                <option value="">Select a date</option>
                                @foreach($dates as $data)
                                    <option value="{{ $data }}">{{ $data }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="activity[]" required >
                        </td>
                        <td>
                            <input class="form-control" name="regular_hours[]" step="any" type="number" required>
                        </td>
                        {{-- <td>
                            <input class="form-control approver_name" name="approver_name[]" type="text" readonly>
                        </td>
                        <td>
                            <input class="form-control approver_email" value=""
                            name="approver_email[]" type="email" readonly>
                        </td> --}}
                        <td>
                          <select class="form-control" name="code[]" required style="width: 140px">
                            <option value="">Select Project</option>
                           
                        @foreach($project_asign as $data)
                        <option value="{{ $data->id }}">{{ $data->code }}</option>
                        @endforeach
                        </select>
                        </td>
                        <td>
                            <label class="switch">
                              <input type="checkbox" class="status-checkbox" name="billable[]" value="1">
                              <span class="slider"></span>
                            </label>
                          </td>
                          
                                        
                          
                        <td>
                            <button type="button" class="btn btn-danger" onclick="deleteRow(this)"><i class="ph-duotone ph-x"></i></button>
                            <button type="button" class="btn btn-success" onclick="addNewRow1(this)"><i class="ph-duotone ph-plus"></i></button>
                        </td>
                    </tr>
                    @endif


                  

                    <tr id="last_row">
                      <td align="center" colspan="2">
                        
                      </td>
                      <td align="center" colspan="1">
                        Total Hours
                      </td>
                      <td align="center" colspan="1">
                        {{$regular_hours}}
                      </td>
                        <td align="center" colspan="1">
                            <button class="btn btn-success">Submit </button>
                        </td>
                    </tr>
                   
                </tbody>
                <tfoot>
                     <tr>
                        <td colspan="9">
                            
                            Notes:
                           <?php echo $records->notes;?>
                        </td>
                    </tr>
                </tfoot>
            </table>  
            
                <input type="hidden" value="{{ $records->id }}" name="timesheet_id" > 
        </form>
        @endif
    </div>
    <input type="hidden" value="{{ $id }}" id="unique_id">
    @include('admin/commons/footer_lib');

    <div class="modal fade" id="send_email" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Send Email</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form action="{{ route('timesheet_email',['id' => $records->id ]) }}">
            @csrf
                        <div class="row">
                            <div class="col">
                                <label for="name">Email</label>
                                <input placeholder="Write email where you want to send this report" name="email" class="form-control" type="text" required>
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
<!-- Add  Modal -->
<div class="modal fade" id="add_client" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="add_clients">
        @csrf
                    <div class="row">
                        <div class="col">
                            <label for="name">Name</label>
                            <input placeholder="Write your name" name="name" class="form-control" type="text" required>
                        </div>
                        <div class="col">
                            <label for="mobile_no">Mobile No.</label>
                            <input name="mobile_no" class="form-control" type="number">
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col">
                            <label for="">Approver Name</label>
                            <input placeholder="Write approver name" name="approver_name" class="form-control" id="approver_name" type="text" required>
                        </div>
                        <div class="col">
                            <label for="">Approver Email</label>
                            <input name="approver_email" id="approver_email" class="form-control" type="text" placeholder="Write approver email">
                            
                        </div>
                    </div><br>
                    <div class="row">
                       
                    <div class="row">
                        <div class="col">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control" id="" cols="30" rows="5"></textarea required>
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


     <!-- Add  Modal -->
<div class="modal fade" id="import_excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <input type="hidden" value="{{ $records->id }}" name="timesheet_id" > 
            
            <button class="btn btn-primary" type="submit">Upload</button>
          </div>
           
        </form>
          
                
    
    </div>
  </div>
 </div>
</div>

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
            <input type="hidden" value="{{ $records->id }}" name="timesheet_id" id="timesheet_id2" > 
            
            <button class="btn btn-primary" type="submit">Upload</button>
           
        </form>
          
                
      </div>
    
    </div>
  </div>
</div>
<script>
$('form').on('submit', function() {
  $('.status-checkbox').each(function() {
    if (!$(this).is(':checked')) {
      $(this).after('<input type="hidden" name="billable[]" value="0">');
    }
  });
});


</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    document.querySelectorAll('select[name="date[]"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                const dateObj = new Date(selectedDate);
                // getDay() - Sunday = 0, Monday=1 ... Saturday=6
                if (dateObj.getDay() === 0) {
                    alert('Warning: Sunday date is not allowed to select!');
                    
                    this.value = '';
                }
            }
        });
    });
});
</script>

<script>
   $(document).ready(function() {

    $('.status-checkbox').on('change', function() {
        const isChecked = $(this).is(':checked') ? 1 : 0;
        const reportId = $(this).data('id');
    
        if (!reportId) return;
    
        $.ajax({
          url: '{{ route("update.status") }}',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            id: reportId,
            billable: isChecked
          },
          success: function(response) {
            if (response.success) {
              console.log('Status updated successfully');
            } else {
              console.error('Failed to update status');
            }
          },
          error: function(xhr) {
            console.error('Error updating status:', xhr.responseText);
          }
        });
      });


  $(document).on('change', '.client-select', function() {
    const clientId = $(this).val();
    const row = $(this).closest('tr');

    if (!clientId) {
      row.find('.approver_name').val('');
      row.find('.approver_email').val('');
      row.find('.code').val('');
      return;
    }

    $.ajax({
      url: `{{ route('get-client-details', ':id') }}`.replace(':id', clientId),
      method: 'GET',
      success: function(response) {
        row.find('.approver_name').val(response.approver_name);
        row.find('.approver_email').val(response.approver_email);
      },
      error: function() {
        alert('Client not found or error occurred');
        row.find('.approver_name').val('');
        row.find('.approver_email').val('');
      }
    });

   
  //   $.ajax({
  //     url: `{{ route('get-project-details', ':id') }}`.replace(':id', clientId),
  //     method: 'GET',
  //     success: function(response) {
  //       row.find('.code').val(response.code);
  //     },
  //     error: function() {
  //       alert('Approver Name not found or error occurred');
  //       row.find('.code').val('');
  //     }
  //   });
  });
   });
</script>

<script>

    function deleteRow(button) {
        var row = button.closest('tr');
        row.parentNode.removeChild(row);
    }

function addNewRow() {
    var id = $('#unique_id').val();
    var formData = {
        'id' : id
    };
    $.ajax({
        type: 'GET',
        url: '{{ route('add.row') }}',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#myTable tbody tr:last').before(response.newRow);
                console.log('New row added:', response.newRow);
            } else {
                console.error('Failed to add row.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error adding row:', error);
        }
    });
}

$(document).ready(function() {
    window.addNewRow1 = function(button) {
        var row = $(button).closest('tr');
        var cloned = row.clone();

        cloned.find('select[name="client[]"]').val(row.find('select[name="client[]"]').val());
        cloned.find('select[name="date[]"]').val(row.find('select[name="date[]"]').val());

        cloned.find('select[name="activity[]"]').val(row.find('select[name="activity[]"]'));
        cloned.find('select[name="regular_hours[]"]').val(row.find('select[name="regular_hours[]"]'));
      

        $('#last_row').before(cloned);
    }
});



    $(document).ready(function() {
            setTimeout(function() {
                $('#success-alert').fadeOut('slow');
                $('#error-alert').fadeOut('slow');
            }, 10000);

            $('#add_clients').submit(function(event) {
                event.preventDefault();
                
                var formData = $(this).serialize();
                formData += '&_token=' + '{{ csrf_token() }}';

                $.ajax({
                    type: 'POST',
                    url: '{{ route('add.client') }}', 
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#add_client').modal('hide');
                        } else {
                            console.error('Failed to add row.');
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        console.error('Error submitting form:', error);
                    }
                });
            });


        });
</script>

<style>
    .switch {
  position: relative;
  display: inline-block;
  width: 54px;
  height: 30px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  border-radius: 20px;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 20px;
  left: 3px;
  bottom: 4px;
  background-color: white;
  border-radius: 50%;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:checked + .slider:before {
  transform: translateX(24px);
}

  </style>
