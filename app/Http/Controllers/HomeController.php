<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use App\Models\BillStatus;
use App\Models\InvoiceStatus;
use Illuminate\Http\Request;
use App\Models\TimesheetApproval;
use App\Models\User;
use App\Models\Vendors;
use App\Models\Terms;
use App\Models\Project;
use App\Models\Employment;
use App\Models\SystemAccess;
use App\Exports\ProjectExport;
use App\Exports\ProjectDataExport;

use App\Exports\ProjectAssignExport;
use App\Models\ProjectAsign;
use App\Models\ProjectAsignClient;
use App\Models\Timesheet;
use App\Models\TimesheetReport;
use App\Models\TimesheetNotes;
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
use App\Models\Expenses;
use App\Exports\UserListExport;
use App\Exports\UserCreateExport;
use App\Exports\ClientListExport;
use App\Exports\TimesheetStatusExport;
use Carbon\Carbon;
use App\Mail\SendMail;
use Mail;
// use App\Services\GeminiService;

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
            $totalWorkingHoursQuery = TimesheetReport::query();
            if ($month !== '0') {
                $monthNumber = Carbon::parse("1 $month")->format('n');
                $totalWorkingHoursQuery->whereMonth('date', $monthNumber);
            }
            if ($year !== '0') {
                $totalWorkingHoursQuery->whereYear('date', $year);
            }
            $totalWorkingHours = $totalWorkingHoursQuery->sum('regular_hours');

            $timesheetsQuery = DB::table('timesheets')
                ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
                ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
                ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
                ->select(
                    'users.name as user_name',
                    'B.name as client_name',
                    'timesheets.month',
                    'timesheet_reports.activity',
                    'timesheet_reports.regular_hours'
                );

            if ($month !== '0') {
                $timesheetsQuery->where('timesheets.month', '=', $month);
            }
            if ($year !== '0') {
                $timesheetsQuery->where('timesheets.year', '=', $year);
            }

            $timesheets = $timesheetsQuery->paginate(10);
            $total_records = count($timesheets);
            $filter = ($month !== '0' || $year !== '0') ? "yes" : "no";
        }

        $clients = User::where('role_id', 2)->get();
        $users = User::where('role_id', 3)->get();
        $years = Timesheet::select('year')->groupBy('year')->get();
    } else {
        $clients = User::where('role_id', 2)->get();
        $timesheetsQuery = DB::table('timesheets')
            ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
            ->join('users as users', 'timesheet_reports.user_id', '=', 'users.id')
            ->join('users as B', 'timesheet_reports.client_id', '=', 'B.id')
            ->where('timesheet_reports.user_id', '=', auth()->user()->id)
            ->select(
                'users.name as user_name',
                'B.name as client_name',
                'timesheets.month',
                'timesheet_reports.activity',
                'timesheet_reports.regular_hours'
            );

        if ($month !== '0') {
            $timesheetsQuery->where('timesheets.month', '=', $month);
        }
        if ($year !== '0') {
            $timesheetsQuery->where('timesheets.year', '=', $year);
        }

        $timesheets = $timesheetsQuery->paginate(10);

        $years = Timesheet::select('year')->groupBy('year')->get();

        $users = [];
        $total_records = 0;
        $totalWorkingHours = 0;
        $filter = "no";
    }
        return view('admin/dashboard',['timesheets' => $timesheets ,'year' => $year,'years' => $years, 'clients' => $clients , 'users' => $users ,'user' =>$user,'client' => $client,'month' =>$month, 'total_records' => $total_records ,'totalWorkingHours' => $totalWorkingHours,'filter' => $filter]);
    }
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
            'timesheets.id as timesheet_id',
            'users.name as user_name',
            'users.id as user_id',
            'B.name as client_name',
            'B.id as client_id',
            'timesheets.month',
            'timesheets.year',
            'E.name as terms_name',
            'C.code as project_code',
            'C.id as project_id',
            'C.name as project_name',
            DB::raw('SUM(timesheet_reports.regular_hours) as regular_hours'),
            'C.bill_rate as project_bill_rate',
            DB::raw('SUM(timesheet_reports.regular_hours) * C.bill_rate as total_amount'),
            'C.bill_rate_unit as project_bill_rate_unit'
        );

        $AllTotalAmount = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id','=','timesheet_reports.timesheet_id')
        ->join('project','timesheet_reports.code','=','project.id')
        ->join('project_asign', function ($join) {
            $join
            ->on('project.id', '=', 'project_asign.project_id')
            ->on('project_asign.user_id', '=', 'timesheet_reports.user_id');
        })
        ->select(
            DB::raw('SUM(timesheet_reports.regular_hours) as total_hours'),
            DB::raw('SUM(timesheet_reports.regular_hours * project.bill_rate) as all_total_amount')
        );
        // ->get();
        // echo "<pre>"; print_r($AllTotalAmount);die;

    if ($month != '0') {
        $query->where('timesheets.month', '=', $month);
        $AllTotalAmount->where('timesheets.month', '=',$month);
    }
    if ($year != '0') {
        $query->where('timesheets.year', '=', $year);
        $AllTotalAmount->where('timesheets.year', '=',$year);
    }
    if ($user != '0') {
        $query->where('timesheet_reports.user_id', '=', $user);
        $AllTotalAmount->where('timesheet_reports.user_id', '=',$user);

    }
    if ($client != '0') {
        $query->where('timesheet_reports.client_id', '=', $client);
        $AllTotalAmount->where('timesheet_reports.client_id', '=',$client);

    }

    $query->groupBy(
        // 'timesheet_reports.id',
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
    
    $timesheets = $query->paginate(10)
    ->appends([
        'user' => $user,
        'client' => $client,
        'month' => $month,
        'year' => $year,
    ]);
    $totals= $AllTotalAmount->first();
    $all_total_amount= $totals->all_total_amount ?? 0 ;
    $total_hours = $totals->total_hours ?? 0;
        // echo "<pre>"; print_r($totals);die;

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
        'all_total_amount' => $all_total_amount,
        'total_hours' => $total_hours

    ]);
}
public function updateInvoiceStatus(Request $request)
{
    // echo "<pre>"; print_r($request);die;
    $validated = $request->validate([
        'status' => 'required|in:1,2',
        'timesheet_id' => 'required|integer',
        'user_id' => 'required|integer',
        'client_id' => 'required|integer',
        'project_id' => 'required|integer',
        'pay_rate' => 'required',
        'total_hours' => 'required',
        'total_amount' => 'required'
    ]);

    InvoiceStatus::updateOrCreate(
        [
            'timesheet_id' => $validated['timesheet_id'],
            'user_id' => $validated['user_id'],
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id']
        ],
        [
            'project_bill_rate' => $validated['pay_rate'],
            'hours' => $validated['total_hours'],
            'total_amount' => $validated['total_amount'],
            'status' => $validated['status']
        ]
    );

    return response()->json(['message' => 'Invoice status updated successfully']);
}

    
            
// -------------------------------------------Bill controller--------------------
public function Bill(Request $data)
{
    $user = !empty($data->user) ? $data->user : '0';
    $client = !empty($data->client) ? $data->client : '0';
    $month = !empty($data->month) ? $data->month : '0';
    $year = !empty($data->year) ? $data->year : '0';

    if (auth()->user()->role_id !== 1) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $firstDay = null;
    $lastDay = null;
    if ($month !== '0' && $year !== '0') {
        try {
            $monthNumber = Carbon::parse("1 $month")->month;
            $firstDay = Carbon::createFromDate($year, $monthNumber, 1)->format('Y/m/d');
            $lastDay = Carbon::createFromDate($year, $monthNumber)->endOfMonth()->format('Y/m/d');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid month or year input');
        }
    }

    $query = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
        ->join('users as consultant', 'timesheet_reports.user_id', '=', 'consultant.id')
        ->join('users as client', 'timesheet_reports.client_id', '=', 'client.id')
        ->join('project', 'timesheet_reports.code', '=', 'project.id')
        ->join('project_asign', function ($join) {
            $join->on('project.id', '=', 'project_asign.project_id')
                 ->on('project_asign.user_id', '=', 'timesheet_reports.user_id');
        })
        // ->join('bill_status', function ($join){
        //     $join->on('project.id', '=','bill_status.project_id')
        //          ->on('consultant.id','=','bill_status.user_id')
        //          ->on('timesheets.id', '=' , 'bill_status.timesheet_id')
        //          ->on('client.id' , '=' ,'bill_status.client_id');
        // })
        ->join('terms', 'project.terms_id', '=', 'terms.id')
        ->leftJoin('vendors', 'consultant.id', '=', 'vendors.user_id')
        ->select(
            'timesheets.id as timesheet_id',
            'consultant.id as user_id',
            'vendors.company_name as vendor_name',
            'consultant.name as user_name',
            'consultant.pay_rate as user_rate',
            'consultant.address as user_address',
            'consultant.location as user_location',
            'client.name as client_name',
            'client.id as client_id',
            'timesheets.month',
            'timesheets.year',
            'terms.name as terms_name',
            'project.code as project_code',
            'project.id as project_id',
            'project.name as project_name',
            'project.bill_rate as bill_rate_currency',
            DB::raw('SUM(timesheet_reports.regular_hours) as regular_hours'),
            // 'project.pay_rate_currency as project_pay_rate_currency',
            DB::raw('SUM(timesheet_reports.regular_hours) * consultant.pay_rate as total_amount'),
            DB::raw('SUM(timesheet_reports.regular_hours * consultant.pay_rate) as all_total_amount'),
            'project.bill_rate_unit as project_bill_rate_unit',
            // 'bill_status.status'
        )
        ->groupBy(
            // 'timesheet_reports.id',
            'vendors.company_name',
            'consultant.name',
            'consultant.pay_rate',
            'consultant.address',
            'consultant.location',
            'client.name',
            'timesheets.month',
            'timesheets.year',
            'terms.name',
            'project.code',
            'project.name',
            'project.bill_rate',
            // 'bill_status.status',
            // 'timesheet_reports.regular_hours',
            // 'project.pay_rate_currency',
            'project.bill_rate_unit'
        );
        $totalQuery = DB::table('timesheets')
        ->join('timesheet_reports', 'timesheets.id', '=', 'timesheet_reports.timesheet_id')
        ->join('project', 'timesheet_reports.code', '=', 'project.id')
        ->join('users as user','timesheet_reports.user_id','=','user.id')
        ->join('project_asign', function ($join) {
            $join->on('project.id', '=', 'project_asign.project_id')
                 ->on('project_asign.user_id', '=', 'timesheet_reports.user_id');
        })
        ->select(
            DB::raw('SUM(timesheet_reports.regular_hours) as total_hours'),
            DB::raw('SUM(timesheet_reports.regular_hours * user.pay_rate) as full_total_amount')
        );
        // ->get();
        // echo "<pre>"; print_r($totalQuery);die;
    
        if ($user !== '0') {
            $query->where('timesheet_reports.user_id', '=', $user);
            $totalQuery->where('timesheet_reports.user_id', '=', $user);
        }
        if ($client !== '0') {
            $query->where('timesheet_reports.client_id', '=', $client);
            $totalQuery->where('timesheet_reports.client_id', '=', $client);
        }
        if ($month !== '0') {
            $query->where('timesheets.month', '=', $month);
            $totalQuery->where('timesheets.month', '=', $month);
        }
        if ($year !== '0') {
            $query->where('timesheets.year', '=', $year);
            $totalQuery->where('timesheets.year', '=', $year);
        }

    $timesheets = $query->paginate(20)->appends([
        'user' => $user,
        'client' => $client,
        'month' => $month,
        'year' => $year,
    ]);
    $result = $totalQuery->first();
    $fullTotalAmount = $result->full_total_amount ?? 0;
    $totalHours = $result->total_hours ?? 0;
// echo "<pre>"; 
// print_r($result);die;
    foreach ($timesheets as $timesheet) {
        $monthNumber = Carbon::parse("1 {$timesheet->month}")->month;
        $timesheet->first_day = Carbon::createFromDate($timesheet->year, $monthNumber, 1)->format('Y/m/d');
        $timesheet->last_day = Carbon::createFromDate($timesheet->year, $monthNumber)->endOfMonth()->format('Y/m/d');
    }

    $clients = User::where('role_id', 2)->get();
    $users = User::where('role_id', 3)->get();
    $years = Timesheet::select('year')->groupBy('year')->get();
    // echo "<pre>"; print_r($timesheets);die;
    
    return view('admin/bill', [
        'timesheets' => $timesheets,
        'full_total_amount' => $fullTotalAmount,
        'total_hours'=> $totalHours,
        'year' => $year,
        'years' => $years,
        'clients' => $clients,
        'users' => $users,
        'user' => $user,
        'client' => $client,
        'month' => $month,
        'first_day' => $firstDay,
        'last_day' => $lastDay,
    ]);
}

public function updateBillStatus(Request $request)
{
    // echo "<pre>"; print_r($request);die;
    $validated = $request->validate([
        'status' => 'required|in:1,2',
        'timesheet_id' => 'required|integer',
        'user_id' => 'required|integer',
        'client_id' => 'required|integer',
        'project_id' => 'required|integer',
        'pay_rate' => 'required',
        'total_hours' => 'required',
        'total_amount' => 'required'
    ]);

    BillStatus::updateOrCreate(
        [
            'timesheet_id' => $validated['timesheet_id'],
            'user_id' => $validated['user_id'],
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id']
        ],
        [
            'pay_rate' => $validated['pay_rate'],
            'quantity' => $validated['total_hours'],
            'total_amount' => $validated['total_amount'],
            'status' => $validated['status']
        ]
    );

    return response()->json(['message' => 'Bill status updated successfully']);
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
    $remember = $request->has('remember');

    if (Auth::attempt($credentials , $remember)) {
        $request->session()->regenerate();
        $user = Auth::user();
        if(auth()->user()->role_id == 1 ){
            return redirect()->intended('/');
        }
        elseif (auth()->user()->role_id == 3 ){
            return redirect()->intended('/timesheet');
        }
        else{
            return redirect('/login')->with('error', 'Oops! It looks like your account isn’t authorized yet. Please contact the admin for access.');

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
        $query = User::whereIn('role_id', [3, 0]);

    
        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        // Filter by user
        if ($request->has('user') && !empty($request->user)) {
            $query->where('id', $request->user);
        }
    
        $users = $query->paginate(20);
        $filter = User::whereIn('role_id', [3,0])
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
        // 'work_email' => 'nullable|email|unique:users,work_email,' . $request->id,
    ], [
        'email.unique' => 'This email is already registered.',
        // 'work_email.unique' => 'This work email is already registered.',
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
  
    public function user_status(Request $request){
       
        $data = $request->validate([
            'role_id' => 'required',
        ]);
        // echo "<pre>"; 
        // print_r($data);die;
        User::where('id', $request->id)->update($data);
        return redirect()->back()->with('Success', 'Status Updated Successfully');
    }
    // -------------------------------------------------- Timesheet section ------------------------------------------------

    public function Timesheet()
    {
            $timesheets = Timesheet::paginate(10);
            $total_hours = TimesheetReport::where('user_id',auth()->user()->id)->sum('regular_hours');
            $status = TimesheetApproval::where('user_id',auth()->user()->id)->get();
            // echo "<pre>";print_r($status);die;
            $users = User::where('role_id',3)->get();
            $year = Timesheet::select('year')->groupBy('year')->get();


            return view('admin.timesheet',['timesheets' => $timesheets,'total_hours' => $total_hours, 'users' => $users,'status'=>$status, 'year'=>$year]);
    }
public function copy_timesheet_data(Request $request)
    {
        // dd($request);
        $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer',
            'timesheet_id' => 'required|exists:timesheets,id'
        ]);

        try {
            DB::beginTransaction();

            $sourceTimesheet = Timesheet::where('month', $request->month)
                ->where('year', $request->year)
                ->first();

            if (!$sourceTimesheet) {
                return redirect()->back()->with('error', 'Source timesheet not found.');
            }

            $targetTimesheet = Timesheet::find($request->timesheet_id);
            if (!$targetTimesheet) {
                return redirect()->back()->with('error', 'Target timesheet not found.');
            }

            $sourceReports = TimesheetReport::where('timesheet_id', $sourceTimesheet->id)
                ->where('user_id', auth()->user()->id)
                ->get();

            if ($sourceReports->isEmpty()) {
                return redirect()->back()->with('error', 'No reports found for the selected timesheet.');
            }
            $targetMonth = Carbon::parse("1 {$targetTimesheet->month}")->month;
            $targetYear = $targetTimesheet->year;
            $totalHours = 0;
            foreach ($sourceReports as $report) {
                $originalDay = Carbon::parse($report->date)->day;

                $newDate = Carbon::create($targetYear, $targetMonth, $originalDay)->format('Y/m/d');
                $newReport = TimesheetReport::create([
                    'timesheet_id' => $targetTimesheet->id,
                    'user_id' => $report->user_id,
                    'client_id' => $report->client_id,
                    'date' => $newDate, 
                    'activity' => $report->activity,
                    'regular_hours' => $report->regular_hours,
                    'code' => $report->code,
                    'billable' => $report->billable,
                ]);

                // $details = $report->activity . ($report->code ? " (Code: {$report->code})" : '');
                // $newReport->summary = $geminiService->summarizeText($details);
                $newReport->save();

                // $totalHours += $report->regular_hours;
            }

            // $targetTimesheet->total_hours = ($targetTimesheet->total_hours ?? 0) + $totalHours;
            // $targetTimesheet->save();

            DB::commit();
            return redirect()->back()->with('success', 'Timesheet data copied successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error copying data: ' . $e->getMessage());
        }
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
        $regular_hours = TimesheetReport::where('user_id',auth()->user()->id)->where('timesheet_id',$record->id)->sum('regular_hours');
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
            
            $formattedDate = $date->format('m/d/Y');
            
            $dates[] = $formattedDate;
        }
        $clients = User::where('role_id',2)->get();
        $user = auth()->id();
                $project = ProjectAsign::where('user_id', $user)->pluck('project_id');
                $project_asign = Project::whereIn('id', $project)->get();
        return view('admin.report',['records' => $record,'dates' => $dates,'clinets' => $clients, 'reports' => $reports, 'id' => $id, 'monthName' => $monthName, 'yearName' => $yearName,
    'project_asign' => $project_asign , 'timesheet_remark' => $timesheet_remark , 'regular_hours' => $regular_hours]);
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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,xls,xlsx,csv|max:2048',

            'approver_name' => 'nullable',
            'approver_email' => 'nullable',
        ]);

        if ($data->hasFile('attachment')) {
            $file = $data->file('attachment');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/attachments'), $filename);
            $validatedRemark['attachment'] = 'uploads/attachments/' . $filename;
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
            
            $formattedDate = $date->format('m/d/Y');
            
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
            $newRow .= '<option value="' . $data->id . '">' . $data->code . '</option>';
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

    // public function upload(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Validate the uploaded file
    //         $request->validate([
    //             'file' => 'required|mimes:xlsx,xls,csv',
    //         ]);

    //         // Get the uploaded file
    //         $file = $request->file('file');

    //         // Convert file into an array
    //         $data = Excel::toArray([], $file);

    //         $isValidData = true; // Flag to track if data is valid
    //         foreach ($data[0] as $index => $row) {
    //             if ($index === 0) {
    //                 continue; // Skip header row
    //             }

    //             // Validate required fields
    //             if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])  || empty($row[5])) {
    //                 $isValidData = false; 
    //                 break;
    //             }

    //             // Process data
    //             $co_name = $row[0];
    //             $pro = $row[5];
    //             $client_id = 0;
    //             $project = Project::where('code', $pro)->first();
    //             $client = User::where('name', $co_name)->where('role_id', 2)->first();

                
    //             // Convert date if needed
    //             if (strstr($row[1], '/')) {
    //                 // Parse mm/dd/yy or mm/dd/yyyy to Y/m/d
    //                 $parsedDate = \DateTime::createFromFormat('m/d/Y', $row[1]) ?: \DateTime::createFromFormat('m/d/y', $row[1]);
    //                 $user_date = $parsedDate ? $parsedDate->format('Y/m/d') : null;
    //             } else {
    //                 // Excel serial number to Y/m/d
    //                 $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
    //             }
    //             if (!$user_date) {
    //                 $isValidData = false;
    //                 break;
    //             }
    //             // $user_date = "";
    //             // if (strstr($row[1], '/')) {
    //             //     $user_date = $row[1];
    //             // } else {
    //             //     $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
    //             // }

    //             // Handle client creation if not found
    //             if (isset($client->id)) {
    //                 $client_id = $client->id;
    //             } else {
    //                 $new_user_record = User::create([
    //                     'name' => $co_name,
    //                     'role_id' => 2,
    //                     'created_by' => auth()->user()->id,
    //                 ]);
    //                 $new_user_record = $new_user_record->toArray();
    //                 $client_id = $new_user_record["id"];
    //             }

    //             // Create timesheet entry if client_id is valid
    //             if ($client_id != 0 && isset($project->id)) {
    //                 TimesheetReport::create([
    //                     "client_id" => $client_id,
    //                     "date" => $user_date,
    //                     "activity" => $row[2],
    //                     "regular_hours" => $row[3],
    //                     "billable" => $row[4] ?? '',
    //                     'code' => $project->id,
    //                     "timesheet_id" => $request->timesheet_id,
    //                     "user_id" => auth()->user()->id,

    //                 ]);
    //             }
    //         }

    //         // Check if data is valid
    //         if (!$isValidData) {
    //             // Rollback the transaction if data is invalid
    //             DB::rollback();
    //             return redirect()->back()->with('error', 'It seems there is some missing or incorrect data in your uploaded file. Please download the template for your working month, fill it correctly, and make sure to choose data from the dropdown options in the template before re-uploading.');
    //         }

    //         // Update report after data is processed
    //         $this->updateReport($request->timesheet_id);

    //         DB::commit();

    //         return redirect()->back()->with('success', 'Timesheet report generated successfully');
    //     } catch (\Exception $e) {
    //         // Rollback transaction if there's an error
    //         DB::rollback();
    //         // Return error message
    //         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }
    public function upload(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);
    
            $file = $request->file('file');
    
            $data = Excel::toArray([], $file);
    
            $isValidData = true;
    
            foreach ($data[0] as $index => $row) {
                if ($index < 2) {
                    continue; 
                }
    
                if (empty($row[0]) || empty($row[1])  ) {
                    $isValidData = false;
                    break;
                }
    
                $co_name = $row[0];
                $pro = $row[5];
    
                if (strstr($row[1], '/')) {
                    $parsedDate = \DateTime::createFromFormat('m/d/Y', $row[1]) ?: \DateTime::createFromFormat('m/d/y', $row[1]);
                    $user_date = $parsedDate ? $parsedDate->format('Y/m/d') : null;
                } else {
                    $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
                }
    
                if (!$user_date) {
                    throw new \Exception("Row {$index}: Invalid date format.");
                }
    
                $month = date('F', strtotime($user_date)); 
                $year = date('Y', strtotime($user_date)); 
    
                $timesheet = Timesheet::find($request->timesheet_id);
                if (!$timesheet || $timesheet->month !== $month || $timesheet->year !== $year) {
                    throw new \Exception("Row {$index}: Date does not match the timesheet's month/year.Please download template from your using month and year. And select data from dropdown ");
                }
                $project = Project::where('name', $pro)->first();
                if (!$project) {
                    throw new \Exception("Row {$index}: Project '{$pro}' not found. Please select data from dropdown.");
                }
                $project_asign = ProjectAsign::where('project_id', $project->id)
                ->where('user_id', auth()->id())
                ->exists();
                if (!$project_asign) {
                    throw new \Exception("Row {$index}: Project '{$pro}' not assign. Please select data from dropdown.");
                }
                $client = User::where('name', $co_name)->where('role_id', 2)->first();
                if (!$client) {
                    throw new \Exception("Row {$index}: Client '{$co_name}' not found. Please select data from dropdown.");
                }
                // if (!isset($project->id) || !isset($client->id)) {
                //     $isValidData = false;
                //     break;
                // }
    
                TimesheetReport::create([
                    "client_id" => $client->id,
                    "date" => $user_date,
                    "activity" => $row[2],
                    "regular_hours" => $row[3],
                    "billable" => $row[4] ?? '',
                    "code" => $project->id,
                    "timesheet_id" => $request->timesheet_id,
                    "user_id" => auth()->user()->id,
                ]);
            }
    
            if (!$isValidData) {
                DB::rollback();
                return redirect()->back()->with('error', 'It seems there is some missing or incorrect data in your uploaded file. Please download the template for your working month, fill it correctly, and make sure to choose data from the dropdown options in the template before re-uploading.');
            }
    
            $this->updateReport($request->timesheet_id);
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Timesheet report generated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    // public function upload(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    
    //         $request->validate([
    //             'file' => 'required|mimes:xlsx,xls,csv',
    //         ]);
    
    //         $file = $request->file('file');
    
    //         $data = Excel::toArray([], $file);
    
    //         $isValidData = true; 
    
    //         foreach ($data[0] as $index => $row) {
    //             if ($index === 0) {
    //                 continue; 
    //             }
    
    //             if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
    //                 $isValidData = false;
    //                 break;
    //             }
    
    //             $co_name = $row[0];
    //             $pro = $row[5];
    
    //             if (strstr($row[1], '/')) {
    //                 $parsedDate = \DateTime::createFromFormat('m/d/Y', $row[1]) ?: \DateTime::createFromFormat('m/d/y', $row[1]);
    //                 $user_date = $parsedDate ? $parsedDate->format('Y/m/d') : null;
    //             } else {
    //                 $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
    //             }
    
    //             if (!$user_date) {
    //                 $isValidData = false;
    //                 break;
    //             }
    
    //             $project = Project::where('name', $pro)->first();
    //             $client = User::where('name', $co_name)->where('role_id', 2)->first();
    
    //             if (!isset($project->id) || !isset($client->id)) {
    //                 $isValidData = false;
    //                 break;
    //             }
    
    //             TimesheetReport::create([
    //                 "client_id" => $client->id,
    //                 "date" => $user_date,
    //                 "activity" => $row[2],
    //                 "regular_hours" => $row[3],
    //                 "billable" => $row[4] ?? '',
    //                 "code" => $project->id,
    //                 "timesheet_id" => $request->timesheet_id,
    //                 "user_id" => auth()->user()->id,
    //             ]);
    //         }
    //         if (!$isValidData) {
    //             DB::rollback();
    //             return redirect()->back()->with('error', 'It seems there is some missing or incorrect data in your uploaded file. Please download the template for your working month, fill it correctly, and make sure to choose data from the dropdown options in the template before re-uploading.');
    //         }
    
    //         $this->updateReport($request->timesheet_id);
    
    //         DB::commit();
    
    //         return redirect()->back()->with('success', 'Timesheet report generated successfully');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    //     }
    // }
    
   public function updateReport($timesheet_id){
        $final_hours = TimesheetReport::where('timesheet_id',$timesheet_id)->sum('regular_hours');
        Timesheet::where('id',$timesheet_id)->update(['total_hours' => $final_hours]);
    }
    public function import_excel(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
                'user_id' => 'required|exists:users,id',
                'timesheet_id' => 'required|exists:timesheets,id'
            ]);
    
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
    
            $timesheet = Timesheet::find($request->timesheet_id);
            $expectedMonth = $timesheet->month;
            $expectedYear = $timesheet->year;
    
            foreach ($data[0] as $index => $row) {
                if ($index < 2) {
                    continue; 
                }
    
                $co_name = trim($row[0] ?? '');
                $dateInput = $row[1] ?? null;
                $activity = $row[2] ?? null;
                $regular_hours = $row[3] ?? null;
                $billable = $row[4] ?? null;
                $project_name = $row[5] ?? null;
    
                if (empty($co_name) || empty($dateInput)) {
                    throw new \Exception("Row {$index}: Company name or date is missing.");
                }
    
                // Parse date
                if (strstr($dateInput, '/')) {
                    $parsedDate = \DateTime::createFromFormat('m/d/Y', $dateInput) ?: \DateTime::createFromFormat('m/d/y', $dateInput);
                    $user_date = $parsedDate ? $parsedDate->format('Y/m/d') : null;
                } else {
                    $user_date = date("Y/m/d", (($dateInput - 25569) * 86400));
                }
    
                if (!$user_date) {
                    throw new \Exception("Row {$index}: Invalid date format.");
                }
    
                $dateMonth = date('F', strtotime($user_date));
                $dateYear = date('Y', strtotime($user_date));
    
                if ($dateMonth !== $expectedMonth || $dateYear != $expectedYear) {
                    throw new \Exception("Row {$index}: Date does not match the timesheet's month/year. Please download template from your using month and year. And select data from dropdown");
                }
    
                $client = User::where('name', $co_name)->where('role_id', 2)->first();
                if (!$client) {
                    throw new \Exception("Row {$index}: Client '{$co_name}' not found. Please select data from dropdown");
                }
    
                $project = Project::where('name', $project_name)->first();
                if (!$project) {
                    throw new \Exception("Row {$index}: Project '{$project_name}' not found. Please select data from dropdown");
                }
                $project_asign = ProjectAsign::where('project_id', $project->id)
                ->where('user_id', auth()->id())
                ->exists();
                if (!$project_asign) {
                    throw new \Exception("Row {$index}: Project '{$project_name}' not assign. Please select data from dropdown");
                }
                TimesheetReport::create([
                    "client_id" => $client->id,
                    "user_id" => $request->user_id,
                    "date" => $user_date,
                    "activity" => $activity,
                    "regular_hours" => $regular_hours,
                    "billable" => $billable,
                    "code" => $project->id,
                    "timesheet_id" => $request->timesheet_id,
                ]);
            }
    
            $this->updateReport($request->timesheet_id);
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Timesheet report generated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Upload Failed: ' . $e->getMessage());
        }
    }
    


    // public function import_excel(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    
    //         $request->validate([
    //             'file' => 'required|mimes:xlsx,xls,csv',
    //         ]);
    
    //         $file = $request->file('file');
    //         $data = Excel::toArray([], $file);
    
    //         foreach ($data[0] as $index => $row) {
    //             if ($index === 0) {
    //                 continue; 
    //             }
               
    //             $co_name = $row[0] ;
    //             $client_id = 0;
    
    //             $client = User::where('name', $co_name)->where('role_id', 2)->first();
               
    //             if (!empty($row[0]) && !empty($row[1]) ) {
    //                 $user_date = "";
    
    //                 if (strstr($row[1], '/')) {
    //                     $user_date = $row[1];
    //                 } else {
    //                     $user_date = date("Y/m/d", (($row[1] - 25569) * 86400));
    //                 }
                    
    //                 if (isset($client->id)) {
    //                     $client_id = $client->id;
    //                 } else {                   
    //                     $new_user_record = User::create([
    //                         'name' => $co_name,
    //                         'role_id' => 2,
    //                         'created_by' => $request->user_id
    //                     ]);
    //                     $client_id = $new_user_record->id;
    //                 }
    
    //                 if ($client_id != 0) {
    //                     TimesheetReport::create([
    //                         "client_id" => $client_id,
    //                         "user_id" => $request->user_id,
    //                         "date" => $user_date,
    //                         "activity" => $row[2] ?? null,
    //                         "regular_hours" => $row[3] ?? null,
    //                         "billable" => $row[4] ?? null,
    //                         "timesheet_id" => $request->timesheet_id,
    //                     ]);
    //                 }
    //             }
    //         }
    
    //         $this->updateReport($request->timesheet_id);
    
    //         DB::commit();
    
    //         return redirect()->back()->with('success', 'Timesheet report generated successfully');
    
    //     } catch (\Exception $e) {
    //         DB::rollback();
    
    //         return "Error: " . $e->getMessage();
    //     }
    // }
    



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
    public function project(Request $request){
     
        $user = DB::table('project')
        ->join('users', 'project.user_id', '=', 'users.id')
        // ->where('users.role_id', 3)
        ->select('users.id', 'users.name as user_name')
        ->distinct()
        ->get();
    
        $query = DB::table('project')
        ->join('users', 'project.user_id', '=', 'users.id')
        ->select('project.*', 'users.name as user_name');

    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('users.name', 'like', "%$searchTerm%")
              ->orWhere('project.code', 'like', "%$searchTerm%")
              ->orWhere('project.bill_rate', 'like', "%$searchTerm%")
              ->orWhere('project.name', 'like', "%$searchTerm%");

        });
    }
                // echo "<pre>"; print_r($user);die;
        $project = $query->paginate(10);
        $terms=Terms::get();
        return view('admin.project',compact('user','project','terms'));
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
            // 'pay_rate_currency'=> 'required',
            'terms_id'=> 'required',

        ]);
        if (Project::where('name', $data['name'])->exists()) {
            return redirect()->back()->with('error', 'This project is already registered.');
        }
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
            // 'pay_rate_currency'=> 'required',
            'terms_id'=> 'required',


        ]);
        $exists = Project::where('name', $data['name'])
        ->where('id', '!=', $request->id)
        ->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'This project name is already registered.');
    }
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
            $skipped = [];
            $imported = 0;
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
    
                $projectName = $row[1] ?? null;
            if ($projectName && Project::where('name', $projectName)->exists()) {
                $skipped[] = $projectName;
                continue;
            }
                Project::create([
                    'code' => $row[0] ?? null,
                    'user_id' => auth()->id(),
                    'name' => $row[1] ?? null,
                    'start' => $date_start,
                    'end' => $date_end,
                    'bill_rate' => $row[4] ?? null,
                    'bill_by' => $row[5] ?? null,
                    // 'pay_rate_currency' => $row[6] ?? null,
                    'bill_rate_unit' => $row[7] ?? null,
                    'approver_name' => $row[8] ?? null,
                    'approver_email' => $row[9] ?? null,
                    'approver_phone' => $row[10] ?? null,
                    'terms_id' => $terms_id ?? null,
                ]);
                 $imported++;
            }
    
             $message = "$imported projects imported successfully.";
        if (!empty($skipped)) {
            $skippedList = implode(', ', $skipped);
            $message .= " Skipped existing project(s): $skippedList. because this projects are already registered" ;
        }

        return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing projects: ' . $e->getMessage());
        }
    }
    
    public function project_sample_download(Request $request,)
    {
        return Excel::download(new ProjectExport, 'Project.xlsx');
    }
    public function export_project_excel(Request $request,)
    {
        return Excel::download(new ProjectDataExport, 'Project.xlsx');
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
    // ---------------------------------project User asign-------------------------
    public function project_asign(Request $request, $id)
    {
        $query = ProjectAsign::select('project_asign.*', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'project_asign.user_id')
            ->join('project', 'project_asign.project_id', '=', 'project.id')
            ->where('project_asign.project_id', $id);
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
    
        $asign = $query->paginate(10);
        $project = Project::findOrFail($id);
        $user = User::where('role_id', 3)->get();
    
        return view('admin.asign_project', compact('project', 'user', 'asign'));
    }
    
    
   
   public function asign_add(Request $request ,$id ){
    $data= $request->validate([
        'user_id' => 'required',
        // 'project_id' => 'required',
        'start' => '',
        'end' => '',
    ]);
    $data['project_id']= $id;
    $result = ProjectAsign::create($data);
    return redirect()->route('asign_project', ['id' => $id])->with('success', 'Project assigned successfully.');
   }
   public function asign_edit(Request $request)
   {
       $data = $request->validate([
           'user_id' => 'required',
           'project_id' => 'required',
           'start' => '',
           'end' => '',
       ]);

       $result = ProjectAsign::where('id', $request->id)->update([
           'user_id' => $data['user_id'],
           'project_id' => $data['project_id'],
           'start' => $data['start'],
           'end' => $data['end'],
       ]);

       if ($result) {
           return redirect()->route('asign_project', ['id' => $data['project_id']])
               ->with('success', 'Assigned project updated successfully.');
       } else {
           return redirect()->route('asign_project', ['id' => $data['project_id']])
               ->with('error', 'Failed to update the assigned project.');
       }
   }
   
   public function asign_delete(Request $request)
    {
        ProjectAsign::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Asign Project  deleted successfuly');
    }

     // ---------------------------------project Client asign-------------------------
     public function project_asign_client(Request $request , $id) {
       
        // $query = 
        // DB::table('project_asign_client')
        //     ->join('users',  'project_asign_client.client_id',  '=',$id);
            // ->where('project_asign_client.project_id',$id);
        $asign= ProjectAsignClient::where('client_id',$id)->paginate(10);
          
          
        if ($request->has('search') && !empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
       
        // $asign = $query->paginate(10);
        // echo "<pre>"; 
        // print_r($query);die;
        $user = User::findOrFail($id);
        $project = Project::get();
    
        return view('admin.asign_project_client',compact('project', 'user', 'asign') );
    }
    
   
   public function client_asign_add(Request $request ,$id ){
    $data= $request->validate([
        // 'client_id' => 'required',
        'project_id' => 'required',
        'start' => '',
        'end' => '',
    ]);
    $data['client_id']= $id;
    $result = ProjectAsignClient::create($data);
    return redirect()->route('asign_project_client',['id'=>$id])->with('success', 'Project assigned successfully.');
   }
   public function client_asign_edit(Request $request)
   {
       $data = $request->validate([
        //    'user_id' => 'required',
           'project_id' => 'required',
           'start' => 'required',
           'end' => 'required',
       ]);

       $result = ProjectAsignClient::where('id', $request->id)->update([
        //    'user_id' => $data['user_id'],
           'project_id' => $data['project_id'],
           'start' => $data['start'],
           'end' => $data['end'],
       ]);

       if ($result) {
           return redirect()->back()
               ->with('success', 'Assigned project updated successfully.');
       } else {
           return redirect()->back()
               ->with('error', 'Failed to update the assigned project.');
       }
   }
   
   public function client_asign_delete(Request $request)
    {
        ProjectAsignClient::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Asign Project  deleted successfuly');
    }
// --------------------------user project-------------------------
public function user_project()
{
    $user = auth()->id();
    // $choose_project = Project::get();
    $assignedProjectIds = ProjectAsign::where('user_id', $user)->pluck('project_id');

    $project = Project::whereIn('id', $assignedProjectIds)->paginate(10);
    $choose_project = Project::whereNotIn('id',$assignedProjectIds)->get();
    return view('admin.user_project', compact('project','choose_project'));
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
            'approver_name' => '',
            'approver_email' => '',
            'approver_phone' => '',
            // 'pay_rate_currency'=> 'required',

        ]);
        if (Project::where('name', $data['name'])->exists()) {
            return redirect()->back()->with('error', 'This project is already registered.');
        }
        $data['user_id'] = $user;
        $project = Project::create($data);
        $project_asign = ProjectAsign::create([
            'user_id' => auth()->id(),
            'project_id' => $project->id,
            'start' => $project->start,
            'end' => $project->end
        ]);
    
        return redirect()->route('user_project')->with('success', 'Project created and assigned successfully.');
    }
    

    public function user_project_edit(Request $request){
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'approver_name' => '',
            'approver_email' => '',
            'approver_phone' => '',
            // 'pay_rate_currency'=> 'required',
        ]);
        $exists = Project::where('name', $data['name'])
        ->where('id', '!=', $request->id)
        ->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'This project name is already registered.');
    }
        $result = Project::where('id',$request['id'])->update($data);
        ProjectAsign::where('project_id', $request['id'])->update([
            'start' => $data['start'],
            'end' => $data['end'],
        ]);
        return redirect()->route('user_project')->with('success','Project updated successfully');
        
    }
    public function user_project_delete(Request $request)
    {
        Project::where('id',$request['id'])->delete();
        return redirect()->back()->with('error','Project  deleted successfully');
    }
    public function choose_project(Request $request){
        $data = $request->validate([
            'project_id'=>'required',
            'start' => '',
            'end' => '',
        ]);
        $data['user_id']= auth()->id();
        ProjectAsign::create($data);
        return redirect()->back()->with('success','Project Assign Successfully');
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


    // ----------------------timesheet email-----------------------------
    
 
    public function timesheet_email(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $to = $request->email;
        $user = auth()->id();
        $userName = User::where('id', $user)->value('name');
    
        $from = "Timeolo <timeolo.com>";
        $subject = "Your Timesheet Report";
    
        $reports = DB::table('timesheet_reports')
        ->where('timesheet_id', $id)
        ->where('user_id', auth()->id())
        ->get();
    

        $clients = User::where('role_id', 2)->pluck('name', 'id')->toArray();
        $projects = DB::table('project')->pluck('code', 'id')->toArray();
    
        $message_body = "
        <html>
        <head>
            <title>Timesheet Report</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; text-align: left; }
            </style>
        </head>
        <body>
            <h2>Timesheet Report send by $userName</h2>
            <h3>Hello $to,</h3>
            <p>Please find the timesheet report below:</p>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Hours</th>
                        <th>Project Code</th>
                        <th>Billable</th>
                    </tr>
                </thead>
                <tbody>";
    
        foreach ($reports as $report) {
            $client = $clients[$report->client_id] ?? 'N/A';
            $project = $projects[$report->code] ?? 'N/A';
            $billable = $report->billable ? 'Yes' : 'No';
    
            $message_body .= "
                <tr>
                    <td>$client</td>
                    <td>$report->date</td>
                    <td>$report->activity</td>
                    <td>$report->regular_hours</td>
                    <td>$project</td>
                    <td>$billable</td>
                </tr>";
        }
    
        $message_body .= "
                </tbody>
            </table>
            <br>
            <p>Regards,<br>Timeolo</p>
        </body>
        </html>";
    
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: $from\r\n";
    
        if (mail($to, $subject, $message_body, $headers)) {
            return redirect()->back()->with('success', 'Timesheet report sent by ' . $userName . '.');

        } else {
            return redirect()->back()->with('error', 'Unable to send email. Please try again.');

        }
    }

    // ----------------------------------expenses controller-------------------------
    public function Expenses(){
        $user= auth()->id();
        $expenses = Expenses::where('user_id',$user)->paginate(10);
        return view('admin.expenses' ,compact('expenses'));
    }


    public function Add_expenses(Request $request)
    {
        $request->validate([
            // 'receipts' => 'nullable|mimes:png,jpg,jpeg,gif,svg,webp|max:2048', 
            'receipts' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', 

            
        ]);
    
        $filename = null;
    
        if ($request->hasFile('receipts')) {
            $file = $request->file('receipts');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
    
            $file->move(public_path('uploads/receipts'), $filename);
        }
    
        $data = [
            'receipts' => $filename ? 'uploads/receipts/' . $filename : null,
            'destination' => $request->input('destination'),
            'amount' => $request->input('amount'),
            'travel_date' => $request->input('travel_date'),
            'purpose' => $request->input('purpose'),
            'user_id' => auth()->id(),
        ];
    
        Expenses::create($data);
    
        return redirect()->route('expenses')->with('success', 'Data Created Successfully');
    }
    
  
    
    public function Edit_expenses(Request $request)
    {
        $validatedData = $request->validate([
            'receipts' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'destination' => 'required',
            'amount' => 'required',
            'travel_date' => 'required',
            'purpose' => 'required',
        ]);
    
        // $validatedData['user_id'] = auth()->id();
    
        $expense = Expenses::findOrFail($request->id); 
    
        if ($request->hasFile('receipts')) {
            $file = $request->file('receipts');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/receipts'), $filename);
            $validatedData['receipts'] = 'uploads/receipts/' . $filename;
        } else {
            $validatedData['receipts'] = $expense->receipts;
        }
    
        $expense->update($validatedData);
    
        return redirect()->back()->with('success', 'Data Updated Successfully');
    }
    
    
    public function Delete_expenses(Request $request){

        $data = Expenses::where('id',$request->id)->delete();
        return redirect()->back()->with('Success', 'Expense Deleted Successfully');
    }





    // --------------------------------Status of Expenses-------------------

    public function Expenses_status(){
        $expenses= Expenses::paginate(20);
        return view('admin.expenses_status',compact('expenses'));
    }

    public function status_update(Request $request){
      $status=  $request->validate([
            'status'=>'required',
        ]);
        $status['approved_by']= auth()->id();
        $status['approved_at']= now();
        $data= Expenses::where('id',$request->id)->update($status);
        return redirect()->back()->with('Success','Status Updated Successfully');
    }


    // -----------------------timesheetApproval----------------------
    public function timesheet_approval(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required',
        ]);
    
        $userId = auth()->id();
    
        $exists = TimesheetApproval::where([
            'user_id' => $userId,
            'timesheet_id' => $id
        ])->exists();
    
        if ($exists) {
            return redirect()->back()->with('Success', 'You have already submitted this timesheet for approval.');
        }
    
        TimesheetApproval::create([
             'user_id' => $userId,
            'timesheet_id' => $id,
            'status' => $validated['status'],
        ]);
    
        return redirect()->back()->with('Success', 'Timesheet sent for approval.');
    }
    


    // --------------------------timesheet Approve status----------------------------
    public function timesheet_status(Request $request)
    {
          $month = !empty($request->month) ? $request->month : '0';
          $year = !empty($request->year) ? $request->year : '0';
        //   $status = !empty($request->status) ? $request->status : '0';
          $status = $request->has('status') && $request->status !== '' ? $request->status : null;

        $timesheet = Timesheet::get();
        $years = Timesheet::select('year')->groupBy('year')->get();
    
        $query = DB::table('timesheet_approval')
            ->join('timesheets', 'timesheet_approval.timesheet_id', '=', 'timesheets.id')
            ->join('users', 'timesheet_approval.user_id', '=', 'users.id')
            ->leftJoin('timesheet_notes', function($join) {
                $join->on('timesheets.id', '=', 'timesheet_notes.timesheet_id')
                     ->on('timesheet_approval.user_id', '=', 'timesheet_notes.user_id');
            })
            ->leftjoin('timesheet_reports', function($join){
                $join->on('timesheets.id', '=', 'timesheet_reports.timesheet_id')
                    ->on('timesheet_reports.user_id', '=', 'timesheet_approval.user_id');
            })
            ->select(
                'timesheet_approval.*',
                'timesheets.month',
                'timesheets.year',
                'users.name as user_name',
                'timesheet_notes.attachment',

                DB::raw('SUM(timesheet_reports.regular_hours) as total_hours')
            );
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
        }
    
       
           if ($month !== '0') {
            $query->where('timesheets.month', '=', $month);
        }
        if ($year !== '0') {
            $query->where('timesheets.year', '=', $year);
        }
        // if ($status != '0') {
        //     $query->where('timesheets.year', '=', $status);
        // }
    if (!is_null($status)) {
        $query->where('timesheet_approval.status', '=', $status); 
    }
        $query->groupBy(
            'timesheet_approval.id',
            'timesheets.month',
            'timesheets.year',
            'users.name',
            'timesheet_approval.status',
            'timesheet_approval.timesheet_id',
            'timesheet_approval.user_id',
       
        );
        $timesheet_status = $query->paginate(10);
         $timesheet_status->appends($request->except('page'));
        // echo "<pre>"; print_r($timesheet_status);die;
    
        return view('admin.timesheet_status', compact('timesheet_status', 'timesheet', 'month', 'years','status','year'));
    }

    public function downloadAttachment($timesheet_id, $user_id)
    {
        $note = DB::table('timesheet_notes')
            ->where('timesheet_id', $timesheet_id)
            ->where('user_id', $user_id)
            ->first();
    
        if (!$note || !$note->attachment) {
            abort(404, 'Attachment not found');
        }
    
        $filePath = public_path($note->attachment);
    
        if (!file_exists($filePath)) {
            abort(404, 'File does not exist on server');
        }
    
        return response()->download($filePath);
    }
    

   
    
    public function timesheet_status_update(Request $request, $timesheet_id, $user_id)
    {
        $data = $request->validate([
            'status' => 'required',
            'reason' => 'nullable',
        ]);
    
        $timesheetApproval = TimesheetApproval::where('timesheet_id', $timesheet_id)
            ->where('user_id', $user_id)
            ->first();
    
        if ($timesheetApproval) {
            $timesheetApproval->status = $data['status'];
            $timesheetApproval->approved_at = now(); 
            $timesheetApproval->save();
            $timesheet = Timesheet::find($timesheet_id);
    
            // Fetch the user's email
            $user = User::find($user_id);
            if ($user) {
                $email = $user->email;
                $name = $user->name;
                $statusMessage = $data['status'] == 1 ? 'approved' : 'rejected';
                $reason = $data['reason'] ?? null;
                $subject = "Timesheet $statusMessage";
                 $messageBody = "
                <html>
                    <body>
                        <p>Hello $name,</p>
                        <p>Your timesheet (Month: $timesheet->month, Year: $timesheet->year) has been <strong>$statusMessage</strong>.</p>";

            if ($reason) {
                $messageBody .= "<p><strong>Reason</strong>: $reason</p>";
            }

            $messageBody .= "
                        <p>Regards,<br>Timeolo</p>
                    </body>
                </html>
            ";
    
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                $headers .= "From: Timeolo <timeolo.com>\r\n";
    
                mail($email, $subject, $messageBody, $headers);
            }
    
            return redirect()->back()->with('success', 'Timesheet status updated and notification sent.');
        }
    
        return redirect()->back()->with('error', 'No matching timesheet approval record found.');
    }
    
    

    // public function timesheet_status_update(Request $request, $timesheet_id, $user_id)
    // {
    //     $data = $request->validate([
    //         'status' => 'required',
    //     ]);
    
    //     $timesheetApproval = TimesheetApproval::where('timesheet_id', $timesheet_id)
    //         ->where('user_id', $user_id)
    //         ->first();
        
    //         // echo "<pre>";
    //         // print_r($timesheetApproval);die;
    //     if ($timesheetApproval) {
    //         $timesheetApproval->status = $data['status'];
    //         $timesheetApproval->approved_at = now(); 
    //         $timesheetApproval->save();
    
    //         return redirect()->back()->with('success', 'Timesheet status updated successfully.');
    //     }
    
    //     return redirect()->back()->with('error', 'No matching timesheet approval record found.');
    // }
    

    public function timesheet_status_view_report(Request $request, $timesheet_id , $user_id,){
        $timesheet = TimesheetReport::where(['timesheet_id' => $timesheet_id, 'user_id' => $user_id ])->get ();
        // echo "<pre>"; print_r($timesheet);die;
        return view('admin.timesheet_status_view_report',compact('timesheet'));
    }


public function timesheet_status_export($month = null, $year = null, $status = null)
{
    return Excel::download(new TimesheetStatusExport($month, $year, $status), 'Timesheet_status.xlsx');
}


    // ---------------------------------------Admin Controller--------------------

    public function admin(){
        $admin = User::where('role_id',1)->paginate(10);
        return view('admin.admin',compact('admin'));
    }

    public function add_admin(Request $request){
         $request->validate([
            'email'=>'required|unique:users,email',
         ],[
            'unique.email'=> 'This email is already registered',
         ]);
         $admin = [
            'name'  =>$request['name'],
            'email' =>$request['email'],
            'password'=>Hash::make($request['password']),
            'mobile_no'=> $request['mobile_no'],
            'address' => $request['address'],
            'role_id' => 1,
         ];
         $admin['created_by']= auth()->id();
        //  echo "<pre>"; print_r($admin);die;
         $result = User::create($admin);
         return redirect()->back()->with('success', 'Admin Added Successfully!');
    }

    public function edit_admin(Request $request){
        $request->validate([
            'email' => 'required|unique:users,email,' . $request->id,

        ],[
            'unique.email' =>'This email is already registered',
        ]);
        $admin = [
            'name'=> $request['name'],
            'email'=> $request['email'],
            'address'=> $request['address'],
            'mobile_no'=> $request['mobile_no'],
        ];

        if (!empty($request['password'])){
            $admin['password'] = Hash::make($request['password']);
        }
        // echo "<pre>";   print_r($admin);die;
        $result = User::where('id',$request['id'])->update($admin);
        return redirect()->back()->with('success','Admin data updated Successfully');
        
    }

    public function delete_admin(Request $request){
        User::where('id',$request['id'])->delete();
        return redirect()->back()->with('success' , 'Admin Deleted Successfully');
    }
    //  -------------------------------------Project Asign Report---------------------
    public function project_asign_report(Request $request)
    {
        $users = User::where('role_id', 3)->get();
        $projects = Project::get();
    
        $query = DB::table('project_asign')
            ->join('project as A', 'project_asign.project_id', '=', 'A.id')
            ->join('users as B', 'project_asign.user_id', '=', 'B.id')
            ->select('project_asign.*', 'A.code as project_code', 'B.name as user_name');
    
        if ($request->filled('search')) {
            $query->where('B.name', 'like', '%' . $request->search . '%');
        }
    
        if ($request->filled('user_id')) {
            $query->where('B.id', $request->user_id);
        }
    
        if ($request->filled('project_id')) {
            $query->where('A.id', $request->project_id);
        }
    
        $data = $query->paginate(15);
    
        return view('admin.project_asign_report', compact('data', 'users', 'projects'));
    }  


   

    
}    
    



