<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;



use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OrderStatus;


class AccountController extends Controller
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


        $data = [
            'tmpl_data'=> [
                'order_statuses' => []
            ]
        ];

        return view('account.index', $data);

    }


    public function ajax_index(){
        $response = ['status' => false];


        return $response;

    }







    public function settings()
    {
        return view('account.settings');
    }

    public function settings_update(Request $request)
    {


        if( $request->has('other__settings') ){
            return $this->other__settings_update($request);
        }



        $auth = Auth::user();


        if(
            empty($request->input('old_password'))
            AND
            empty($request->input('new_password'))
            AND
            empty($request->input('new_password_confirmation'))

        ){

            $this->validate($request, [
                'name' =>'required|string|max:255',
            ]);

            try{

                $user = Auth::user();
                $user->update( $request->all() );
                return back()->with('status','Profile Updated');

            }catch(Exception $e){
                return back()->with('error','server error');
            }

        }



        $this->validate($request, [
            'name' =>'required|string|max:255',
            'old_password' => 'required|string',
            'new_password' => 'required|confirmed|min:4|string'
        ]);


        if (!Hash::check($request->get('old_password'), $auth->password))
        {
            return back()->with('error', "Current Password is Invalid");
        }



        // The passwords matches
        if (!Hash::check($request->get('old_password'), $auth->password))
        {
            return back()->with('error', "Current Password is Invalid");
        }

        // Current password and new password same
        if (strcmp($request->get('old_password'), $request->new_password) == 0)
        {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }

        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();

        return back()->with('status', "Changes saved successfully");



    }





    public function other__settings_update(Request $request){

        $this->validate($request, [
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
            'operatorid_in_crm' => 'nullable|integer|max:999999999',
        ]);


        try{
            unset($request['other__settings']);

            $user = Auth::user();
            $user->update( $request->all() );
            return back()->with('status', 'Налаштування оновлено');

        }catch(Exception $e){
            return back()->with('error','server error');
        }

    }










}
