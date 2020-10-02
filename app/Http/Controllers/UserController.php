<?php

namespace App\Http\Controllers; 

use App\User;
use App\Project;
use App\Usertype;
use App\Events\LiveTable;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
             return view('users.index', ['users' => $model->paginate(15)]);
        }
        
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
            // $zones = Zone::all();
            // $offices = Office::all();
            // $designations = Designation::all();
            $projects = Project::all();
            $usertypes = Usertype::all();
            return view('users.create',compact('projects','usertypes'));
        }
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        event(new LiveTable('reload'));
        $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());
        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user) 
    {
        if(!Gate::allows('isAdmin')){
            abort(403, 'Sorry, this page is for system administrator.');
        }
        else{
            $projects = Project::all();
            $users = User::all();
            $usertypes = Usertype::all();
            return view('users.edit', compact('user','users','projects','usertypes'));
        }      
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User  $user)
    {
        event(new LiveTable('reload'));
        // $user->update(
        //     $request->merge(['password' => Hash::make($request->get('password'))])
        //         ->except([$request->get('password') ? '' : 'password']
        // ));
        $user->update($request->all());

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        event(new LiveTable('reload'));
        $user->delete();
        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }
}
