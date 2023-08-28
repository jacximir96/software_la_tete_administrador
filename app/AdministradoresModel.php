<?php

namespace App;

use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Model;

class AdministradoresModel extends Model
{
    use HasRolesAndPermissions;
    //protected $connection = 'sqlsrv';
    protected $table = 'users';

}
