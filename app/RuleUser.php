<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RuleUser extends Model
{

	protected $table = 'role_user';

    protected $fillable = [
        'user_id', 'role_id'
    ];
    


    public function user()
	{
	  return $this->belongsTo('App\User','user_id');
	}

    public function role()
	{
	  return $this->belongsTo('App\Role','role_id');
	}
}
