@include('admin.commons.header_lib')

    <div class="container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Activity</th>
                            <th>Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $data)
                            <tr>
                                <td>
                                    {{ $data->user->name }}
                                </td>
                                <td>
                                    {{ $data->client->name }}
                                </td>
                                <td>
                                    {{ $data->date }}
                                </td>
                                <td>
                                    {{ $data->activity }}
                                </td>
                                <td>
                                    {{ $data->regular_hours }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4">Total hours</td>
                            <td>{{ $total_hours }}</td>
                        </tr>
                    </tbody>
                </table> 
    </div>

