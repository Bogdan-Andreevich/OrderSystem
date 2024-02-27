<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Laravel\Cashier\Billable;

//use Spatie\Activitylog\Traits\CausesActivity;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    //use CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        'phone',
        //'ipn_code',
        'operatorid_in_crm',

        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the comments for the blog post.
     */
    public function PredictDocuments()
    {
        return $this->hasMany(PredictDocument::class);
    }


    function get_permissions( $required_permissions )
    {
        $permissions = ['PredictDocuments_store'=>true];

        // PredictDocuments_store - право на створення документу
        if( in_array('PredictDocuments_store', $required_permissions) ){

            if ($this->subscribed('1') == false and $this->PredictDocuments->count() > 0) {
                $permissions['PredictDocuments_store'] = false;
            } else {
                $permissions['PredictDocuments_store'] = true;
            }
        }

        //dd($required_permissions[0]);
        if (count($required_permissions) == 1){
            return $permissions[ $required_permissions[0] ];
        }else{
            return $permissions;
        }


    }

    public function  get_plan(){

        if( $this->subscribed('1') ) {

            $plan_data = Plan::find('1')->get()->toArray()[0];
            return $plan_data['name'];

        }else{
            return 'Free';
        }

    }


    public function socialAccounts(){
        return $this->hasMany(socialAccount::class);
    }


}
