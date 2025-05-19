<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Vendors;
use App\Models\Terms;
use App\Models\Project;
use App\Models\Employment;
use App\Models\SystemAccess;
use App\Exports\ProjectExport;
use App\Exports\ProjectAssignExport;
use App\Models\ProjectAsign;
use App\Models\Timesheet;
use App\Models\TimesheetReport;
use App\Models\TimesheetNotes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\TimesheetReportExportByFilter;
use App\Exports\TimesheetReportExport;
use App\Exports\TimesheetReportExport3;
use App\Exports\TimesheetExport;
use App\Exports\TimesheetExportByFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use App\Exports\ClientExport;
use App\Exports\InvoiceExport;
use App\Exports\BillExport;
use Illuminate\Support\Facades\Log;

use App\Exports\UserListExport;
use App\Exports\UserCreateExport;
use App\Exports\ClientListExport;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\SendMail;
use Mail;

class HomeController extends Controller
{
    public function dashboard(Request $data)
    {
        
            $user = isset($data->user) && !empty($data->user) ? $data->user : '0';
            $client = isset($data->client) && !empty($data->client) ? $data->client : '0';
            $month = isset($data->month) && !empty($data->month) ? $data->month : '0';
            $year = isset($data->year) && !empty($data->year) ? $data->year : '0';
            if(auth()->user()->role_id == 1 ){
            
        if($data->user != '' && $data->client == '')
        {
            $monthName = $data['month']; 
            $monthNumber = Carbon::parse("1 $monthName")->format('n');

            $totalWorkingHours = TimesheetReport::where('user_id',$data['user'])->whereMonth('date', $monthNumber)->sum('regular_hours');

            $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                     ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->where('timesheet_reports.user_id', '=', $data->user)
                    ->where('timesheets.month', '=' , $data->month)
                    ->where('timesheets.year', '=' , $data->year)
                     ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours'
                        
                    )
                   // ->groupBy('timesheet_reports.timesheet_id') 
                    ->paginate(10);

            $total_records = count($timesheets);
            $filter = "yes";
           
        } else if($data->user == '' && $data->client != ''){
             $monthName = $data['month']; 
            $monthNumber = Carbon::parse("1 $monthName")->format('n');

            $totalWorkingHours = TimesheetReport::where('client_id',$data['client'])->whereMonth('date', $monthNumber)->sum('regular_hours');

            $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                     ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    ->where('timesheet_reports.client_id', '=', $data->client)
                    ->where('timesheets.month', '=' , $data->month)
                    ->where('timesheets.year', '=' , $data->year)
                     ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours'
                        
                    )
                  //  ->groupBy('timesheet_reports.timesheet_id') 
                    ->paginate(10);

            $total_records = count($timesheets);
            $filter = "yes";
        }
        else{
            $totalWorkingHours = TimesheetReport::sum('regular_hours');
            
            $timesheets = DB::table('timesheets')
                    ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                     ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                    // ->where('timesheets.month', '=' , $data->month)
                    // ->where('timesheets.year', '=' , $data->year)
                   ->select(
                        'users.name as user_name',
                        'B.name as client_name',
                        'timesheets.month', 
                        'timesheet_reports.activity',
                        'timesheet_reports.regular_hours'
                        
                    )
                   // ->groupBy('timesheet_reports.timesheet_id') 
                    // ->groupBy('timesheet_reports.user_id') 
                    ->paginate(10);
            $total_records = count($timesheets);
            $filter = "no";
          
        }
       
        $clients = User::where('role_id',2)->get();
        $users = User::where('role_id',3)->get();
        $years = Timesheet::select('year')->groupBy('year')->get();

    }else{
        $clients = User::where('role_id',2)->get();
        $timesheets = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
        ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
         ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
         ->where('timesheet_reports.user_id', '=', auth()->user()->id)
        ->where('timesheets.month', '=' , $data->month)
        ->where('timesheets.year', '=' , $data->year)
         ->select(
            'users.name as user_name',
            'B.name as client_name',
            'timesheets.month', 
            'timesheet_reports.activity',
            'timesheet_reports.regular_hours'
            
        )
      //  ->groupBy('timesheet_reports.timesheet_id') 
        ->paginate(10);
        $years = Timesheet::select('year')->groupBy('year')->get();
      
        $users = [];
        $total_records = 0;
        $totalWorkingHours = 0;
        $filter = "no";
    }
        return view('admin/dashboard',['timesheets' => $timesheets ,'year' => $year,'years' => $years, 'clients' => $clients , 'users' => $users ,'user' =>$user,'client' => $client,'month' =>$month, 'total_records' => $total_records ,'totalWorkingHours' => $totalWorkingHours,'filter' => $filter]);
    }
    // public function Invoice(Request $data)
    // {
    //     $user = !empty($data->user) ? $data->user : '0';
    //     $client = !empty($data->client) ? $data->client : '0';
    //     $month = !empty($data->month) ? $data->month : '0';
    //     $year = !empty($data->year) ? $data->year : '0';
    
    //     if (auth()->user()->role_id == 1) {
    //         $query = DB::table('timesheets')
    //             ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
    //             ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
    //             ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
    //             ->join('project as C', 'timesheet_reports.code', '=', 'C.id')
    //             ->join('project_asign as D', 'C.id', '=', 'D.project_id')
    //             ->join('terms as E', 'C.terms_id', '=', 'E.id');
    
    //         if ($month != '0') {
    //             $query->where('timesheets.month', '=', $month);
    //         }
    //         if ($year != '0') {
    //             $query->where('timesheets.year', '=', $year);
    //         }
    //         if ($user != '0') {
    //             $query->where('D.user_id', '=', $user)
    //                   ->where('timesheet_reports.user_id', '=', $user);
    //         }
    //         if ($client != '0') {
    //             $query->where('timesheet_reports.client_id', '=', $client);
    //         }
    
    //         $query->select(
    //             'users.name as user_name',
    //             'B.name as client_name',
    //             'timesheets.month',
    //             'timesheets.year',
    //             'E.name as terms_name',
    //             'C.code as project_code',
    //             'C.name as project_name',
    //             'timesheet_reports.regular_hours',
    //             'C.bill_rate as project_bill_rate',
    //             DB::raw('timesheet_reports.regular_hours * C.bill_rate as total_amount'),
    //             'C.bill_rate_unit as project_bill_rate_unit',
    //         );
    
    //         $timesheets = $query->paginate(10);
        
    //         foreach ($timesheets as $timesheet) {
    //             $monthNumber = Carbon::parse("1 {$timesheet->month}")->format('n');
    //             $firstDay = Carbon::createFromDate($timesheet->year, $monthNumber, 1)->format('Y/m/d');
    //             $lastDay = Carbon::createFromDate($timesheet->year, $monthNumber)->endOfMonth()->format('Y/m/d');
    //             $timesheet->first_day = $firstDay;
    //             $timesheet->last_day = $lastDay;
    //         }
    //     }
    
    //     $clients = User::where('role_id', 2)->get();
    //     $users = User::where('role_id', 3)->get();
    //     $years = Timesheet::select('year')->groupBy('year')->get();
    
    //     return view('admin/invoice', [
    //         'timesheets' => $timesheets,
    //         'year' => $year,
    //         'years' => $years,
    //         'clients' => $clients,
    //         'users' => $users,
    //         'user' => $user,
    //         'client' => $client,
    //         'month' => $month,
    //     ]);
    // }
    public function Invoice(Request $data)
{
    $user = !empty($data->user) ? $data->user : '0';
    $client = !empty($data->client) ? $data->client : '0';
    $month = !empty($data->month) ? $data->month : '0';
    $year = !empty($data->year) ? $data->year : '0';

    $query = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
        ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
        ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
        ->join('project as C', 'timesheet_reports.code', '=', 'C.id')
        ->leftJoin('project_asign as D', function ($join) {
            $join->on('C.id', '=', 'D.project_id')
                 ->on('timesheet_reports.user_id', '=', 'D.user_id');
        }) 
        ->join('terms as E', 'C.terms_id', '=', 'E.id')
        ->select(
            'users.name as user_name',
            'B.name as client_name',
            'timesheets.month',
            'timesheets.year',
            'E.name as terms_name',
            'C.code as project_code',
            'C.name as project_name',
            DB::raw('SUM(timesheet_reports.regular_hours) as regular_hours'),
            'C.bill_rate as project_bill_rate',
            DB::raw('timesheet_reports.regular_hours * C.bill_rate as total_amount'),
            'C.bill_rate_unit as project_bill_rate_unit'
        )
        ->distinct(); 

    if ($month != '0') {
        $query->where('timesheets.month', '=', $month);
    }
    if ($year != '0') {
        $query->where('timesheets.year', '=', $year);
    }
    if ($user != '0') {
        $query->where('timesheet_reports.user_id', '=', $user);
    }
    if ($client != '0') {
        $query->where('timesheet_reports.client_id', '=', $client);
    }

    $query->groupBy(
        'users.name',
        'B.name',
        'timesheets.month',
        'timesheets.year',
        'E.name',
        'C.code',
        'C.name',
        
        'C.bill_rate',
        'C.bill_rate_unit'
    );

    $timesheets = $query->paginate(10);

    foreach ($timesheets as $timesheet) {
        $monthNumber = Carbon::parse("1 {$timesheet->month}")->format('n');
        $firstDay = Carbon::createFromDate($timesheet->year, $monthNumber, 1)->format('Y/m/d');
        $lastDay = Carbon::createFromDate($timesheet->year, $monthNumber)->endOfMonth()->format('Y/m/d');
        $timesheet->first_day = $firstDay;
        $timesheet->last_day = $lastDay;
    }

    $clients = User::where('role_id', 2)->get();
    $users = User::where('role_id', 3)->get();
    $years = Timesheet::select('year')->groupBy('year')->get();

    return view('admin/invoice', [
        'timesheets' => $timesheets,
        'year' => $year,
        'years' => $years,
        'clients' => $clients,
        'users' => $users,
        'user' => $user,
        'client' => $client,
        'month' => $month,
    ]);
}

    
            

public function Bill(Request $data)
{
    $user = !empty($data->user) ? $data->user : '0';
    $client = !empty($data->client) ? $data->client : '0';
    $month = !empty($data->month) ? $data->month : '0';
    $year = !empty($data->year) ? $data->year : '0';

    if (auth()->user()->role_id !== 1) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    try {
        $monthName = $data['month'] ?? 'January'; 
        $monthNumber = Carbon::parse("1 $monthName")->month;
        $firstDay = Carbon::createFromDate($year, $monthNumber, 1)->format('Y/m/d');
        $lastDay = Carbon::createFromDate($year, $monthNumber)->endOfMonth()->format('Y/m/d');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Invalid month input');
    }

    $query = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
        ->join('users as consultant', 'timesheet_reports.user_id', '=', 'consultant.id')
        ->join('users as client', 'timesheet_reports.client_id', '=', 'client.id')
        ->join('project', 'timesheet_reports.code', '=', 'project.id')
        ->join('project_asign', 'project.id', '=', 'project_asign.project_id')
        ->join('terms', 'project.terms_id', '=', 'terms.id')
        ->join('vendors', 'consultant.id', '=', 'vendors.user_id')
        ->select(
            'vendors.company_name as vendor_name',
            'consultant.name as user_name',
            'consultant.address as user_address',
            'consultant.location as user_location',
            'client.name as client_name',
            'timesheets.month',
            'timesheets.year',
            'terms.name as terms_name',
            'project.code as project_code',
            'project.name as project_name',
            'timesheet_reports.regular_hours',
            'project.pay_rate_currency as project_pay_rate_currency',
            DB::raw('(timesheet_reports.regular_hours * project.pay_rate_currency) as total_amount'),
            'project.bill_rate_unit as project_bill_rate_unit'
        )
        ->distinct(); 

    if ($user !== '0') {
        $query->where('project_asign.user_id', '=', $user)
              ->where('timesheet_reports.user_id', '=', $user);
    }

    if ($client !== '0') {
        $query->where('timesheet_reports.client_id', '=', $client);
    }

    if ($month !== '0' && $year !== '0') {
        $query->where('timesheets.month', '=', $month)
              ->where('timesheets.year', '=', $year);
    }

    $timesheets = $query->paginate(10);

    foreach ($timesheets as $timesheet) {
        try {
            $monthNumber = Carbon::parse("1 {$timesheet->month}")->month;
            $timesheet->first_day = Carbon::createFromDate($timesheet->year, $monthNumber, 1)->format('Y/m/d');
            $timesheet->last_day = Carbon::createFromDate($timesheet->year, $monthNumber)->endOfMonth()->format('Y/m/d');
        } catch (\Exception $e) {
            $timesheet->first_day = null;
            $timesheet->last_day = null;
        }
    }

    $clients = User::where('role_id', 2)->get();
    $users = User::where('role_id', 3)->get();
    $years = Timesheet::select('year')->groupBy('year')->get();

    return view('admin/bill', [
        'timesheets' => $timesheets,
        'year' => $year,
        'years' => $years,
        'clients' => $clients,
        'users' => $users,
        'user' => $user,
        'client' => $client,
        'month' => $month,
    ]);
}


// ------------------------------------------- Register/Login Section -----------------------------------------

public function Register_view(Request $request)
{
    return view('admin.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|unique:users,email',
        'mobile_no' => 'required',
        'password' => 'required',
    ]);

    if (User::where('email', $request->email)->exists()) {
        return redirect()->back()->with('error', 'Email already exists. Please log in.');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'mobile_no' => $request->mobile_no,
        'password' => Hash::make($request->password),
        'role_id' => 3, 
    ]);

    if ($user) {
        Auth::login($user);

        return redirect()->intended(auth()->user()->role_id == 1 ? '/' : '/timesheet')
                         ->with('success', 'Registration successful! Welcome.');
    }

    return redirect()->back()->with('error', 'Registration failed. Please try again.');
}


    
    public function Login_view()
    {
        return view('admin.login');
    }
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        if(auth()->user()->role_id == 1 ){
            return redirect()->intended('/');
        }
        else{
            return redirect()->intended('/timesheet');
        }
    } else {
        return redirect('/login')->with('error', 'Invalid email or password');
    }
}
    public function Logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }

    public function Change_password()
    {
        return view('admin.change_password');
    }
    public function Update_password(Request $data)
    {
        User::where('email',session('email'))->update([
            'password' => md5($data['password'])
        ]);
        return $this->dashboard();
    }

    public function forget_list(){
        return view('admin.forget_password');
    }


    // public function forget_action(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:users',
    //     ]);
    
    //     $token = Str::random(64);
    
    //     DB::table('password_reset_tokens')->insert([
    //         'email' => $request->email,
    //         'token' => $token,
    //         'created_at'=> Carbon::now()
    //     ]);
    
    //     try {
    //         Mail::send('admin.mailforget', ['token' => $token], function($message) use ($request){
    //             $message->to($request->email);
    //             $message->subject('Reset Password');
    //         });
    //     } catch (\Exception $e) {
    //         Log::error("Mail sending failed: " . $e->getMessage());
    //         return back()->with('error', 'Unable to send email. Please try again.');
    //     }
    
    //     return redirect()->back()->with('message', 'We have sent reset instructions to your email.');
    // }
    
    public function forget_action(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
    
        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->orderByDesc('created_at')
            ->first();
    
        if ($existingToken && Carbon::parse($existingToken->created_at)->addMinutes(60)->isFuture()) {
            return redirect()->back()->with('error', ['A reset link was already sent recently. Please check your email or try again after some time.']);

        }
    
        $token = Str::random(64);
    
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );
    
        $to = $request->email;
        $from = "Timeolo";
        $subject = "Reset Your Password";
        $resetLink = route('mailforget', ['token' => $token]);
    
        $message_body = "
            <html>
            <head><title>Reset Your Password</title></head>
            <body>
                <p>Hello,</p>
                <p>We received a request to reset your password. Click the link below to proceed:</p>
                <p><a href='$resetLink'>Reset Password</a></p>
                <p>If you didn’t request this, you can ignore this email.</p>
                <br>
                <p>Regards,<br>Timeolo</p>
            </body>
            </html>
        ";
    
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: Timeolo <$from>\r\n";
        $headers .= "Reply-To: $from\r\n";
    
        if (mail($to, $subject, $message_body, $headers)) {
            return redirect()->back()->with('success', ['We have sent password reset instructions to your email. It may take 1 to 2 minutes to arrive.']);
        } else {
            return redirect()->back()->with('error', ['Unable to send email. Please try again.']);

        }
    }
    
        public function showresetpasswordform($token){
            return view('admin.new_password', ['token'=> $token]);
        }

    public function updateresetpassword(Request $request){
        $request->validate([
            'email' => 'required',
            'password'=>'required',
            'conform_password' =>'required',
        ]);
        $updatepassword = DB::table('password_reset_tokens')
        ->where([
           'email' => $request->email,
           'token' => $request->token,

        ])->first();
        if($updatepassword){
            return back()->withInput()->with('error', 'reset link has expired!');
        }
        $user = User::where('email', $request->email)
        ->update(['password'=> Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=>$request->email])->delete();

        return redirect('login')->with('success', 'your password has changed');
    }


// ------------------------------------------- Clients Section -------------------------------------------

    public function Clients()
    {
        if(auth()->user()->role_id == 1)
        {
            $clients = User::where('role_id',2)->paginate(10);
        }
        else{
            $clients = User::where('role_id',2)->where('created_by',auth()->user()->id)->paginate(10);
        }
        $users = User::get();
        return view('admin.clients',['clients' => $clients,'users' => $users]);
    }
    public function Add_client(Request $request)
    {
        
        $data = [
            'name' => $request['name'],
            'mobile_no' => $request['mobile_no'],
            'address' => $request['address'],
            'approver_name' => $request->input('approver_name'),
            'approver_email' => $request->input('approver_email'),
            'approver_phone' => $request->input('approver_phone'),

            'role_id' => 2,
            'created_by' => auth()->user()->id
        ];
        User::create($data);
        return redirect()->back()->with('success','Client Added Successfuly !');
    }
    public function Addclient(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'mobile_no' => $request->input('mobile_no'),
            'address' => $request->input('address'),
            'approver_name' => $request->input('approver_name'),
            'approver_email' => $request->input('approver_email'),
            'approver_phone' => $request->input('approver_phone'),

            // 'email' => $request->input('email'),
            'role_id' => 2,
            'created_by' => auth()->user()->id
        ];
        User::create($data);
        return response()->json(['success' => true]);
    }
    public function edit_client(Request $request)
    {
        $data = [
            'name' => $request['name'],
            'mobile_no' => $request['mobile_no'],
            'address' => $request['address'],
            'approver_name' => $request->input('approver_name'),
            'approver_email' => $request->input('approver_email'),
            'approver_phone' => $request->input('approver_phone'),

            'role_id' => 2
        ];

        User::where('id',$request['id'])->update($data);
        return redirect()->back()->with('success','Client updated successfuly');
    }
    public function Delete_client(Request $request)
    {
        User::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Client deleted successfuly');
    }

    // ------------------------------------------------------- User section ------------------------------------------------------ 

    public function Users(Request $request)
    {
        $clients = User::where('role_id', 2)->get();
        $query = User::where('role_id', 3);
    
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        // Filter by user
        if ($request->has('user') && !empty($request->user)) {
            $query->where('id', $request->user);
        }
    
        $users = $query->paginate(20);
        $filter = User::where('role_id', 3)
            ->orderByRaw('LOWER(name) ASC')
            ->get()
            ->map(function ($user) {
                $user->name = strtolower($user->name);
                return $user;
            });


        $employment= Employment::get();
        $system_access= SystemAccess::get();
            $vendor = Vendors::get();
        return view('admin.users', ['users' => $users, 'clients' => $clients, 'filter' => $filter,'employment'=>$employment , 'system_access'=>$system_access , 'vendor'=>$vendor]);
    }
    
    public function Add_User(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'work_email' => 'nullable|email|unique:users,work_email',
        ], [
            'email.unique' => 'This email is already registered.',
            'work_email.unique' => 'This work email is already registered.',
        ]);
    
        $data = [
            'name' => $request['name'],
            'mobile_no' => $request['mobile_no'],
            'address' => $request['address'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role_id' => 3,
            'pay_rate' => $request['pay_rate'],
            'currency' => $request['currency'],
            'bank_details' => $request['bank_details'],
            'job_title' => $request['job_title'],
            'dob' => $request['dob'],
            'ssn' => $request['ssn'],
            'employment_id' => $request['employment_id'],
            'system_access_id' => $request['system_access_id'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'bank_name' => $request['bank_name'],
            'bank_branch_address' => $request['bank_branch_address'],
            'account_holder_name' => $request['account_holder_name'],
            'bank_account_number' => $request['bank_account_number'],
            'swift_bic_code' => $request['swift_bic_code'],
            'iban_number' => $request['iban_number'],
            'bank_routing_number' => $request['bank_routing_number'],
            'pay_rate_currency' => $request['pay_rate_currency'],
            'work_email' => $request['work_email'],
            'location' => $request['location'],
        ];
    
        User::create($data);
        return redirect()->back()->with('success', 'User Added Successfully!');
    }
    
    
    public function edit_User(Request $request)
{
    // Validate input
    $request->validate([
        'email' => 'required|email|unique:users,email,' . $request->id,
        'work_email' => 'nullable|email|unique:users,work_email,' . $request->id,
    ], [
        'email.unique' => 'This email is already registered.',
        'work_email.unique' => 'This work email is already registered.',
    ]);

    $data = [
        'name' => $request['name'],
        'mobile_no' => $request['mobile_no'],
        'address' => $request['address'],
        'email' => $request['email'],
        'work_email' => $request['work_email'],
        'pay_rate' => $request['pay_rate'],
        'currency' => $request['currency'],
        'job_title' => $request['job_title'],
        'dob' => $request['dob'],
        'ssn' => $request['ssn'],
        'employment_id' => $request['employment_id'],
        'system_access_id' => $request['system_access_id'],
        'start_date' => $request['start_date'],
        'end_date' => $request['end_date'],
        'bank_name' => $request['bank_name'],
        'bank_branch_address' => $request['bank_branch_address'],
        'account_holder_name' => $request['account_holder_name'],
        'bank_account_number' => $request['bank_account_number'],
        'swift_bic_code' => $request['swift_bic_code'],
        'iban_number' => $request['iban_number'],
        'bank_routing_number' => $request['bank_routing_number'],
        'pay_rate_currency' => $request['pay_rate_currency'],
        'location' => $request['location'],
        'role_id' => 3
    ];

    if (!empty($request['password'])) {
        $data['password'] = Hash::make($request['password']);
    }

    User::where('id', $request['id'])->update($data);
    return redirect()->back()->with('success', 'User updated successfully');
}

    
    public function Delete_User(Request $request)
    {
        User::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','User deleted successfuly');
    }
  
    // -------------------------------------------------- Timesheet section ------------------------------------------------

    public function Timesheet()
    {
            $timesheets = Timesheet::paginate(10);
            $total_hours = TimesheetReport::where('user_id',auth()->user()->id)->sum('regular_hours');
            $users = User::where('role_id',3)->get();
            return view('admin.timesheet',['timesheets' => $timesheets,'total_hours' => $total_hours, 'users' => $users]);
    }
    public function Add_Timesheet(Request $data)
    {
       
        $check = Timesheet::where('month',$data['month'])->where('year',$data['year'])->first();
        if(isset($check['unique_id'])){
            return redirect()->back()->with('error','Timesheet for this month is already generated !');
        }else{
            Timesheet::create([
                'month' => $data['month'],
                'year' => $data['year'],
                'unique_id' => Str::random(8)
            ]); 
            return redirect()->back()->with('success','Timesheet generated successfuly');
        }
        
    }
    public function Delete_Timesheet(Request $data)
    {
        if(auth()->user()->role_id == 1){
          Timesheet::where('id',$data['id'])->delete();
          TimesheetReport::where('timesheet_id',$data['id'])->delete();
        }else{
            TimesheetReport::where('timesheet_id',$data['id'])->where('user_id',auth()->user()->id)->delete();
        }
        $this->updateReport($data['id']);
        return redirect()->back()->with('error','Timesheet deleted successfuly');
    }

    public function Delete_User_Timesheet($user_id, $timesheet_id)
    {
        TimesheetReport::where('timesheet_id',$timesheet_id)->where('user_id',$user_id)->delete();
        return redirect()->back()->with('error','Timesheet deleted successfuly');
    }


    
   
    // -------------------------------------------------------- Report section ---------------------------------------------

    // public function Report($id)
    // { 
    //     if(auth()->user()) {  
    //         $record = Timesheet::where('unique_id', $id)->first();
            
    //         // Ensure the correct year is used
    //         $year = $record->year;  // Get year from Timesheet instead of using date('Y')
    
    //         $reports = TimesheetReport::where('user_id', auth()->user()->id)
    //                     ->where('timesheet_id', $record->id)
    //                     ->get();
    
    //         $timesheet_remark = TimesheetNotes::where('user_id', auth()->user()->id)
    //                             ->where('timesheet_id', $record->id)
    //                             ->get();
    
    //         $monthName = $record->month;
    //         $yearName = $record->year;  // This is now correctly set
    
    //         $daysInMonth = Carbon::createFromDate($year, Carbon::parse("1 $monthName")->month)->daysInMonth;
    
    //         $dates = [];
    
    //         for ($day = 1; $day <= $daysInMonth; $day++) {
    //             $date = Carbon::create($year, Carbon::parse("1 $monthName")->month, $day);
    //             $dates[] = $date->format('Y/m/d');  
    //         }
    
    //         $clinets = User::where('role_id', 2)->get();
    //         $user = auth()->id();
    //         $project = ProjectAsign::where('user_id', $user)->pluck('project_id');
    //         $project_asign = Project::whereIn('id', $project)->get();
    
    //         return view('admin.report', [
    //             'records' => $record,
    //             'dates' => $dates,
    //             'clinets' => $clinets,
    //             'reports' => $reports,
    //             'id' => $id,
    //             'monthName' => $monthName,
    //             'yearName' => $yearName,
    //             'project_asign' => $project_asign,
    //             'timesheet_remark' => $timesheet_remark
    //         ]);
    //     } else {
    //         return redirect('/login');
    //     }
    // }
    public function Report($id)
    { 
        if(auth()->user())
        {
            
     
        $record = Timesheet::where('unique_id',$id)->first();
        $reports = TimesheetReport::where('user_id',auth()->user()->id)->where('timesheet_id',$record->id)->get();
        $timesheet_remark = TimesheetNotes::where('user_id', auth()->user()->id)
                                    ->where('timesheet_id', $record->id)
                                    ->get();
        

        $monthName = $record->month;
        $yearName = $record->year;
        // $year = date('Y');


        $daysInMonth = Carbon::createFromDate($yearName, Carbon::parse("1 $monthName")->month)->daysInMonth;

        $dates = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = Carbon::create($yearName, Carbon::parse("1 $monthName")->month, $day, 0, 0, 0);
            
            $formattedDate = $date->format('Y/m/d');
            
            $dates[] = $formattedDate;
        }
        $clients = User::where('role_id',2)->get();
        $user = auth()->id();
                $project = ProjectAsign::where('user_id', $user)->pluck('project_id');
                $project_asign = Project::whereIn('id', $project)->get();
        return view('admin.report',['records' => $record,'dates' => $dates,'clinets' => $clients, 'reports' => $reports, 'id' => $id, 'monthName' => $monthName, 'yearName' => $yearName,
    'project_asign' => $project_asign , 'timesheet_remark' => $timesheet_remark]);
        }
        else{
             return redirect('/login');
        }
    }


    // --------------- Generate Report -----------------------
    public function Report_generate(Request $data)
    {

        try {
            DB::beginTransaction();
    
            $records = TimesheetReport::where('user_id', auth()->user()->id)->where('timesheet_id', $data->timesheet_id)->get();
            
            $hours = 0;
            foreach($records as $row){
                $hours = $hours + $row->regular_hours;
            }
            
            TimesheetReport::where('user_id', auth()->user()->id)->where('timesheet_id', $data->timesheet_id)->delete(); 
        
            $old_hours = Timesheet::select('total_hours')->where('id',$data->timesheet_id)->first();
            $total_hours = 0;
            for($i = 0;$i < count($data->date); $i++){
                $record = Array(
                    "client_id" => $data->client[$i],
                    "user_id" => auth()->user()->id,
                    "date" => date("Y/m/d",strtotime($data->date[$i])),
                    "activity" => $data->activity[$i],
                    "regular_hours" => $data->regular_hours[$i],
                    "timesheet_id" => $data->timesheet_id,
                  
                    "code" => $data->code[$i],
                    "billable" => $data->billable[$i] ?? 0,

                );

                TimesheetReport::create($record);
                $total_hours += $data->regular_hours[$i];
            }
            $final_hours = $total_hours+$old_hours['total_hours'] - $hours;
            Timesheet::where('id',$data->timesheet_id)->update(['total_hours' => $final_hours]);
            
        $validatedRemark = $data->validate([
            'notes' => 'nullable',
            'attachment' => 'nullable|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,xlsx|max:2048',
            'approver_name' => 'nullable',
            'approver_email' => 'nullable',
        ]);

        if ($data->hasFile('attachment')) {
            $path = $data->file('attachment')->store('attachments', 'public');
            $validatedRemark['attachment'] = $path;
        }

        $validatedRemark['user_id'] = auth()->user()->id;
        $validatedRemark['timesheet_id'] = $data->timesheet_id;

        TimesheetNotes::updateOrCreate(
            ['user_id' => auth()->user()->id, 'timesheet_id' => $data->timesheet_id],
            $validatedRemark
        );
            DB::commit();
    
            return redirect()->back()->with('success','Timesheet report generated successfuly');
            
        } catch (\Exception $e) {
            DB::rollback();
    
            return "Error: " . $e->getMessage();
        }

    }

    public function Update_Timesheet(Request $request)
    {
        $description = $request->description;
        Timesheet::where('id',$request->timesheet_id)->update(['notes' => $description]);
        return redirect()->back()->with('success','Timesheet updated successfuly');
    
    }
    // --------------- Show Report -----------------------
    public function ShowReport($id)
    {
        $data = Timesheet::where('id',$id)->first();
        if(auth()->user()->role_id == 1){
            $record = TimesheetReport::select('user_id','timesheet_id', DB::raw('SUM(regular_hours) as total_regular_hours'))
            ->where('timesheet_id', $id)
            ->whereHas('user') 
            ->groupBy('user_id') 
            ->get();
            $total_hours = TimesheetReport::where('timesheet_id',$id)->sum('regular_hours');
        }
        else{
            $record = TimesheetReport::where('timesheet_id',$id)->where('user_id',auth()->user()->id)->whereHas('client')->whereHas('user')->get();
            $total_hours = TimesheetReport::where('timesheet_id',$id)->where('user_id',auth()->user()->id)->sum('regular_hours');
        }
        return view('admin.view_report',['reports' => $record,'timesheet' => $data,'total_hours' => $total_hours, 'my_timesheet_id'=>$id]);
    }

    // --------------- Show Report By User -----------------------
    public function ShowUserReport($user_id,$id)
    {
        $record = TimesheetReport::where('user_id',$user_id)->where('timesheet_id',$id)->whereHas('client')->whereHas('user')->get();
        $total_hours = TimesheetReport::where('user_id',$user_id)->where('timesheet_id',$id)->sum('regular_hours');
        return view('admin.user_report',['reports' => $record,'total_hours' => $total_hours]);
    }

    // --------------- Show Report By Both User And Client -----------------------
    public function ShowUserClientReport($user_id,$client_id,$id)
    {
        $record = TimesheetReport::where('user_id',$user_id)->where('client_id',$client_id)->where('timesheet_id',$id)->whereHas('client')->whereHas('user')->get();
        $total_hours = TimesheetReport::where('user_id',$user_id)->where('client_id',$client_id)->where('timesheet_id',$id)->sum('regular_hours');
        return view('admin.user_report',['reports' => $record,'total_hours' => $total_hours]);
    }

    // --------------- TimeSheet Report Export -----------------------
    public function export($id, $timesheet_id)
    {
        return Excel::download(new TimesheetReportExport($id, $timesheet_id), 'TimeSheetReport.xlsx');
    }
    
    public function export_filter($user,$client,$month,$year,$timesheet_id)
    {
        return Excel::download(new TimesheetReportExportByFilter($user,$client,$month,$year,$timesheet_id), 'TimeSheetReport.xlsx');
    }
    public function user_list_export()
    {
        return Excel::download(new UserListExport, 'UserList.xlsx');
    }
    public function client_list_export()
    {
        return Excel::download(new ClientListExport, 'ClientList.xlsx');
    }
    // ---------------------------- Timesheet export --------------------------- 
    public function TimesheetExport()
    {
        return Excel::download(new TimesheetExport(), 'TimeSheet.xlsx');
    }
    public function TimesheetExportFilter($user,$client,$month,$year)
    {
        return Excel::download(new TimesheetExportByFilter($user,$client,$month,$year), 'TimeSheet.xlsx');
    }
    public function invoice_export($user,$client,$month,$year)
    {
        return Excel::download(new InvoiceExport($user,$client,$month,$year), 'Invoice.xlsx');
    }
    public function bill_export($user,$client,$month,$year)
    {
        return Excel::download(new BillExport($user,$client,$month,$year), 'Bill.xlsx');
    }
    // ---------------------------- User export --------------------------- 
    public function user_export($id)
    {
        return Excel::download(new TimesheetReportExport(auth()->user()->id, $id), 'TimeSheetReport.xlsx');
    }

    public function export_complete($id)
    {
        return Excel::download(new TimesheetReportExport3($id), 'TimeSheetReport.xlsx');
    }

    public function user_export_admin($id, $timesheet_id)
    {
        return Excel::download(new TimesheetReportExport($id, $timesheet_id), 'TimeSheetReport.xlsx');
    }

    public function addRow(Request $request)
    {
        $id = $request->id;
        $record = Timesheet::where('unique_id',$id)->first();

        $monthName = $record->month;
        $year = $record->year;

        // $year = date('Y');


        $daysInMonth = Carbon::createFromDate($year, Carbon::parse("1 $monthName")->month)->daysInMonth;

        $dates = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = Carbon::create($year, Carbon::parse("1 $monthName")->month, $day, 0, 0, 0);
            
            $formattedDate = $date->format('Y/m/d');
            
            $dates[] = $formattedDate;
        }
        $clients = User::where('role_id',2)->get();
        $user = auth()->id();
        $project  =ProjectAsign::where('user_id',$user )->pluck('project_id');
        $project_asign = Project::whereIn('id', $project)->get();
        $newRow = '<tr class="firstRow">
            <td>
                <select class="form-control client-select" name="client[]" required>
                    <option value="">Select a client</option>';
        foreach($clients as $data) {
            $newRow .= '<option value="' . $data->id . '">' . $data->name . '</option>';
        }
        $newRow .= '</select>
            </td>
            <td>
                <select class="form-control" name="date[]" required>
                    <option value="">Select a date</option>';
        foreach($dates as $data) {
            $newRow .= '<option value="' . $data . '">' . $data . '</option>';
        }
        $newRow .= '</select>
            </td>
            <td>
                <input type="text" class="form-control"  name="activity[]" required>
            </td>
            <td>
                <input class="form-control" name="regular_hours[]" step="any" type="number" required>
            </td>
            
            <td>
              <select class="form-control " name="code[]" required>
                    <option value="">Select Project</option>';
        foreach($project_asign as $data) {
            $newRow .= '<option value="' . $data->id . '">' . $data->name . '</option>';
        }
        $newRow .= '</select>
                   
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
        </tr>';
        return response()->json(['success' => true, 'newRow' => $newRow]);
    }

    public function upload(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $file = $request->file('file');

            $data = Excel::toArray([], $file);

            foreach ($data[0] as $index => $row) {
                if ($index === 0) {
                    continue; 
                }
               
                $co_name = $row[0];
              
                $client_id = 0;

                $client = User::where('name',[$co_name])->where('role_id',2)->first();
               
                if(!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]))
                {
                    $user_date = "";
                    if(strstr($row[1], '/')){
                        $user_date = $row[1];
                    }else{
                        $user_date = date("Y/m/d",(($row[1]  - 25569) * 86400));
                    }

                    if(isset($client->id))
                    {
                        $client_id = $client->id;
                    } else {                   
                       $new_user_record = User::create([
                            'name' => $co_name,
                            'role_id' => 2,
                            'created_by' => auth()->user()->id
                        ]);
    
                       $new_user_record = $new_user_record->toArray();
                       $client_id = $new_user_record["id"];
                    }
                   
                    if($client_id != 0){
                        TimesheetReport::create([
                            "client_id" => $client_id,
                            "user_id" => auth()->user()->id,
                            "date" => $user_date,
                            "activity" => $row[2],
                            "regular_hours" => $row[3],
                            "billable" => $row[4] ?? '',
                            "timesheet_id" => $request->timesheet_id,
                        ]);
                    }
                 }
            }

            $this->updateReport($request->timesheet_id);
            DB::commit();

            return redirect()->back()->with('success','Timesheet report generated successfuly');

        } catch (\Exception $e) {
            DB::rollback();

            return "Error: " . $e->getMessage();
        }
    }

    function updateReport($timesheet_id){
        $final_hours = TimesheetReport::where('timesheet_id',$timesheet_id)->sum('regular_hours');
        Timesheet::where('id',$timesheet_id)->update(['total_hours' => $final_hours]);
    }

    public function import_excel(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);
    
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
    
            foreach ($data[0] as $index => $row) {
                if ($index === 0) {
                    continue; 
                }
               
                $co_name = $row[0] ;
                $client_id = 0;
    
                $client = User::where('name', $co_name)->where('role_id', 2)->first();
               
                if (!empty($row[0]) && !empty($row[1]) ) {
                    $user_date = "";
    
                    if (strstr($row[1], '/')) {
                        $user_date = $row[1];
                    } else {
                        $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
                    }
                    
                    if (isset($client->id)) {
                        $client_id = $client->id;
                    } else {                   
                        $new_user_record = User::create([
                            'name' => $co_name,
                            'role_id' => 2,
                            'created_by' => $request->user_id
                        ]);
                        $client_id = $new_user_record->id;
                    }
    
                    if ($client_id != 0) {
                        TimesheetReport::create([
                            "client_id" => $client_id,
                            "user_id" => $request->user_id,
                            "date" => $user_date,
                            "activity" => $row[2] ?? null,
                            "regular_hours" => $row[3] ?? null,
                            "billable" => $row[4] ?? null,
                            "timesheet_id" => $request->timesheet_id,
                        ]);
                    }
                }
            }
    
            $this->updateReport($request->timesheet_id);
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Timesheet report generated successfully');
    
        } catch (\Exception $e) {
            DB::rollback();
    
            return "Error: " . $e->getMessage();
        }
    }
    



    public function sample_download(Request $request, $id)
    {
        return Excel::download(new UserExport($id), 'TimeSheet.xlsx');
    }
    public function user_download(Request $request)
    {
        return Excel::download(new UserCreateExport, 'User.xlsx');
    }
    public function client_template(Request $request)
    {
        return Excel::download(new ClientExport, 'Client.xlsx');
    }
    public function test_mail(){
        $name = "vikas";
        $email = "er.vikas391@gmail.com";
        $sub = "test mail";
        $mess = "test messsage";
        $mailData = [
        'url' => 'https://mywebsite.com/',
        ];
        $send_mail = "er.vikas391@gmail.com";
        Mail::to($send_mail)->send(new SendMail($name, $email, $sub, $mess));
        // $senderMessage = "thanks for your message , we will reply you in later";
        // Mail::to( $email)->send(new
        // SendMessageToEndUser($name,$senderMessage,$mailData));
        return "Mail Send Successfully";
    }




    //-----------------------------project section------------------
    public function project(){
        $clients = User::where('role_id',2)->get();
        $project = Project::paginate(10);
        $terms=Terms::get();
        return view('admin.project',compact('clients','project','terms'));
    }

    public function project_add(Request $request){
        $data = $request->validate([
            
            'code' => 'required',
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'bill_rate' => 'required',
            'bill_rate_unit' => 'required',
            'bill_by' => 'required',
            'approver_name' => 'required',
            'approver_email' => 'required',
            'approver_phone' => 'required',
            'pay_rate_currency'=> 'required',
            'terms_id'=> 'required',

        ]);
        $data['user_id'] = auth()->id();
        $result = Project::create($data);
        return redirect()->route('project')->with('success','Project created successfully');
    }

    public function project_edit(Request $request){
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'bill_rate' => 'required', 
            'bill_rate_unit' => 'required', 
            'bill_by' => 'required',
            'approver_name' => 'required',
            'approver_email' => 'required',
            'approver_phone' => 'required',  
            'pay_rate_currency'=> 'required',
            'terms_id'=> 'required',


        ]);
        $result = Project::where('id',$request['id'])->update($data);
        return redirect()->route('project')->with('success','Project updated successfully');
        
    }
    public function project_delete(Request $request)
    {
        Project::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Project  deleted successfuly');
    }
   
    
    public function import_project_excel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
    
            if (empty($data[0])) {
                return back()->with('error', 'Uploaded file is empty or invalid.');
            }
    
            foreach ($data[0] as $index => $row) {
                if ($index === 0) {
                    continue; // Skip header row
                }
                $co_name = $row[11];
               

                $term = Terms::where('name',[$co_name])->first();
                if ($term) {
                    $terms_id = $term->id;
                } else {
                    $terms_id = null; 
                }
                // Convert empty values to null
                $row = array_map(function ($value) {
                    return $value === '' ? null : $value;
                }, $row);
    
                // Convert Excel dates if necessary
                $date_start = !empty($row[2]) ? (strstr($row[2], '/') ? $row[2] : date("Y/m/d", (($row[2] - 25569) * 86400))) : null;
                $date_end = !empty($row[3]) ? (strstr($row[3], '/') ? $row[3] : date("Y/m/d", (($row[3] - 25569) * 86400))) : null;
    
                Project::create([
                    'code' => $row[0] ?? null,
                    'user_id' => auth()->id(),
                    'name' => $row[1] ?? null,
                    'start' => $date_start,
                    'end' => $date_end,
                    'bill_rate' => $row[4] ?? null,
                    'bill_by' => $row[5] ?? null,
                    'pay_rate_currency' => $row[6] ?? null,
                    'bill_rate_unit' => $row[7] ?? null,
                    'approver_name' => $row[8] ?? null,
                    'approver_email' => $row[9] ?? null,
                    'approver_phone' => $row[10] ?? null,
                    'terms_id' => $terms_id ?? null,
                ]);
            }
    
            return back()->with('success', 'Projects imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing projects: ' . $e->getMessage());
        }
    }
    
    public function project_sample_download(Request $request,)
    {
        return Excel::download(new ProjectExport, 'Project.xlsx');
    }


    public function getClientDetails($id)
    {
        $client = User::find($id);

        if ($client) {
            return response()->json([
                'approver_name' => $client->approver_name,
                'approver_email' => $client->approver_email,
            ]);
        }

        return response()->json(['error' => 'Client not found'], 404);
    }

    public function getProjectDetails($id)
    {
        $project = Project::where('client_id', $id)->first();
    
        if ($project) {
            return response()->json([
                'code' => $project->code,
            ]);
        }
    
        return response()->json(['error' => 'Project not found'], 404);
    }

    public function updateStatus(Request $request)
    {
        $report = TimesheetReport::find($request->id);
    
        if ($report) {
            $report->billable = $request->billable;
            $report->save();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Report not found']);
    }
    // ---------------------------------project asign-------------------------
    public function project_asign(Request $request) {
        $query = ProjectAsign::select('project_asign.*')
            ->join('users', 'users.id', '=', 'project_asign.user_id')
            ->join('project', 'project.id', '=', 'project_asign.project_id')
            ->with(['user', 'project']);
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
    
        $asign = $query->paginate(10);
        $project = Project::all();
        $user = User::where('role_id', 3)->get();
    
        return view('admin.asign_project', compact('project', 'user', 'asign'));
    }
    
   
   public function asign_add(Request $request){
    $data= $request->validate([
        'user_id' => 'required',
        'project_id' => 'required',
    ]);
    $result = ProjectAsign::create($data);
    return redirect()->route('asign_project');
   }
   public function asign_edit(Request $request)
   {
       $data = $request->validate([
           'user_id' => 'required',
           'project_id' => 'required',
       ]);
   
       if (!$request->has('id')) {
           return redirect()->route('asign_project')->with('error', 'Invalid Request: Missing ID.');
       }
   
       $result = ProjectAsign::where('id', $request->id)->update($data);
   
       if ($result) {
           return redirect()->route('asign_project')->with('success', 'Assigned project updated successfully.');
       } else {
           return redirect()->route('asign_project')->with('error', 'Failed to update the assigned project.');
       }
   }
   
   public function asign_delete(Request $request)
    {
        ProjectAsign::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Asign Project  deleted successfuly');
    }
// --------------------------user project-------------------------
    public function user_project(){
        $user= auth()->id();
        // $projectasign = ProjectAsign::where('user_id',$user)->pluck('project_id');
        $project = Project::where('user_id',$user)->paginate(10);
        return view('admin.user_project',compact('project'));
    }

    public function project_assign_download(Request $request,)
    {
        return Excel::download(new ProjectAssignExport, 'ProjectAssign.xlsx');
    }

    
    
    public function import_project_assign_excel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);
    
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
    
            if (empty($data[0])) {
                return back()->with('error', 'Uploaded file is empty or invalid.');
            }
    
            foreach ($data[0] as $index => $row) {
                if ($index === 0 || empty($row[0]) || empty($row[1])) {
                    continue;
                }
    
                $userName = $row[0];
                $projectName = $row[1];
    
                $client1 = User::where('name', $userName)->where('role_id', 3)->first();
                $client2 = Project::where('code', $projectName)->first();
                    ProjectAsign::create([
                        'user_id' => $client1->id,
                        'project_id' => $client2->id,
                    ]);
                
            }
    
            return redirect()->back()->with('success', 'Project assignments imported successfully.');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function import_user_excel(Request $request)
    {
        try {
            // Validate required fields
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);
    
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
    
            if (empty($data[0])) {
                return back()->with('error', 'Uploaded file is empty or invalid.');
            }
    
            foreach ($data[0] as $index => $row) {
                if ($index === 0) {
                    continue; // Skip header row
                }
    
                // Ensure name, email, and password are not empty
                if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    continue;
                }
    
                // Convert date fields (ensure they go null if empty)
                $dob = !empty($row[8]) ? (strstr($row[8], '/') ? $row[8] : date("Y/m/d", (($row[8] - 25569) * 86400))) : null;
                $date_start = !empty($row[12]) ? (strstr($row[12], '/') ? $row[12] : date("Y/m/d", (($row[12] - 25569) * 86400))) : null;
                $date_end = !empty($row[13]) ? (strstr($row[13], '/') ? $row[13] : date("Y/m/d", (($row[13] - 25569) * 86400))) : null;
    
                $employment1 = Employment::where('name', $row[10])->first();
                $systemaccess1 = SystemAccess::where('name', $row[11])->first();
                // $term = Vendors::where('company_name', $row[24])->first();
    
              
                User::create([
                    'name' => $row[0],
                    'email' => $row[1],
                    'password' => Hash::make($row[2]),
                    'address' => $row[3] ?? null,
                    'mobile_no' => $row[4] ?? null,
                    'pay_rate' => $row[5] ?? null,
                    'currency' => $row[6] ?? null,
                    'job_title' => $row[7] ?? null,
                    'dob' => $dob, 
                    'ssn' => $row[9] ?? null,
                    'employment_id' => $employment1->id ?? null, 
                    'system_access_id' => $systemaccess1->id ?? null, 
                    'start_date' => $date_start, 
                    'end_date' => $date_end, 
                    'bank_name' => $row[14] ?? null,
                    'bank_branch_address' => $row[15] ?? null,
                    'account_holder_name' => $row[16] ?? null,
                    'bank_account_number' => $row[17] ?? null,
                    'swift_bic_code' => $row[18] ?? null,
                    'iban_number' => $row[19] ?? null,
                    'bank_routing_number' => $row[20] ?? null,
                    'pay_rate_currency' => $row[21] ?? null,
                    'work_email' => $row[22] ?? null,
                    'location' => $row[23] ?? null,
                    // 'vendor_id' => $term->id ?? null, 
                    'role_id' => 3, 
                ]);
            }
    
            return redirect()->back()->with('success', 'User imported successfully.');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    
    public function import_client_excel(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $file = $request->file('file');

            $data = Excel::toArray([], $file);

            foreach ($data[0] as $index => $row) {
                if ($index === 0) {
                    continue; 
                }
               
              
               
                if (!empty($row[0])) {
                    User::create([
                        "name" => $row[0], 
                        "mobile_no" => $row[1] ?? null,
                        "address" => $row[2] ?? null,
                        "approver_name" => $row[3] ?? null,
                        "approver_email" => $row[4] ?? null,
                        "approver_phone" => $row[5] ?? null,
                        "role_id" => 2,
                        "created_by" => auth()->id(),
                    ]);

                 }
            }


            DB::commit();

            return redirect()->back()->with('success','Client generated successfuly');

        } catch (\Exception $e) {
            DB::rollback();

            return "Error: " . $e->getMessage();
        }
    }

    
    public function user_project_add(Request $request)
    {
        $user = auth()->id();
    
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'approver_name' => 'required',
            'approver_email' => 'required',
            'approver_phone' => 'required',
            // 'pay_rate_currency'=> 'required',

        ]);
        $data['user_id'] = $user;
        $project = Project::create($data);
        $project_asign = ProjectAsign::create([
            'user_id' => auth()->id(),
            'project_id' => $project->id,
        ]);
    
        return redirect()->route('user_project')->with('success', 'Project created and assigned successfully.');
    }
    

    public function user_project_edit(Request $request){
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'approver_name' => 'required',
            'approver_email' => 'required',
            'approver_phone' => 'required',
            // 'pay_rate_currency'=> 'required',
        ]);
        $result = Project::where('id',$request['id'])->update($data);
        return redirect()->route('user_project')->with('success','Project updated successfully');
        
    }
    public function user_project_delete(Request $request)
    {
        Project::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Project  deleted successfuly');
    }


    // --------------------------------- Type of Employment----------------------

    public function employment(){
        $get = Employment::paginate(10);
        return view('admin.type_of_employment',compact('get'));
    }

    public function employment_add(Request $request){
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['user_id']= auth()->id();

        $result = Employment::create($data);
        return redirect()->route('employment')->with('Success','Type of Employment Created');
    }
    public function employment_delete(Request $request){
        Employment::where('id',$request['id'])->delete();
        return redirect()->back()->with('Success','Type of Employment Deleted');
    }
     // --------------------------------- Type of System Access----------------------

     public function system_access(){
        $get = SystemAccess::paginate(10);
        return view('admin.type_of_system_access',compact('get'));
    }

    public function system_access_add(Request $request){
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['user_id']= auth()->id();
        $result = SystemAccess::create($data);
        return redirect()->route('system_access')->with('Success','Type of System Access Created');
    }
    public function system_access_delete(Request $request){
        SystemAccess::where('id',$request['id'])->delete();
        return redirect()->back()->with('Success','Type of System Access Deleted');
    }

       // --------------------------------- Type of Terms----------------------

       public function terms(){
        $get = Terms::paginate(10);
        return view('admin.terms',compact('get'));
    }

    public function terms_add(Request $request){
        $data = $request->validate([
            'name' => 'required',
        ]);
        $data['user_id']= auth()->id();
        $result = Terms::create($data);
        return redirect()->route('terms')->with('Success','Terms Created Successfully');
    }
    public function terms_delete(Request $request){
        Terms::where('id',$request['id'])->delete();
        return redirect()->back()->with('Success','Terms Successfully Deleted');
    }


    // ------------------------users himself Edit---------------------------

    public function user_edit(Request $request, $id) {
        $user = User::where('id', $id)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
    
        return view('admin.user_edit', compact('user'));
    }
    
   

    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $data = $request->validate([
            'name' => 'required',
            'mobile_no' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $id,
            'work_email' => 'nullable|email|unique:users,work_email,' . $id,
            'location' => 'nullable',
            'currency' => 'nullable',
            'pay_rate_currency' => 'nullable',
            'job_title' => 'nullable',
            'address' => 'nullable',
            'ssn' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'bank_name' => 'nullable',
            'bank_branch_address' => 'nullable',
            'account_holder_name' => 'nullable',
            'bank_account_number' => 'nullable',
            'swift_bic_code' => 'nullable',
            'iban_number' => 'nullable',
            'bank_routing_number' => 'nullable',
            'dob' => 'nullable',
        ], [
            'email.unique' => 'This email is already registered.',
            'work_email.unique' => 'This work email is already registered.',
        ]);
    
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
    
        $user->update($data);
    
        return redirect()->back()->with('success', 'User updated successfully!');
    }
    
    
// ---------------------------------------Vendors-------------------
public function Vendors($id) {
    $user = Vendors::where('user_id', $id)->first();
    return view('admin.vendors', compact('user', 'id')); 
}



public function Add_Vendor(Request $request) {
    $data = $request->validate([
        'company_name' => 'required',
        'first_name' => 'nullable',
        'last_name' => 'nullable',
        'phone' => 'nullable',
        'address' => 'nullable',
        'email' => 'required|email', // No unique validation
        'pay_rate' => 'nullable',
        'terms' => 'nullable',
        'tax_id_number' => 'nullable',
        'consultant_name' => 'nullable',
        'pay_rate_currency' => 'nullable',
        'bank_name' => 'nullable',
        'bank_branch_address' => 'nullable',
        'account_holder_name' => 'nullable',
        'bank_account_number' => 'nullable',
        'swift_bic_code' => 'nullable',
        'iban_number' => 'nullable',
        'bank_routing_number' => 'nullable',
    ]);

    $data['user_id'] = $request->input('user_id'); 
    $data['created_by'] = auth()->id(); 
//  echo "<pre>"; print_r($data);die;
    Vendors::updateOrCreate(
        ['user_id' => $data['user_id']], 
        $data
    );

    return redirect()->back()->with('success', 'Vendor saved successfully!');
}


   // ------------------------ Users Edit or Create their Vendor ---------------------------
   public function vendor_edit(Request $request) {
    $userId = auth()->id();
    $vendor = Vendors::where('user_id', $userId)->first();

    

    return view('admin.user_vendor_edit', compact('vendor'));
}
public function vendor_update(Request $request) {
    $userId = auth()->id();
    
    $data = $request->validate([
        'company_name' => 'required',
        'first_name' => 'nullable',
        'last_name' => 'nullable',
        'phone' => 'nullable',
        'address' => 'nullable',
        'email' => 'required',
        'pay_rate' => 'nullable',
        'terms' => 'nullable',
        'tax_id_number' => 'nullable',
        'consultant_name' => 'nullable',
        'pay_rate_currency' => 'nullable',
        'bank_name' => 'nullable',
        'bank_branch_address' => 'nullable',
        'account_holder_name' => 'nullable',
        'bank_account_number' => 'nullable',
        'swift_bic_code' => 'nullable',
        'iban_number' => 'nullable',
        'bank_routing_number' => 'nullable',
    ]);

    $data['user_id'] = $userId;

    $vendor = Vendors::updateOrCreate(['user_id' => $userId], $data);

    return redirect()->back()->with('success', 'Vendor information saved successfully!');
}




public function switchToUser()
{
    if (Auth::check() && Auth::user()->role_id == 1) {
     
        $user = User::where('role_id', 3)->first();

        if ($user) {
            Auth::logout();
            Auth::login($user); 

            return redirect('/dashboard'); 
        } else {
            return back()->with('error', 'No user found with role_id 3.');
        }
    }

    return back()->with('error', 'You must be an admin to switch.');
}

    // Impersonate a user
    public function impersonateUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
    
     
        session(['impersonate_admin' => Auth::id()]);
    
      
        Auth::login($user);
    
        return redirect('/'); 
    }
    

    // Stop impersonation and switch back to admin
    public function stopImpersonating()
    {
        if (session()->has('impersonate_admin')) {
            $adminId = session('impersonate_admin');

            // Log back in as the admin
            Auth::loginUsingId($adminId);

            // Remove impersonation session
            session()->forget('impersonate_admin');
        }

        return redirect('/'); // Redirect back to admin dashboard
    }
}



