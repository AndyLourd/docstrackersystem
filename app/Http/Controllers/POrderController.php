<?php

namespace App\Http\Controllers;

use App\POrder;
use Illuminate\Http\Request;
use App\Events\LiveTable;
use App\PRequest;
use Gate;
use Validator;

class POrderController extends Controller
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
            if($_GET['type']=="Generate Number")
            {
                return datatables()->of(PRequest::latest()->get()
                ->where('status',"For Supply"))
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" id="'.$data->id.'" class="generatepo btn btn-success btn-sm">Attach PO</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            else if($_GET['type']=="for OOTD PO")
            {
                return datatables()->of(POrder::join('p_requests', 'p_orders.pr_id', '=', 'p_requests.id')
                    ->select(['p_orders.id as id','p_orders.po_number as po_number','p_orders.description as description','p_orders.created_at as created_at','p_orders.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks','p_orders.status as status'])
                    ->where('p_orders.status',"For OOTD")
                    ->orWhere('p_orders.status',"Received By OOTD")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewootd_po" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendootd_po" id="'.$data->id.'">Send to Accounting</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type']=="for Accounting PO")
            {
                return datatables()->of(POrder::join('p_requests', 'p_orders.pr_id', '=', 'p_requests.id')
                    ->select(['p_orders.id as id','p_orders.po_number as po_number','p_orders.description as description','p_orders.created_at as created_at','p_orders.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks','p_orders.status as status'])
                    ->where('p_orders.status',"For Accounting")
                    ->orWhere('p_orders.status',"Received by Accounting")
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewaccnt_po" id="'.$data->id.'">View</a>
                                </li>
                                <li>
                                    <a href="#" class="sendaccnt_po" id="'.$data->id.'">Send to Supply</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else if($_GET['type']=="for Enduser PO")
            {
                $enduser = auth()->user();
                return datatables()->of(POrder::join('p_requests', 'p_orders.pr_id', '=', 'p_requests.id')
                    ->select(['p_orders.id as id','p_orders.po_number as po_number','p_orders.description as description','p_orders.created_at as created_at','p_orders.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks','p_orders.status as status'])
                    ->where('p_requests.user_id', $enduser->id)
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="viewuser_po" id="'.$data->id.'">View</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            else{
                //PO table Supply
                return datatables()->of(POrder::join('p_requests', 'p_orders.pr_id', '=', 'p_requests.id')
                    ->select(['p_orders.id as id','p_orders.po_number as po_number','p_orders.description as description','p_orders.created_at as created_at','p_orders.updated_at as updated_at','p_requests.pr_number as pr_number','p_requests.remarks as remarks','p_orders.status as status'])
                    ->latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<div class="td-actions text-right w70"><button type="button" title="Update" name="edit" id="'.$data->id.'" class="editpo btn btn-success btn-sm"><i class="material-icons">edit</i></button>';
                        $button .= '&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" title="Remove" name="delete" id="'.$data->id.'" class="deletepo btn btn-danger btn-sm"><i class="material-icons">close</i></button></div>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
        
        if(Gate::allows('isSupply'))
        {
            $pr = PRequest::all();
            return view('pages.supply.po',compact('pr')); 
        }
        else if(Gate::allows('isOOTD'))
        {
            return view('pages.ootd.po'); 
        }
        else if(Gate::allows('isAccountant'))
        {
            return view('pages.accountant.po'); 
        }
        else if(Gate::allows('isUser'))
        {
            return view('pages.enduser.po'); 
        }
        else if(Gate::allows('isAdmin'))
        {
            return view('pages.po'); 
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
            'po_number'    =>  ['required',"unique:p_orders"],
            'description' => 'required',
            'hidden_id' => 'required',
            'status' => 'required',
            'pr_number' => 'required',
            'descriptionpr' => 'required',
            'purpose' => 'required',
            'type' => 'required',
            'remarks',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'po_number'        =>  $request->po_number,
            'description' => $request->description,
            'pr_id' => $request->input('hidden_id'),
            'status' => $request->status,
        );
        $form_data2 = array(
            'pr_number' => $request->pr_number,
            'description' => $request->descriptionpr,
            'purpose' => $request->purpose,
            'remarks' => $request->remarks,
            'type' => $request->type,
            'status' => "Attached PO",
        );
        
        POrder::create($form_data);
        PRequest::whereId($request->hidden_id)->update($form_data2);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\POrder  $pOrder
     * @return \Illuminate\Http\Response
     */
    public function show(POrder $pOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\POrder  $pOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            if($_GET['editpo']=="ou")
            {
                $data = POrder::with('prequest')->findOrFail($id);
                return response()->json(['data' => $data]);
            }
            else{
                //Generate PO table
                $data = PRequest::findOrFail($id);
                return response()->json(['data' => $data]);
            }            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\POrder  $pOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, POrder $pOrder)
    {
        event(new LiveTable('reload'));
        if($request->update == "ootdpo")
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
                'status'        =>  $request->status,
            );
            $form_data2 = array(
                'remarks' => $request->remarks,
            );
            
            POrder::whereId($request->hidden_id)->update($form_data);
            PRequest::whereId($request->pr_id)->update($form_data2);

            return response()->json(['success' => 'Data Added successfully.']);
        }
        else if($request->update == "sendOOTDPO")
        {
            POrder::whereId($request->hidden_id)->update(['status' => 'For Accounting']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else if($request->update == "accntpo")
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
                'status'        =>  $request->status,
            );
            $form_data2 = array(
                'remarks' => $request->remarks,
            );
            
            POrder::whereId($request->hidden_id)->update($form_data);
            PRequest::whereId($request->pr_id)->update($form_data2);

            return response()->json(['success' => 'Data Added successfully.']);
        }
        else if($request->update == "sendaccntPO")
        {
            POrder::whereId($request->hidden_id)->update(['status' => 'For Supply']);
            return response()->json(['success' => 'Data sent successfully.']);
        }
        else
        {
            //Supply PO
            $rules = array(
                'po_number'    =>  'required',
                'description' => 'required',
                'pr_number' => 'required',
                'remarks',
            );
            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $form_data = array(
                'po_number'        =>  $request->po_number,
                'description' => $request->description,
            );
            $form_data2 = array(
                'pr_number' => $request->pr_number,
                'remarks' => $request->remarks,
            );
            
            POrder::whereId($request->porder_id)->update($form_data);
            PRequest::whereId($request->pr_id)->update($form_data2);

            return response()->json(['success' => 'Data Added successfully.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\POrder  $pOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = POrder::findOrFail($id);
        $data->delete();
    }
}
