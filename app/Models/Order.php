<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;


class Order extends Model
{
    use HasFactory;


    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $prop_names = [
        'id' => 'Внутрішній ID',
        'status' => 'Статус',
        'ordertype' => 'ID типу замовлення',
        'name' => 'Імя',
        'comment' => 'Коментар',
        'comment_inner' => 'Внутренний комментарий',
        'phone1' => 'Телефон №1',
        'phone2' => 'Телефон №2',
        'phone3' => 'Телефон №3',
        'cityid' => 'ID міста',
        'streetid' => 'ID вулиці',
        'street' => 'Вулиця',
        'house' => 'Будинок',
        'flat' => 'Квартира',
        'operatorid' => 'ID оператора',
        'nextcontact' => 'Дата наступного контакту',
        'date' => 'Дата',
        'time' => 'Час',
        'questions_and_answers' => 'Питання і відповіді',
        'с_calldate' => 'Дата дзвінка',
        'с_callerid' => 'Номер телефона клієнта',
        'с_line' => 'Номер, на котрий дзвонив клієнт',
        'с_recording' => 'Ім’я файлу запису розмови',
        'ordertype_name' => 'Тип замолвення',
        'city_name' => 'Місто',
        'street_name' => 'Вудиця',
        'request' => 'Запит',
        'response' => 'Відповідь',
        'created_at' => 'Дата створення',
        'updated_at' => 'Дата редагування'

    ];

    protected $appends = ['requestStatus'];



    public function requests()
    {
        return $this->hasMany(Request::class);
    }


    // $model->requestStatus
    public function getRequestStatusAttribute()
    {
        // Логика вычисления вычисляемого свойства
        // Верните результат вычисления свойства
        //return $this->attribute1 + $this->attribute2;
        /*
        У разі успішної відправки форми API буде відповідати в форматі:
            {"result": "created", "order_id": ORDER_ID, "request_id": REQUEST_ID}
        У разі помилки, буде:
            {"result": "failed", "reason": REASON}

        */

        /* отримати повторын запити
        якщо хочаб один з запитыв true то status = true
        якщо повторних запитів немає то перевіряємо основний запит

        */

        /*$response = json_encode($this->response, true);
        if( is_array($response) ){
            if($response['result'] == 'created') return true;
        }*/

        $is_success_response = function( $response ){

            $response = json_decode($response, true);  //dd($response);
            if( is_array($response) ){
                if($response['result'] == 'created') return true;
            }

            return false;
        };


        // якщо хочаб один з запитыв true то status = true
        $requests = $this->requests;  //dd($this->requests->toArray());
        for($i = 0; $i < count($requests); $i++){
            if( $is_success_response($requests[$i]['response']) ) return true;
        }

        //якщо повторних запитів немає то перевіряємо основний запит
        if( $is_success_response($this->response) ) return true;

        return false;
    }




}
