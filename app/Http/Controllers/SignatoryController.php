<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\LiveTable;
use App\Signatory;
use App\Designation;
use App\Office;
use App\Zone;
use Gate;
use Validator;

class SignatoryController extends Controller
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

            // return datatables()->of(Signatory::latest()->get())
            //         ->addColumn('action', function($data){
            //             $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-success btn-sm">Edit</button>';
            //             $button .= '&nbsp&nbsp&nbsp';
            //             $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
            //             return $button;
            //         })
            //         ->rawColumns(['action'])
            //         ->make(true);


                return datatables()->of(Signatory::join('designations', 'signatories.designation_id', '=', 'designations.id')
                    ->join('offices', 'signatories.office_id', '=', 'offices.id')
                    ->join('zones', 'signatories.zone_id', '=', 'zones.id')
                    ->select(['signatories.id as id','signatories.name as name','designations.description as designation','offices.description as office','zones.name as zone','signatories.signature as signature'])

                    ->orderBY('signatories.name'))
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="editsignatory btn btn-success btn-sm">Edit</button>';
                        $button .= '&nbsp&nbsp&nbsp';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="deletesignatory btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            
        }
        if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{

            $designation = Designation::all();
            $office = Office::all();
            $zone = Zone::all();
            return view('pages.signatories',compact('designation','office','zone'));  
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
            'name'    =>  'required',
            'signature' => 'required',
            'designation' => 'required',
            'office' => 'required',
            'zone_id' => 'required',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'        =>  $request->name,
            'signature' => $request->signature,
            'designation_id' => $request->input('designation'),
            'office_id' => $request->input('office'),
            'zone_id' => $request->input('zone_id'),

        );
        
        Signatory::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Signatory::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signatory $signatory)
    {
        event(new LiveTable('reload'));
        $rules = array(
            'name'    =>  'required',
            'signature' => 'required',
            'designation' => 'required',
            'office' => 'required',
            'zone_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'name'        =>  $request->name,
            'signature' => $request->signature,
            'designation_id' => $request->input('designation'),
            'office_id' => $request->input('office'),
            'zone_id' => $request->input('zone_id'),
        );
        Signatory::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = Signatory::findOrFail($id);
        $data->delete();
    }
}
