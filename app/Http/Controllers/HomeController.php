<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PRequest;
use App\POrder;
use App\TOrder;
use App\Voucher;
use App\Project;
use Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Gate::allows('isAdmin'))
        {
            $pr = PRequest::where('status','Attached PO')
                ->orWhere('status','For Supply')
                ->count();
            $prmax = PRequest::count();

            $po = POrder::where('status','Attached DV')
                ->orWhere('status','For Supply')
                ->count();
            $pomax = POrder::count();

            $to = TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->count();
            $tomax = TOrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();

            return view('dashboard',compact('pr','prmax','po','pomax','to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isSupply'))
        {
            $pr = PRequest::where('status','Attached PO')
                ->orWhere('status','For Supply')
                ->count();
            $prmax = PRequest::count();

            $po = POrder::where('status','Attached DV')
                ->orWhere('status','For Supply')
                ->count();
            $pomax = POrder::count();

            $to = TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->count();
            $tomax = TOrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();

            return view('supply', compact('pr','prmax','po','pomax','to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isAccountant'))
        {
            $po = POrder::where('status','Attached DV')
                ->orWhere('status','For Supply')
                ->count();
            $pomax = POrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();

            $countprojects = Project::latest()->first();
            if(isset($countprojects->id))
            {
                $countprojects = $countprojects->id;
            }
            else
            {
                $countprojects = 0;
            }
            
            if($countprojects !=0)
            {
                for($x=1; $x <= $countprojects; $x++)
                {
                    $check[$x] = Project::where('id',$x)->count();
                    if($check[$x] == 1)
                    {
                        $projectname[$x] = Project::where('id',$x)->get();
                        $projectname[$x]  = $projectname[$x][0]->name;
                        $PO[$x] = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                            ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                            ->join('users','p_requests.user_id','=','users.id')
                            ->join('projects','users.project_id','=','projects.id')
                            ->where('projects.name',$projectname[$x])
                            ->sum('amount');
                        $DVandTO[$x] = Voucher::join('users','vouchers.user_id','=','users.id')
                            ->join('projects','users.project_id','=','projects.id')
                            ->where('projects.name',$projectname[$x])
                            ->sum('amount');
                        $numeric[$x] = $PO[$x] + $DVandTO[$x];
                        $amount[$x] = number_format($numeric[$x],2);
                    }
                    else
                    {
                        $amount[$x] = 0;
                        $projectname[$x] = "";
                    }
                }
            }
            else
            {
                $amount = 0;
                $projectname = "";
            }

            return view('accountant', compact('countprojects','amount','projectname','po','pomax','dv','dvmax'));
        }
        else if(Gate::allows('isHRMO'))
        {
            $to = TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->count();
            $tomax = TOrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();
            return view('hrmo', compact('to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isTOD'))
        {
            $pr = PRequest::where('status','Attached PO')
                ->orWhere('status','For Supply')
                ->count();
            $prmax = PRequest::count();

            $po = POrder::where('status','Attached DV')
                ->orWhere('status','For Supply')
                ->count();
            $pomax = POrder::count();

            $to = TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->count();
            $tomax = TOrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();

            return view('tod', compact('pr','prmax','po','pomax','to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isOOTD'))
        {
            $pr = PRequest::where('status','Attached PO')
                ->orWhere('status','For Supply')
                ->count();
            $prmax = PRequest::count();

            $po = POrder::where('status','Attached DV')
                ->orWhere('status','For Supply')
                ->count();
            $pomax = POrder::count();

            $to = TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->count();
            $tomax = TOrder::count();

            $dv = Voucher::where('status','Completed')->count();
            $dvmax = Voucher::count();

            return view('ootd', compact('pr','prmax','po','pomax','to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isUser'))
        {
            $enduser = auth()->user();
            $pr = PRequest::where([
                    ['status','Attached PO'],
                    ['user_id', $enduser->id]
                ])
                ->orWhere([
                    ['status','For Supply'],
                    ['user_id', $enduser->id]
                ])->count();
            $prmax = PRequest::where('user_id',$enduser->id)->count();

            $po = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                ->where([
                    ['p_orders.status','Attached DV'],
                    ['user_id',$enduser->id]
                ])
                ->orWhere([
                    ['p_orders.status','For Supply'],
                    ['user_id',$enduser->id]
                ])->count();
            $pomax = POrder::join('p_requests','p_orders.pr_id','=','p_requests.id')
                ->where('user_id',$enduser->id)->count();

            $to = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                ->where([
                    ['status','Received by HRMO / Completed'],
                    ['t_o_users.user_id', $enduser->id]
                ])
                ->orWhere([
                    ['status','For HRMO'],
                    ['t_o_users.user_id',$enduser->id]
                ])
                ->orWhere([
                    ['status','For HRMO'],
                    ['t_o_users.user_id',0]
                ])
                ->orWhere([
                    ['status','Received by HRMO / Completed'],
                    ['t_o_users.user_id',0]
                ])
                ->count();
            $tomax = TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                ->where('t_o_users.user_id',$enduser->id)
                ->orWhere('t_o_users.user_id',0)
                ->count();

            $dvforsalary = Voucher::where([
                    ['vouchers.status','Completed'],
                    ['vouchers.user_id',$enduser->id],
                    ['vouchers.type', 'DV for Salary']
                ])->count();
            $dvforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                ->where([
                    ['vouchers.status','Completed'],
                    ['p_requests.user_id',$enduser->id]
                ])->count();
            $dvforto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->where([
                    ['vouchers.status','Completed'],
                    ['vouchers.user_id',$enduser->id]
                ])->count();
            $dv = $dvforsalary + $dvforpo + $dvforto;
            $dvmaxforsalary = Voucher::where([
                ['vouchers.user_id',$enduser->id],
                ['vouchers.type','DV for Salary']
            ])->count();
            $dvmaxforpo = Voucher::join('p_orders','vouchers.po_id','=','p_orders.id')
                ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                ->where('p_requests.user_id',$enduser->id)
                ->count();
            $dvmaxfoto = Voucher::join('t_orders','vouchers.to_id','=','t_orders.id')
                ->where('vouchers.user_id',$enduser->id)
                ->count();
            $dvmax = $dvmaxforsalary + $dvmaxforpo + $dvmaxfoto;

            return view('enduser', compact('pr','prmax','po','pomax','to','tomax','dv','dvmax'));
        }
        else if(Gate::allows('isCanvasser'))
        {
            $pr = PRequest::join('canvasses','p_requests.id','=','canvasses.pr_id')
                ->where('canvasses.status','Completed')
                ->count();
            $prmax = PRequest::count();

            return view('canvasser',compact('pr','prmax'));
        }
    }    
}
