<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

		$rats = \EntityManager::findAll(Rats::class);

        return [
            "status" => 1,
            "data" => $rats
        ];
    }

	public function test()
	{

$rats = \EntityManager::getRepository(\App\Entities\Rat::class)->findAll();


		return 
		   $rats;
	}

}
