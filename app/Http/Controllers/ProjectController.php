<?php

namespace App\Http\Controllers;

use App\Project;
use App\Events\LiveTable;
use Illuminate\Http\Request;
use Validator;
use Gate;

class ProjectController extends Controller
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
            return datatables()->of(Project::latest()->get())
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="btn btn-round btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">calendar_view_day</i>
                            <span class="caret"></span>
                            <div class="ripple-container"></div></button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" x-placement="top-end" style="position: absolute; top: -177px; left: -69px; will-change: top, left;">
                                <li>
                                    <a href="#" class="edit_project" id="'.$data->id.'">Edit</a>
                                </li>
                                <li>
                                    <a href="#" class="delete_project" id="'.$data->id.'">Delete</a>
                                </li>
                            </ul>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
             return view('pages.project');  
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
            'name'    =>  'required|unique:projects',
        );
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name'        =>  $request->name,
        );

        Project::create($form_data);

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Project::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        event(new LiveTable('reload'));
        $rules = array(
                'name'    =>  'required|unique:projects',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $form_data = array(
            'name'       =>   $request->name,
        );
        Project::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        event(new LiveTable('reload'));
        $data = Project::findOrFail($id);
        $data->delete();
    }
}
