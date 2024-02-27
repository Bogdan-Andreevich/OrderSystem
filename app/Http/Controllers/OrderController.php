<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


use App\Models\Order;


use Illuminate\Support\Facades\Log;




class OrderController extends Controller
{
    //


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $response = ['status' => false];

        $dt_params = json_decode($_GET['dt_params'], true);
        $request->merge(['page' => ((array_key_exists('page', $dt_params)) ? $dt_params['page'] : 0)]);





        $startDate = date('Y-m-d 00:00:00', strtotime($dt_params['filters']['daterange']['dates'][0])); // "2023-12-29T15:18:37.507Z"
        $endDate =  date('Y-m-d 23:59:59', strtotime($dt_params['filters']['daterange']['dates'][1]));

        $searchTerm = $dt_params['filters']['query_string'];

        $operatorid_in_crm = Auth::user()->operatorid_in_crm;


        $records = Order::with('requests')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($searchTerm != '', function ($query) use ($searchTerm) {

                return $query->where(function ($query) use ($searchTerm) {
                    $query->where('phone1', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('phone2', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('phone3', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('name', 'LIKE', '%' . $searchTerm . '%');
                });

            })
            ->when(Auth::user()->role != 'admin', function ($query) use ($operatorid_in_crm) {
                return $query->where('operatorid', '=', $operatorid_in_crm);
            })
            ->limit( $dt_params['rows'] )
            ->offset($dt_params['rows'] * $request->page)
            ->get();

        $totalRecords = Order::count();

        $response['status'] = true;
        $response['items'] = $records;
        $response['totalRecords'] =  $totalRecords;

        $model = new Order();
        $response['prop_names'] = $model->prop_names;

        return $response;

    }






    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $res = [];
        $tables = ['city','street', 'ordertype'];


        foreach($tables as $table){

            $url = 'http://193.169.189.29/bot/api/dict.php?table='.$table;
            $authorization = 'Basic Ym90c2FueWNoMjo4TnRMNzU1SE1jOWtk';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'Accept-Language: en-US,en;q=0.9,ru;q=0.8,fr;q=0.7,uk;q=0.6',
                'Authorization: ' . $authorization,
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Pragma: no-cache',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
            ]);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if ($response === false) {
                $error = curl_error($ch);
                // Handle the error
                //echo 'cURL error: ' . $error;
                $res[$table] = 'cURL error: ' . $error;

            } else {
                // Process the response
                //echo $response;
                $res[$table] = json_decode($response, true);
            }

            curl_close($ch);

        }






        $res['reasons_for_refusal'] = config('dictionaries.reasons_for_refusal');



        return json_encode($res);
    }


    function get_ordertype_detail( $order_type ){

        $url = 'http://193.169.189.29/bot/api/dict.php?table=ordertype-detail&ordertype='.$order_type;
        $res = $this->send_request( $url );

        return json_encode($res);
    }












    public function edit($id){

    }






    public function store()
    {
        $res = ['status' => false];

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        $data['operatorid'] = Auth::user()->operatorid_in_crm;


        /*----------------- логування -----------------*/
        $data['questions_and_answers'] = json_encode($data['questions_and_answers']);
        $data['request'] = json_encode($data);
        $data['response'] = '';
        $order = Order::create($data);



        /*----------------- Валідація -----------------*/
        $validator = $this->validate_record($data);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

            //https://193.169.189.29/bot/api/createorder.php



        Log::channel('app_log')->info( json_encode($data) );

        $res['api_result'] = $this->send_request( $url='https://193.169.189.29/bot/api/createorder.php', $data);

        Log::channel('app_log')->info( json_encode($res['api_result']) );
        Log::channel('app_log')->info('-----------------------------------------------------------------------------');


        /*----------------- логування -----------------*/
        $order->response = json_encode($res['api_result']);
        $order->save();


        return $res;
    }








    public function update($id)
    {
        $res = ['status' => false];

        return $res;
    }





















    public function validate_record( $data, $mode='store' )
    {

        $rule_arr = [
            'status' => 'required|in:order,repeat,refuse,commun',

            'ordertype' => 'nullable|integer',

            'name' => 'nullable|string|max:64',

            'comment' => 'required|string|max:1024',
            'comment_inner' => 'nullable|string|max:1024',

            'phone1' => 'required|string|max:14',
            'phone2' => 'nullable|string|max:14',
            'phone3' => 'nullable|string|max:14',
            //'phone4' => 'nullable|string|max:14',

            'cityid' => 'required|integer',
            'streetid' => 'nullable|integer',
            'street' => 'nullable|string|max:128',
            'house' => 'nullable|string|max:64',
            'flat' => 'nullable|string|max:64',
            'operatorid' => 'required|integer',
            'reason' => 'nullable|integer',
            'nextcontact' => 'nullable|string|max:64',

             'date' => 'nullable|string|max:64',
             'time' => 'nullable|string|max:64'

        ];


        $attribute_names = [
            'status' => 'Статус',

            'ordertype' =>  'Тип замовлення',

            'name' =>  'Імя',

            'comment' =>  'Коментра',
            'comment_inner' =>  'Внутрішній коментар',

            'phone1' =>  'Телефон',
            'phone2' =>  'Телефон',
            'phone3' =>  'Телефон',
            //'phone4' =>  'Телефон',

            'cityid' =>  'Місто',
            'streetid' =>  'Вулиця',
            'street' =>  'Вулиця',
            'house' =>  'Будинок',
            'flat' =>  'Квартира',
            'operatorid' =>  'Опратор',
            'reason' =>  'Причина відмови',
            'nextcontact' =>  'Дата наступного контакту',

            'date' => 'Дата',
            'time' => 'Час',

        ];


        $rule_arr = ($mode=='store')? $rule_arr : Arr::only($rule_arr, array_keys($data));


        $validator = Validator::make($data, $rule_arr)->setAttributeNames( $attribute_names );

        return $validator;



    }





    function send_request( $url, $data=null){

        //$url = 'http://193.169.189.29/bot/api/dict.php?table='.$table;
        $authorization = 'Basic Ym90c2FueWNoMjo4TnRMNzU1SE1jOWtk';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language: en-US,en;q=0.9,ru;q=0.8,fr;q=0.7,uk;q=0.6',
            'Authorization: ' . $authorization,
            'Cache-Control: no-cache',
            'Connection: keep-alive',
            'Pragma: no-cache',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
        ]);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if( $data != null ){
            curl_setopt($ch, CURLOPT_POST, true); // Встановлюємо метод POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Передаємо пустий POST-запит
        }




        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            // Handle the error
            //echo 'cURL error: ' . $error;
            return ['cURL error: ' . $error];

        } else {
            // Process the response
            //var_dump($response);

            return json_decode($response, true);
        }

        curl_close($ch);

    }




    public function getcall(){

        $guid = '0f778716-ad04-43e3-8c16-bd96ebb46301';
        $oper = '0982710209'; //Auth::user()->phone;
        $hash = md5($guid.$oper);

        $url = "http://91.239.233.217/getcalls.php?oper=$oper&hash=$hash";
        //$res = $this->send_request($url); var_dump($res);
        //$res = file_get_contents($url); var_dump($res);
        $res = '{"id":"1703152032.1962","calldate":"1703152032","operator":"2128","callerid":"0982710209","line":"0673436220","recording":"q-133-0674944288-20231221-114712-1703152032.1962.wav","answertime":"1703152039","endtime":"0"}';

        //return json_encode($res);
        return $res;

    }


    public function getclient(Request $request){
        //http://193.169.189.29/bot/api/clients.php?phone=хххххххххх де Х - номер телефона клієнта (callerid ) в 10-значному форматі.

        $phone = $request->input('phone');
        $url = "http://193.169.189.29/bot/api/clients.php?phone=$phone";
        $res = $this->send_request($url); //var_dump($res);


        return json_encode($res);
    }





    function send_request_again( $order_id ){

        $res = ['status' => false];


        $order = Order::find($order_id);

        Log::channel('app_log')->info( $order->request );

        $api_result = $this->send_request( $url='https://193.169.189.29/bot/api/createorder.php', json_decode($order->request, true));

        Log::channel('app_log')->info( json_encode($api_result) );
        Log::channel('app_log')->info('-----------------------------------------------------------------------------');


        $request = $order->requests()->create([
            'response' => json_encode($api_result)
        ]);


        $res['status'] = true;
        $res['order'] = Order::with('requests')->find($order_id)->toArray();
        return $res;
    }



}
