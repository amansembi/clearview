<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class technician extends Model
{
    use Notifiable;
	
	protected $fillable = [
        'name', 'email', 'password', 'phonenumber',
    ];
	protected $hidden = [
        'password', 'remember_token',
    ];
}
