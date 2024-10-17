<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
 
    protected $table = 'employees'; 

    protected $fillable = ['code', 'password','status','type']; 

    protected $hidden = ['password'];
    
    use HasApiTokens;
   
    public function getAuthIdentifierName()
    {
        return 'code'; 
    }
}


