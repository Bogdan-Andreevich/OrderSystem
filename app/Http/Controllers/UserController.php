<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Redirect;

use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $response = ['status' => false];

        $dt_params = json_decode($_GET['dt_params'], true);
        $request->merge(['page' => ((array_key_exists('page', $dt_params)) ? $dt_params['page'] : 0)]);


        $sql = 'SELECT
                    *
                /*D*/
                FROM
                    users
                    /* WHERE role!=\'admin\' */
                ORDER BY
                    id DESC

                /*D*/
                LIMIT :limit
                OFFSET :offset';


        $stmt = DB::connection()->getPdo()->prepare($sql);
        $stmt->execute([
            'limit' => $dt_params['rows'],
            'offset' => $dt_params['rows'] * $request->page
        ]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);



        /*--------------------- count SQL -------------------------*/
        list($str1, $str2, $str3) = explode('/*D*/', $sql);
        $count_sql = 'SELECT COUNT(*) as totalRecords '.$str2; //dd($count_sql);
        $stmt = DB::connection()->getPdo()->prepare($count_sql);
        $stmt->execute();
        $totalRecords = $stmt->fetch(\PDO::FETCH_ASSOC)['totalRecords'];



        $statuses = [
            '1'=> ['code'=>1, 'name'=>'Активний'],
            '2'=> ['code'=>2, 'name'=>'Не активний']
        ];

        foreach ($records as $i=>$record) {
            $records[$i]['status'] = $statuses[ ''.$record['status'] ];
        }


        $response['status'] = true;
        $response['records'] = $records;
        $response['totalRecords'] =   $totalRecords;

        return $response;

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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $res = ['status' => false];

        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);


        /*----------------- Валідація -----------------*/
        /*$validator = $this->validate_record( $request_body, $mode='update' );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ], 422);
        }*/



        $robject = User::where('id', '=', $id)->first();

        $robject->fill($request_body);
        $robject->save();


        $res['status'] = true;

        return $res;



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




    public function login_as_user($id)
    {
        $user = User::find($id);

        Auth::login( $user );

        return Redirect::home();
    }


}
