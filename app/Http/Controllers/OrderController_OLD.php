<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

use Eluceo\iCal\Domain\ValueObject\MultiDay;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Domain\Entity\Event;

use App\Models\Robject;
use App\Models\Categorie;
use App\Models\Order;
use App\Models\OrderStatus;




class OrderControllerOLD extends Controller
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



        $sql = 'SELECT

                orders.*,

                robjects.name as robject_name,
                categories.name as categorie_name,
                order_statuses.name as status_name

                /*D*/
                FROM
                    orders

                    INNER JOIN robjects ON robjects.id = orders.robject_id
                    INNER JOIN categories ON categories.id = orders.categorie_id
                    INNER JOIN order_statuses ON order_statuses.id = orders.status_id

                WHERE
                    robjects.user_id = :user_id

                ORDER BY
                    orders.id DESC

                /*D*/
                LIMIT :limit
                OFFSET :offset
                ';


        $stmt = DB::connection()->getPdo()->prepare($sql);
        $stmt->execute([
            'limit' => $dt_params['rows'],
            'offset' => $dt_params['rows'] * $request->page,
            'user_id' => Auth::user()->id
        ]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);



        /*--------------------- count SQL -------------------------*/
        list($str1, $str2, $str3) = explode('/*D*/', $sql);
        $count_sql = 'SELECT COUNT(*) as totalRecords '.$str2; //dd($count_sql);
        $stmt = DB::connection()->getPdo()->prepare($count_sql);
        $stmt->execute(['user_id' => Auth::user()->id]);
        $totalRecords = $stmt->fetch(\PDO::FETCH_ASSOC)['totalRecords'];



        foreach ($records as $i=>$record){
            $records[$i]['status'] = [
                'name'=> $record['status_name'],
                'code'=> $record['status_id'],
            ];
        }


        $response['status'] = true;
        $response['records'] = $records;
        $response['totalRecords'] =   $totalRecords;

        return $response;
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create( $robject_id )
    {
        //
        $arr = [];

        $arr['robject_id'] = $robject_id;


          $arr['robject'] = Robject::with('categories.tariffs.tariff')
            ->where('id', '=', $robject_id)
            ->get()
            ->toArray()[0];


        /*$arr['robject'] = Robject::with('categories.tariffs.tariff')
                        ->whereHas('categories', function ($query) {
                            $query->where('status', '=', 1);
                        })
                        ->where('id', '=', $robject_id)
                        ->toSql();

                        ->where('id', '=', $robject_id)
                        ->get()
                        ->toArray()[0];

        dd($arr['robject']);*/


        //$arr['robject']['status'] = 0;


        /*------------------фільтруємо сутності по статусу (лише зі статусом активні) ------------------*/
        $collection = collect($arr['robject']['categories']);
        $arr['robject']['categories'] = $collection->where('status', '=', 1)->toArray();
        #dd($arr['robject']['categories']);

        foreach ($arr['robject']['categories'] as $i=>$category){
            $arr['robject']['categories'][$i]['tariffs'] =  collect($category['tariffs'])->where('tariff.status', '=', 1)->toArray();

            // якщов категорыъ немаэ тарифів то її потрібно видалити
            if( count($arr['robject']['categories'][$i]['tariffs']) == 0) unset($arr['robject']['categories'][$i]);
        }
        #dd($arr);




        $arr['start_date'] = date("Y-m-d");
        $arr['end_date'] =   date("Y-m-d", strtotime("+1 day"));


        ## $first_cat = reset($arr['robject']['categories']); $first_cat['id'];
        ## $first_tariff = reset($first_cat['tariffs']);   $first_tariff['tariff_id'];
        $arr['robject']['categories'] = array_values($arr['robject']['categories']);
        $arr['robject']['categories'][0]['tariffs'] = array_values($arr['robject']['categories'][0]['tariffs']);


        $arr['selected_categorie_id'] = $arr['robject']['categories'][0]['id'];
        $arr['selected_tariff_id'] =  $arr['robject']['categories'][0]['tariffs'][0]['tariff_id'];
        $arr['selected_number_of_seats'] = 1;


        return view('reservation', ['tmpl_data'=>$arr]);

    }


    public function create_admin_version()
    {
        //

        $robjects = Robject::with('categories.tariffs.tariff')
            ->get()
            ->toArray();


        /*------------------фільтруємо сутності по статусу (лише зі статусом активні) ------------------*/

        foreach ($robjects as $i=>$robject){

            // якщо в обєкта з статусом не актинвий то його потрібно видалити
            /*if( $robjects[$i]['status'] != 1 ){
                unset($robjects[$i]);
                continue;
            }*/

            $collection = collect( $robjects[$i]['categories'] );
            $robjects[$i]['categories'] = $collection->where('status', '=', 1)->toArray();


            // якщо в обєкта немає категорій то його потрібно видалити
            if( count($robjects[$i]['categories']) == 0 ){
                unset($robjects[$i]);
                continue;
            }


            foreach ($robjects[$i]['categories'] as $b=>$category){
                $robjects[$i]['categories'][$b]['tariffs'] =  collect($category['tariffs'])->where('tariff.status', '=', 1)->toArray();

                // якщов в категорії немає тарифів то її потрібно видалити
                if( count($robjects[$i]['categories'][$b]['tariffs']) == 0) unset($robjects[$i]['categories'][$b]);
            }

        }








        $order = [];

            $order['start_date'] = date("Y-m-d");
            $order['end_date'] =   date("Y-m-d", strtotime("+1 day"));



            $robjects[0]['categories'] = array_values($robjects[0]['categories']);
            $robjects[0]['categories'][0]['tariffs'] = array_values($robjects[0]['categories'][0]['tariffs']);


            $order['selected_robject_id'] =  $robjects[0]['id'];
            $order['selected_categorie_id'] = $robjects[0]['categories'][0]['id'];
            $order['selected_tariff_id'] = $robjects[0]['categories'][0]['tariffs'][0]['tariff_id'];

            $order['selected_number_of_seats'] = 1;


            $order_status = OrderStatus::first()->toArray();

            $order['selectedStatus'] = [
                'name'=> $order_status['name'],
                'code'=> $order_status['id'],
            ];

        $res = [
            'robjects' =>  $robjects,
            'order' => $order
        ];


        return $res;
    }



    public function edit($id){

        $robjects = Robject::with('categories.tariffs.tariff')
            ->get()
            ->toArray();

        $model = Order::find($id)->toArray();

        $order['start_date'] = $model['start_date'];
        $order['end_date'] = $model['end_date'];

        $order['selected_robject_id'] =  $model['robject_id'];
        $order['selected_categorie_id'] = $model['categorie_id'];
        $order['selected_tariff_id'] = $model['tariff_id'];

        $order['selected_number_of_seats'] =  $model['number_of_seats'];


        $order_status = OrderStatus::find( $model['status_id'] )->toArray();

        $order['selectedStatus'] = [
            'name'=> $order_status['name'],
            'code'=> $order_status['id'],
        ];

        $order = array_merge( $order, Arr::only($model, ['name', 'surname', 'phone', 'email', 'comment']) );



        $res = [
            'robjects' =>  $robjects,
            'order' => $order
        ];


        return $res;

    }






    public function store()
    {
        $res = ['status' => false];

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);



        if( isset($_GET['frameform']) ){
            $data['status_id'] = 1;
        }

        //if( !isset($_GET['frameform']) ) {

            /*----------------- Валідація -----------------*/
            $validator = $this->validate_record($data);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

        //}


        //$data['robject_id'] = 1; // розифровуэмо 'robject_id'   $data['robject_id'] = Order::decode_robject_id( $data['robject_id'] );

        ###$data['amount'] = 100; // підраховуємо amount
        $data['amount'] = Order::calc_amount($data['robject_id'], $data['categorie_id'], $data['tariff_id'], $data['start_date'], $data['end_date']);


        $order = Order::create($data);


        //
        $res['status'] = true;
        $res['order'] = $order;

        return $res;
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateOLD($id)
    {
        $res = ['status' => false];

        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);

        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'status_id'       => 'required',
        );
        $validator = Validator::make(request()->all(), $rules);



        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
                //'$request_body'=> $request_body
            ], 422);


        } else {

            $shark = Order::find($id);
            $shark->status_id = request()->status_id;  //Input::get('status_id');
            $shark->save();

        }


        $res['status'] = true;
        return $res;

    }



    public function update($id)
    {
        $res = ['status' => false];

        $request_body = file_get_contents('php://input');
        $request_body = json_decode($request_body, true);



        /*----------------- Валідація -----------------*/
        $validator = $this->validate_record( $request_body, $mode='update' );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ], 422);
        }


        //idea $request_body =  array_marge($request_body, Order::find($id)->toArray() );
        if(Arr::exists($request_body, 'robject_id')) {
            $request_body['amount'] = Order::calc_amount($request_body['robject_id'], $request_body['categorie_id'], $request_body['tariff_id'], $request_body['start_date'], $request_body['end_date']);
        }


        $order = Order::find($id);


        // -------------- вдправка email ---------------
        if( array_key_exists('status_id', $request_body) ){

            if($request_body['status_id'] != $order->status_id){

                $data = [
                    'title' => 'Статус вашого замовленн змінено',
                    'body' => 'Ваше замовлення №'.$id.' змінили статус на "'.OrderStatus::find( $request_body['status_id'] )->first()->name.'"'
                ];

                Mail::to( $order->email )->send(new SendMail($data));
            }
        }


        $order->fill($request_body);
        $order->save();




        $res['status'] = true;
        return $res;
    }






    public function validate_record( $data, $mode='store' )
    {

        $rule_arr = [
            'surname' => 'required|string|max:64',
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:15',

            'email' => 'required|email',
            'status_id' => 'required|numeric',

            'start_date' => 'required|string|max:15',
            'end_date' => 'required|string|max:15',

            'robject_id' => 'required|numeric',
            'categorie_id' => 'required|numeric',
            'tariff_id' => 'required|numeric',
            'number_of_seats' => 'required|numeric',
            'comment' => 'nullable|string|max:256',
        ];


        $attribute_names = [
            'surname' => 'Прізвище',
            'name' => 'Імя',
            'phone' => 'Телефон',

            'email' => 'Email',
            'status_id' => 'Статус',

            'start_date' => 'Дата заїзду',
            'end_date' => 'Дата виїзду',

            'robject_id' => 'Обєкт',
            'categorie_id' => 'Категорія номеру',
            'tariff_id' => 'Тариф',
            'number_of_seats' => 'Кількість гостей',
            'comment' => 'Коментар бронювання',
        ];


        $rule_arr = ($mode=='store')? $rule_arr : Arr::only($rule_arr, array_keys($data));


        $validator = Validator::make($data, $rule_arr)->setAttributeNames( $attribute_names );

        return $validator;
    }





/*
    function export_to_csv( Request $request )
    {
        $this->download_send_headers("data_export_" . date("Y-m-d") . ".csv");

        $records = Order::with('robject')
            ->with('categorie')
            ->with('order_status')
            ->get();

        $rows = [];
        foreach ($records as $item) {
            $row['id'] = $item->id;
            $row['Гість'] = $item->name.' '.$item->surname;
            $row['Помешкання'] = $item->categorie->name;
            $row['Обєкт'] = $item->robject->name;
            $row['Сума'] = $item->amount;
            $row['Дата_створення'] = $item->created_at;
            $row['Період_проживання'] = $item->start_date.'---'.$item->end_date;
            $row['Статус'] = $item->order_status->name;

            $rows[] = $row;
        }

        echo $this->array2csv($rows);

        die();
    }



    function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w'); fprintf($df, chr(0xEF).chr(0xBB).chr(0xBF));


        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }*/







    function export_to_csv( Request $request ){

        $fileName = 'report.csv';

        $records = Order::with('robject')
            ->with('categorie')
            ->with('order_status')
            ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            //"Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",

            "Content-Transfer-Encoding" => "binary"
        );

        //header("Content-Transfer-Encoding: binary");

        $columns = array('id', 'Гість', 'Помешкання', 'Обєкт',  'Сума', 'Дата створення', 'Період проживання', 'Статус');

        $callback = function() use($records, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns, ';', '"');

            foreach ($records as $item) {
                $row['id'] = $item->id;
                $row['Гість'] = $item->name.' '.$item->surname;
                $row['Помешкання'] = $item->categorie->name;
                $row['Обєкт'] = $item->robject->name;
                $row['Сума'] = $item->amount;
                $row['Дата_створення'] = $item->created_at;
                $row['Період_проживання'] = $item->start_date.'---'.$item->end_date;
                $row['Статус'] = $item->order_status->name;

                fputcsv($file, array_values($row), ';', '"');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        #return response()->streamDownload($callback, 200, $headers);

    }



    function export_to_ical( Request $request )
    {


        // 1. Create Event domain entity
        /*$event = (new \Eluceo\iCal\Domain\Entity\Event())
            ->setSummary('Christmas Eve')
            ->setDescription('Lorem Ipsum Dolor...')
            ->setOccurrence(
                new \Eluceo\iCal\Domain\ValueObject\SingleDay(
                    new \Eluceo\iCal\Domain\ValueObject\Date(
                        \DateTimeImmutable::createFromFormat('Y-m-d', '2030-12-24')
                    )
                )
            );
        */


        $orders = Order::with('robject', 'categorie', 'order_status')->whereHas('robject', function ($query) {
            return $query->where('user_id', '=', Auth::user()->id);
        })->get();

        $events = [];
        foreach ($orders as $order) {

            $end_date = new \DateTime($order['end_date'].' 23:59:59');
            $n_end_date = $end_date->modify('-1 day')->format('Y-m-d');


            $firstDay = new \Eluceo\iCal\Domain\ValueObject\Date( \DateTimeImmutable::createFromFormat('Y-m-d', $order['start_date']) );
            $lastDay = new \Eluceo\iCal\Domain\ValueObject\Date( \DateTimeImmutable::createFromFormat('Y-m-d',  $n_end_date) );
            $occurrence = new MultiDay($firstDay, $lastDay);

            $event = (new \Eluceo\iCal\Domain\Entity\Event())
                ->setSummary( implode(' ', [$order['name'], $order['surname']]).' ('.$order->order_status->name.')' )
                ->setDescription($order->robject->name.' '.$order->categorie->name)
                ->setOccurrence($occurrence);

            $events[] = $event;
        }



        // 2. Create Calendar domain entity
        $calendar = new \Eluceo\iCal\Domain\Entity\Calendar($events);


        // 3. Transform domain entity into an iCalendar component
        $componentFactory = new \Eluceo\iCal\Presentation\Factory\CalendarFactory();
        $calendarComponent = $componentFactory->createCalendar($calendar);


        // 4. Set headers
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        // 5. Output
        echo $calendarComponent;


    }



    function has_change_field( $field, $old_arr, $new_arr ){ //status_id

        $diff_arr = array_diff($old_arr, $new_arr);

        return (array_key_exists($field, $diff_arr));
    }




}
