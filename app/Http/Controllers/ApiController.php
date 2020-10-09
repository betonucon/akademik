<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class ApiController extends Controller
{
    public function api_user(){
        error_reporting(0);
        $data=User::orderBy('name','Asc')->get();
        foreach($data as $o){
           
            $show[]=array(
                "id" =>$o['id'],
                "nik" =>$o['nik'],
                "name" =>$o['name'],
                "role"=>$o['role_id']
            );
        }

        echo json_encode($show);
        
    }
}
