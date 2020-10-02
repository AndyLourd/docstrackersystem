<?php

namespace App\Http\Controllers;

use App\Canvass;
use Illuminate\Http\Request;
use App\Events\LiveTable;
use App\PRequest;
use App\User;
use Gate;
use Validator;

class CanvassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Canvass  $canvass
     * @return \Illuminate\Http\Response
     */
    public function show(Canvass $canvass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Canvass  $canvass
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PRequest::with('canvass')->findOrFail($id);
        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Canvass  $canvass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Canvass $canvass)
    {
        event(new LiveTable('reload'));
        if($request->mayors_permit && $request->clearance == true)
        {
            $status = "Completed";
        }
        else
        {
            $status = "Incomplete";
        }

        $form_data = array(
            'mayors_permit' =>  $request->mayors_permit,
            'clearance' => $request->clearance,
            'status' => $status
        );
        
        Canvass::whereId($request->hidden_id)->update($form_data);
        PRequest::whereId($request->hidden_id)->update(['remarks' => $request->remarks]);
        return response()->json(['success' => 'Data Added successfully.']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Canvass  $canvass
     * @return \Illuminate\Http\Response
     */
    public function destroy(Canvass $canvass)
    {
        //
    }
}
