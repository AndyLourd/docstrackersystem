<?php

namespace App\Http\Controllers;

use App\PRequest;
use Illuminate\Http\Request;
use App\User;
use App\Canvass;
use App\Events\LiveTable;
use Gate;
use Validator;

class PRequestController extends Controller
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
            if($_GET['type'] == "50k+")
            {
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('p_requests.type',"50K+")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right"><button type="button" title="Update" name="edit" id="'.$data->id.'" class="editpr btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                        $button.='&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" title="Remove" name="delete" id="'.$data->id.'" class="deletepr btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "50k below")
            {
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('p_requests.type',"50k below")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right"><button type="button" rel="tooltip" title="Update" rel="tooltip" name="edit" id="'.$data->id.'" class="editpr2 btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                        $button.='&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" rel="tooltip" title="Remove" name="delete" id="'.$data->id.'" class="deletepr2 btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "for plane tickets")
            {
                //for plane tickets
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('p_requests.type',"for plane tickets")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right w70"><button type="button" title="Update" name="edit" id="'.$data->id.'" class="editpr3 btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                        $button.='&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" title="Remove" name="delete" id="'.$data->id.'" class="deletepr3 btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "for TOD PR")
            {
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('p_requests.status',"For TOD")
                    ->orWhere('p_requests.status',"Received By TOD")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewtod_pr" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendtod_pr" id="'.$data->id.'">Send to OOTD</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "for OOTD PR")
            {
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('p_requests.status',"For OOTD")
                    ->orWhere('p_requests.status',"Received By OOTD")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_pr" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_pr" id="'.$data->id.'">Send to Supply</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type'] == "for enduser PR")
            {
                $enduser = auth()->user();
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','p_requests.remarks as remarks','p_requests.status as status'])
                    ->where('users.name', $enduser->name)
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_pr" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type']=="PR for canvass")
            {              
                return datatables()->of(PRequest::join('users', 'p_requests.user_id', '=', 'users.id')
                    ->join('canvasses','p_requests.id','=','canvasses.pr_id')
                    ->select(['p_requests.id as id','p_requests.pr_number as pr_number','p_requests.description as description','p_requests.purpose as purpose','p_requests.created_at as created_at','p_requests.updated_at as updated_at','users.name as name','p_requests.remarks as remarks','canvasses.status as status','mayors_permit','clearance'])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="addreq" id="'.$data->id.'">Add Requirements</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
        if(Gate::allows('isSupply')){
            $users = User::all();
            return view('pages.supply.pr',compact('users'));   
        }
        else if(Gate::allows('isTOD'))
        {
            $users = User::all();
            return view('pages.tod.pr',compact('users'));
        }
        else if(Gate::allows('isOOTD')){

            $users = User::all();
            return view('pages.ootd.pr',compact('users'));  
        }
        else if(Gate::allows('isUser')){
            $users = User::all();
            return view('pages.enduser.pr',compact('users'));  
        }
        else if(Gate::allows('isCanvasser')){
            $users = User::all();
            return view('pages.canvasser.pr',compact('users'));  
        }
        else if(Gate::allows('isAdmin')){
            $users = User::all();
            return view('pages.pr',compact('users'));  
        }
        else{
            abort(403, 'Sorry, this page is unavailable on this account.');
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
        $rules = array(
            'pr_number'    =>  ['required',"unique:p_requests"],
            'description' => 'required',
            'purpose' => 'required',
            'user_id' => 'required',
            'status' => 'required',
            'type' => 'required',
            'remarks',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'pr_number'        =>  $request->pr_number,
            'description' => $request->description,
            'purpose' => $request->purpose,
            'user_id' => $request->input('user_id'),
            'status' => $request->status,
            'type' => $request->type,
            'remarks' => $request->remarks,

        );
        
        PRequest::create($form_data);

        $id = PRequest::where('pr_number',$request->pr_number)->get('id')->toArray();
        Canvass::create([
            'status' => "Incomplete",
            'pr_id' => $id[0]['id'],
        ]);
        
        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PRequest  $pRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PRequest $pRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PRequest  $pRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = PRequest::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PRequest  $pRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PRequest $pRequest)
    {
        event(new LiveTable('reload'));
        if($request->update == "todpr")
        {
            $rules = array(
                'status' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'remarks' => $request->remarks,
                'status' => $request->status
            );
            
            PRequest::whereId($request->hidden_id)->update($form_data);

            return response()->json(['success' => 'Data received successfully.']);
        }
        else if($request->update == "sendTODPR")
        {
            PRequest::whereId($request->hidden_id)->update(['status' => 'For OOTD']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else if($request->update == "ootdpr")
        {
            $rules = array(
                'status' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'remarks' => $request->remarks,
                'status' => $request->status
            );
            
            PRequest::whereId($request->hidden_id)->update($form_data);

            return response()->json(['success' => 'Data received successfully.']);
        }
        else if($request->update == "sendOOTDPR")
        {
            PRequest::whereId($request->hidden_id)->update(['status' => 'For Supply']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else
        {
            // for Supply PR
            $rules = array(
                'pr_number'    =>  'required',
                'description' => 'required',
                'purpose' => 'required',
                'user_id' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'pr_number'        =>  $request->pr_number,
                'description' => $request->description,
                'purpose' => $request->purpose,
                'user_id' => $request->input('user_id'),
                'remarks' => $request->remarks,
            );
            
            PRequest::whereId($request->hidden_id)->update($form_data);
            return response()->json(['success' => 'Data Added successfully.']);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PRequest  $pRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = PRequest::findOrFail($id);
        $data->delete();
    }
}
