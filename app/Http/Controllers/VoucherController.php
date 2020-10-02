<?php

namespace App\Http\Controllers;

use App\Voucher;
use Illuminate\Http\Request;
use App\Events\LiveTable;
use App\PRequest;
use App\POrder;
use App\TOrder;
use App\User;
use Gate;
use Validator;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            if($_GET['type'] == "Generate DV")
            {
                //for PR/PO
                return datatables()->of(PRequest::join('p_orders', 'p_requests.id', '=', 'p_orders.pr_id')
                    ->select(['p_orders.id as id','p_orders.po_number as po_number','p_orders.description as description','p_orders.status as status','p_orders.created_at as created_at','p_orders.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks','p_requests.type as type'])
                    ->where([
                        [
                            'p_orders.status',"For Supply"
                        ],
                        [
                            'p_requests.type','50k+'
                        ],
                    ])
                    ->orWhere([
                        [
                            'p_orders.status',"For Supply"
                        ],
                        [
                            'p_requests.type','50k below'
                        ],
                    ])
                    ->orWhere([
                        [
                            'p_orders.status',"For OOTD"
                        ],
                        [
                            'p_requests.type','for plane tickets'
                        ],
                    ])
                    ->orWhere([
                        [
                            'p_orders.status',"For Accounting"
                        ],
                        [
                            'p_requests.type','for plane tickets'
                        ],
                    ])
                    ->orWhere([
                        [
                            'p_orders.status',"For Supply"
                        ],
                        [
                            'p_requests.type','for plane tickets'
                        ],
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="generatedv btn btn-success btn-sm">Attach DV</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "Voucher Table")
            {
                //for PR/PO
                return datatables()->of(Voucher::join('p_orders', 'vouchers.po_id', '=', 'p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->select(['vouchers.id as id','p_orders.po_number as po_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks'])
                    ->where('vouchers.type','DV for PO')
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right w70"><button type="button" title="Update" name="edit" id="'.$data->id.'" class="editdv btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                        $button .= '&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" title="Remove" name="delete" id="'.$data->id.'" class="deletedv btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "Generate TO")
            {
                return datatables()->of(TOrder::where('status','Received by HRMO / Completed')
                ->orWhere('status','For HRMO')
                ->latest()->get())
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="generate_to btn btn-success btn-sm">Attach DV</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
            }    
            else if($_GET['type'] == "DV table for TO")
            {
                return datatables()->of(Voucher::join('t_orders', 'vouchers.to_id', '=', 't_orders.id')
                    ->select(['vouchers.id as id','t_orders.to_number as to_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','vouchers.remarks as remarks'])
                    ->where('vouchers.type','DV for TO')
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">'.
                                (($data->status == "Received by TOD" || $data->status == "Received by HRMO") ? '<li><a href="#" class="senddvforTO" id="'.$data->id.'">Send to OOTD</a></li>':'')
                                .'<li>
                                    <a href="#" class="editdvforTO" id="'.$data->id.'">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="deletedvforTO" id="'.$data->id.'">Delete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "OOTD DV table for TO")
            {
                return datatables()->of(Voucher::join('t_orders', 'vouchers.to_id', '=', 't_orders.id')
                    ->select(['vouchers.id as id','t_orders.to_number as to_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','vouchers.remarks as remarks'])
                    ->where([
                        [
                            'vouchers.type','DV for TO'
                        ],
                        [
                            'vouchers.status','For OOTD'
                        ]
                    ])
                    ->orWhere([
                        [
                            'vouchers.type','DV for TO'
                        ],
                        [
                            'vouchers.status','Received by OOTD'
                        ]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_dvforto" id="'.$data->id.'">Receive</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_dvforto" id="'.$data->id.'">Send to Accounting</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "accnt DV table for TO")
            {
                return datatables()->of(Voucher::join('t_orders', 'vouchers.to_id', '=', 't_orders.id')
                    ->select(['vouchers.id as id','t_orders.to_number as to_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','vouchers.remarks as remarks'])
                    ->where([
                        [
                            'vouchers.type','DV for TO'
                        ],
                        [
                            'vouchers.status','For Accounting'
                        ]
                    ])
                    ->orWhere([
                        [
                            'vouchers.type','DV for TO'
                        ],
                        [
                            'vouchers.status','Received by Accounting'
                        ]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewaccnt_dvforto" id="'.$data->id.'">Receive</a>
                                </li>
                                <li>
                                    <a href="#" class="sendaccnt_dvforto" id="'.$data->id.'">Mark as Complete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "enduser DV table for TO")
            {
                $enduser = auth()->user();
                return datatables()->of(Voucher::join('t_orders', 'vouchers.to_id', '=', 't_orders.id')
                    ->select(['vouchers.id as id','t_orders.to_number as to_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','vouchers.remarks as remarks'])
                    ->where([
                        ['vouchers.user_id', $enduser->id],
                        ['vouchers.type', 'DV for TO']
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_dvforto" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "DV for Salary")
            {
            	// in TOD account
                return datatables()->of(Voucher::join('users','vouchers.user_id','=','users.id')
                    ->select(['vouchers.id as id','description','remarks','vouchers.created_at as created_at','vouchers.updated_at as updated_at','name','status'])
                    ->where('type','DV for Salary')
                    ->latest('vouchers.created_at')->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right w70"><button type="button" title="Update" name="edit" id="'.$data->id.'" class="editforSalary btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                            $button .= '&nbsp&nbsp&nbsp';
                            $button .= '<button type="button" title="Remove" name="delete" id="'.$data->id.'" class="deleteforSalary btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                            return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "DV for PR/PO")
            {
            	// in TOD account
                return datatables()->of(Voucher::join('p_orders', 'vouchers.po_id', '=', 'p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->select(['vouchers.id as id','p_orders.po_number as po_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks'])
                    ->where([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','For TOD'
                    	]
                    ])
                    ->orWhere([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','Received by TOD'
                    	]
                    ])
                    ->orWhere([
                        [
                            'vouchers.type','DV for PO'
                        ],
                        [
                            'vouchers.status','Received by HRMO'
                        ]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewtod_dvforpo" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendtod_dvforpo" id="'.$data->id.'">Send to OOTD</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "DV for OOTD PR/PO")
            {
                return datatables()->of(Voucher::join('p_orders', 'vouchers.po_id', '=', 'p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->select(['vouchers.id as id','p_orders.po_number as po_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks'])
                    ->where([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','For OOTD'
                    	]
                    ])
                    ->orWhere([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','Received by OOTD'
                    	]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_dvforpo" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_dvforpo" id="'.$data->id.'">Send to Accounting</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "DV for accnt PR/PO")
            {
                return datatables()->of(Voucher::join('p_orders', 'vouchers.po_id', '=', 'p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->select(['vouchers.id as id','p_orders.po_number as po_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks'])
                    ->where([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','For Accounting'
                    	]
                    ])
                    ->orWhere([
                    	[
                    		'vouchers.type','DV for PO'
                    	],
                    	[
                    		'vouchers.status','Received by Accounting'
                    	]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewaccnt_dvforpo" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendaccnt_dvforpo" id="'.$data->id.'">Mark as Complete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "DV for Enduser PR/PO")
            {
                $enduser = auth()->user();
                return datatables()->of(Voucher::join('p_orders', 'vouchers.po_id', '=', 'p_orders.id')
                    ->join('p_requests','p_orders.pr_id','=','p_requests.id')
                    ->select(['vouchers.id as id','p_orders.po_number as po_number','vouchers.description as description','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks'])
                    ->where('p_requests.user_id', $enduser->id)
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_dvforpo" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "OOTD DV for Salary")
            {
                return datatables()->of(Voucher::join('users','vouchers.user_id','=','users.id')
                    ->select(['vouchers.id as id','vouchers.description as description','vouchers.remarks as remarks','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','users.name as name'])
                    ->where([
                        [
                            'vouchers.type','DV for Salary'
                        ],
                        [
                            'vouchers.status','For OOTD'
                        ]
                    ])
                    ->orWhere([
                        [
                            'vouchers.type','DV for Salary'
                        ],
                        [
                            'vouchers.status','Received by OOTD'
                        ]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_dvforsalary" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_dvforsalary" id="'.$data->id.'">Send to Accounting</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "accnt DV for Salary")
            {
                return datatables()->of(Voucher::join('users','vouchers.user_id','=','users.id')
                    ->select(['vouchers.id as id','vouchers.description as description','vouchers.remarks as remarks','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at','users.name as name'])
                    ->where([
                        [
                            'vouchers.type','DV for Salary'
                        ],
                        [
                            'vouchers.status','For Accounting'
                        ]
                    ])
                    ->orWhere([
                        [
                            'vouchers.type','DV for Salary'
                        ],
                        [
                            'vouchers.status','Received by Accounting'
                        ]
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewaccnt_dvforsalary" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendaccnt_dvforsalary" id="'.$data->id.'">Mark as complete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "user DV for Salary")
            {
                $enduser = auth()->user();
                return datatables()->of(Voucher::join('users','vouchers.user_id','=','users.id')
                    ->select(['vouchers.id as id','vouchers.description as description','vouchers.remarks as remarks','vouchers.status as status','vouchers.created_at as created_at','vouchers.updated_at as updated_at'])
                    ->where([
                        ['vouchers.user_id',$enduser->id],
                        ['vouchers.type','DV for Salary']
                    ])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_dvforsalary" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
        if(Gate::allows('isSupply'))
        {
            return view('pages.supply.voucher'); 
        }
        else if(Gate::allows('isTOD'))
        {
            $users = User::all();
            return view('pages.tod.voucher',compact('users')); 
        }
        else if(Gate::allows('isOOTD'))
        {
        	$users = User::all();
        	return view('pages.ootd.voucher',compact('users'));
        }
        else if(Gate::allows('isAccountant'))
        {
        	$users = User::all();
        	return view('pages.accountant.voucher',compact('users'));
        }
        else if(Gate::allows('isHRMO'))
        {
            $users = User::all();
            return view('pages.hrmo.voucher',compact('users'));
        }
        else if(Gate::allows('isAdmin'))
        {
            $users = User::all();
            return view('pages.voucher',compact('users'));
        }
        else if(Gate::allows('isUser'))
        {
            $users = User::all();
            return view('pages.enduser.voucher',compact('users'));
        }
        else
        {
            abort(403, 'Sorry, this page is unavailable in this account');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        event(new LiveTable('reload'));
        if($request->store == "generateTO")
        {
            $rules = array(
                'description' => 'required',
                'hidden_id' => 'required',
                'status' => 'required',
                'user_id' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            Voucher::create([
                'description' =>  $request->description,
                'status' => $request->status,
                'to_id' => $request->input('hidden_id'),
                'user_id' => $request->input('user_id'),
                'type' => $request->type,
                'remarks' => $request->remarks
            ]);
            return response()->json(['success' => 'Data Added successfully.']); 
        }
        else if($request->store == "Add DV for Salary")
        {
            $rules = array(
                'description' => 'required',
                'user_id' => 'required'
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            Voucher::create([
                'description' =>  $request->description,
                'remarks' =>  $request->remarks,
                'status' => $request->status,
                'user_id' =>  $request->input('user_id'),
                'type' => $request->type
            ]);
            return response()->json(['success' => 'Data Added successfully.']); 
        }
        else if($request->store == "generate DV for PO")
        {
            //for PR&PO
            $rules = array(
                'description' => 'required',
                'hidden_id' => 'required',
                'status' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'description'        =>  $request->description,
                'status' => $request->status,
                'po_id' => $request->input('hidden_id'),
                'type' => $request->type
            );
            $form_data2 = array(
                'status' => "Attached DV",
            );
            $form_data3 = array(
                'remarks' => $request->remarks,
            );
            Voucher::create($form_data);
            POrder::whereId($request->hidden_id)->update($form_data2);
            PRequest::whereId($request->pr_id)->update($form_data3);

            return response()->json(['success' => 'Data Added successfully.']);     
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            if($_GET['generator']=="DV")
            {
                //for PR/PO
                $data = POrder::with('prequest')->findOrFail($id);
                return response()->json(['data' => $data]);
            }
            else if($_GET['generator']=="Edit DV")
            {
                //for PR/PO
                $data = Voucher::with('porder')->findOrFail($id);
                $data2 = POrder::with('prequest')->findOrFail($data->porder->id);
                return response()->json([
                    'data' => $data,
                    'data2' => $data2,
                ]);
            }
            else if($_GET['generator']=="Generate DV for TO")
            {
                $data = TOrder::findOrFail($id);
                return response()->json(['data' => $data]);
            }
            else if($_GET['generator']=="Edit DV for TO")
            {
                $data = Voucher::with('torder')->findOrFail($id);
                return response()->json(['data' => $data]);
            }
            else if($_GET['generator'] == "Edit DV for Salary")
            {
                $data = Voucher::with('user')->findOrFail($id);
                return response()->json(['data' => $data]);
            }
            else if($_GET['generator']=="Edit DV for PO")
            {
                //in TOD account && OOTD && accounting
                $data = Voucher::with('porder')->findOrFail($id);
                $data2 = POrder::with('prequest')->findOrFail($data->porder->id);
                return response()->json([
                    'data' => $data,
                    'data2' => $data2,
                ]);
            }
            else if($_GET['generator'] == "Edit OOTD DV for Salary")
            {
                $data = Voucher::with('user')->findOrFail($id);
                return response()->json(['data' => $data]);
            }  
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {
        event(new LiveTable('reload'));
        if($request->update == "Update DV for TO")
        {
            // TOD account
            $rules = array(
                'description' => 'required',
                'to_id' => 'required',
                'user_id' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            Voucher::whereId($request->hidden_id)->update([
                'description' => $request->description,
                'user_id' => $request->input('user_id'),
                'remarks' => $request->remarks
            ]);
            return response()->json(['success' => 'Data updated successfully.']); 
        }
        else if($request->update == "sendTODDVforTO")
        {
            Voucher::whereId($request->hidden_id)->update(['status' => "For OOTD"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "Update OOTD DV for TO")
        {
            Voucher::whereId($request->hidden_id)->update([
                'status' => 'Received by OOTD',
                'remarks' => $request->remarks
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendOOTDDVforTO")
        {
            Voucher::whereId($request->hidden_id)->update(['status' => "For Accounting"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "Update accnt DV for TO")
        {
            $rules = array(
                'amount' => 'required',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $amount = $request->amount;
            if(!is_numeric($amount)) {

                 $amount = str_replace(',', '', $amount);
            }
            Voucher::whereId($request->hidden_id)->update([
                'status' => 'Received by Accounting',
                'remarks' => $request->remarks,
                'amount' => $amount
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendaccntDVforTO")
        {
            Voucher::whereId($request->hidden_id)->update(['status' => "Completed"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "Edit DV for Salary")
        {
            $rules = array(
                'description' => 'required',
                'user_id' => 'required'
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            Voucher::whereId($request->hidden_id)->update([
                'description' =>  $request->description,
                'remarks' =>  $request->remarks,
                'user_id' =>  $request->input('user_id'),
            ]);
            return response()->json(['success' => 'Data updated successfully.']);
        }
        else if($request->update == "Update DV for PO")
        {
        	// in TOD Account
        	Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by TOD",
            ]);
            PRequest::whereId($request->pr_id)->update([
                'remarks' => $request->remarks,
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "HRMO Update DV for PO")
        {
            // in HRMO Account
            Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by HRMO",
            ]);
            PRequest::whereId($request->pr_id)->update([
                'remarks' => $request->remarks,
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendTODPO")
        {
        	Voucher::whereId($request->hidden_id)->update(['status' => "For OOTD"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "Update DV for OOTD PO")
        {
        	// in OOTD Account
        	Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by OOTD",
            ]);
            PRequest::whereId($request->pr_id)->update([
                'remarks' => $request->remarks,
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendOOTDPO")
        {
        	Voucher::whereId($request->hidden_id)->update(['status' => "For Accounting"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "Update DV for accnt PO")
        {
        	// accounting
            $rules = array(
                'amount' => 'required',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            $amount = $request->amount;
            if(!is_numeric($amount)) {

                 $amount = str_replace(',', '', $amount);
            }
        	Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by Accounting",
                'amount' => $amount
            ]);
            PRequest::whereId($request->pr_id)->update([
                'remarks' => $request->remarks,
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendaccntPO")
        {
        	Voucher::whereId($request->hidden_id)->update(['status' => "Completed"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "OOTD Received DV for Salary")
        {
            Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by OOTD",
                'remarks' => $request->remarks
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendOOTDDVforSalary")
        {
            Voucher::whereId($request->hidden_id)->update(['status' => "For Accounting"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else if($request->update == "accnt Received DV for Salary")
        {
            $rules = array(
                'amount' => 'required',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $amount = $request->amount;
            if(!is_numeric($amount)) {

                 $amount = str_replace(',', '', $amount);
            }

            Voucher::whereId($request->hidden_id)->update([
                'status' => "Received by Accounting",
                'remarks' => $request->remarks,
                'amount' => $amount
            ]);
            return response()->json(['success' => 'Data received successfully.']); 
        }
        else if($request->update == "sendaccntDVforSalary")
        {
            Voucher::whereId($request->hidden_id)->update(['status' => "Completed"]);
            return response()->json(['success' => 'Data sent successfully.']); 
        }
        else
        {
           //update DV for PR&PO
            $rules = array(
                'description' => 'required',
                'pr_number' => 'required',
                'po_id' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'description' =>  $request->description,
                'po_id' => $request->po_id,
            );
            $form_data2 = array(
                'po_number' => $request->po_number,
            );
            $form_data3 = array(
                'pr_number' => $request->pr_number,
                'remarks' => $request->remarks,
            );

            Voucher::whereId($request->hidden_id)->update($form_data);
            POrder::whereId($request->po_id)->update($form_data2);
            PRequest::whereId($request->pr_id)->update($form_data3);

            return response()->json(['success' => 'Data updated successfully.']); 
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = Voucher::findOrFail($id);
        $data->delete();
    }
}
