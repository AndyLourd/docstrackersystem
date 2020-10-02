<?php

namespace App\Http\Controllers;

use App\TOrder;
use Illuminate\Http\Request;
use App\Events\LiveTable;
use App\User;
use App\TOUser;
use Gate;
use Validator;

class TOrderController extends Controller
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
            if($_GET['account']=="TOD")
            {
                return datatables()->of(TOrder::where('status','For TOD')
                    ->orWhere('status','Received by TOD')
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewtod_to" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendtod_to" id="'.$data->id.'">Send to OOTD</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['account']=="HRMO")
            {
                return datatables()->of(TOrder::latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">'
                                . (($data->status == "For HRMO") ? '<li><a href="#" class="receiveto" id="'.$data->id.'">Receive</a></li>':'').
                                '<li>
                                    <a href="#" class="editto" id="'.$data->id.'">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="deleteto" id="'.$data->id.'">Delete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['account']=="OOTD")
            {
                return datatables()->of(TOrder::where('status','For OOTD')
                    ->orWhere('status','Received by OOTD')
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_to" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_to" id="'.$data->id.'">Send to HRMO</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['account']=="enduser")
            {
                $enduser = auth()->user();
                return datatables()->of(TOrder::join('t_o_users','t_orders.id','=','t_o_users.to_id')
                    ->select('t_orders.id as id','t_orders.to_number as to_number','t_orders.destination as destination','t_orders.inclusive_date as inclusive_date', 't_orders.purpose as purpose','t_orders.remarks as remarks','t_orders.status as status')
                    ->where('t_o_users.user_id',$enduser->id)
                    ->orWhere('t_o_users.user_id',0)
                    ->latest('t_orders.created_at')->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_to" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
        if(Gate::allows('isHRMO'))
        {
            $users = User::all();
            return view('pages.hrmo.to',compact('users')); 
        }
        else if(Gate::allows('isTOD'))
        {
            $users = User::all();
            return view('pages.tod.to',compact('users')); 
        }
        else if(Gate::allows('isOOTD'))
        {
            $users = User::all();
            return view('pages.ootd.to',compact('users')); 
        }
        else if(Gate::allows('isUser'))
        {
            $users = User::all();
            return view('pages.enduser.to',compact('users')); 
        }
        else if(Gate::allows('isAdmin'))
        {
            $users = User::all();
            return view('pages.to',compact('users')); 
        }
        else
        {
            abort(403, 'Sorry, this page is unavailable on this account');
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
            'to_number'    =>  'required',
            'destination' => 'required',
            'purpose' => 'required',
            'from' => 'required',
            'user' => 'required',
            'status' => 'required',
            'remarks',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $formdata = array(
            'to_number' =>  $request->to_number,
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'inclusive_date' => $request->from.' - '.$request->to,
            'status' => $request->status,
            'remarks' => $request->remarks,
        );
        TOrder::create($formdata);

        $items = $request->input('user');
        $items = implode(',', $items);
        $user = $request->except($items);
        $user['items'] = $user;

        $users = $request->input('user');
        $count = 0;
        $id = TOrder::select('id')->where('to_number',$request->to_number)->get('id')->toArray();
        foreach ($users as $usercount) {
            TOUser::create([
                'to_id' =>  $id[0]['id'],
                'user_id' => $user['user'][$count],
            ]);
            $count++;
        }        
        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TOrder  $tOrder
     * @return \Illuminate\Http\Response
     */
    public function show(TOrder $tOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TOrder  $tOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = TOrder::with('tousers')->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TOrder  $tOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TOrder $tOrder)
    {
        event(new LiveTable('reload'));
        if($request->update == "TODreceiveTO")
        {
            TOrder::whereId($request->hidden_id)->update([
                'status'=>'Received by TOD',
                'remarks'=> $request->remarks
            ]);
            return response()->json(['success' => 'Data received successfully.']);
        }
        else if($request->update == "sendTODTO")
        {
            TOrder::whereId($request->hidden_id)->update(['status'=>'For OOTD']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else if($request->update == "OOTDreceiveTO")
        {
            TOrder::whereId($request->hidden_id)->update([
                'status'=>'Received by OOTD',
                'remarks'=> $request->remarks
            ]);
            return response()->json(['success' => 'Data received successfully.']);
        }
        else if($request->update == "sendOOTDTO")
        {
            TOrder::whereId($request->hidden_id)->update(['status'=>'For HRMO']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else if($request->update == "receiveHRMOTO")
        {
            TOrder::whereId($request->hidden_id)->update([
                'status'=>'Received by HRMO / Completed',
                'remarks'=> $request->remarks
            ]);
            return response()->json(['success' => 'Data received successfully.']);
        }
        else
        {
            //HRMO TO
            $rules = array(
                'to_number'    =>  'required',
                'destination' => 'required',
                'purpose' => 'required',
                'from' => 'required',
                'user' => 'required',
                'status' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $formdata = array(
                'to_number' =>  $request->to_number,
                'destination' => $request->destination,
                'purpose' => $request->purpose,
                'inclusive_date' => $request->from.' - '.$request->to,
                'remarks' => $request->remarks,
            );
            TOrder::whereId($request->hidden_id)->update($formdata);

            $items = $request->input('user');
            $items = implode(',', $items);
            $user = $request->except($items);
            $user['items'] = $user;

            $count = 0;
            $id = TOrder::select('id')->where('to_number',$request->to_number)->get('id')->toArray();
            $idtodelete = TOUser::select('id')->where('to_id',$id[0]['id'])->get('id')->toArray();
            
            foreach ($idtodelete as $del) {
                $delete = TOUser::findOrFail($idtodelete[$count]['id']);
                $delete->delete();
                $count++;
            } 

            $count=0;
            $users = $request->input('user');
            foreach ($users as $usercount) {
                TOUser::create([
                    'to_id' =>  $id[0]['id'],
                    'user_id' => $user['user'][$count],
                ]);
                $count++;
            }       
            return response()->json(['success' => 'Data Added successfully.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TOrder  $tOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = TOrder::findOrFail($id);
        $data->delete();

        $count = 0;
        $data2 = TOUser::select('id')->where('to_id',$data->id)->get('id')->toArray();
        foreach ($data2 as $del) {
            $delete = TOUser::findOrFail($data2[$count]['id']);
            $delete->delete();
            $count++;
        }
    }
}
