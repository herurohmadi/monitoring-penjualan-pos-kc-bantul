<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'username'];

    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
