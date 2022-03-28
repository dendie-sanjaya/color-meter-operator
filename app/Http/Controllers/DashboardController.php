<?php

namespace App\Http\Controllers;

use App\Models\ColorGrade;
use App\Models\ColorList;
use App\Models\ColorPattern;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Session;

class DashboardController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function index()
	{				
	   $data = array();	
	   $config = Config::where('is_delete','no')->get();

	   $dataColorGrade = ColorGrade::orderBy('name', 'asc')->where([['is_delete','no']])->get();
	   $dataColorPattern = ColorPattern::orderBy('name', 'asc')->where([['is_delete','no']])->get();

	   return view('dashboard.index',['data' => $data, 'dataColorGrade' => $dataColorGrade, 'dataColorPattern' => $dataColorPattern]);
	} 

	public function scan(Request $request) {
	   	if(!empty($request->color_pattern_id)) {
	   		$where = [ 'rgb' => $request->rgb,
	   			   	   'color_pattern_id' => $request->color_pattern_id
	   			 	 ];  	   		
	   	} else {
	   		$where = [ 'rgb' => $request->rgb ];  	   			   		
	   	}

	   	$data = ColorList::where($where)->first();	

		//return response()->json(['success'=>'true','code'=>'200','data'=>$data]);	   	
		return response()->json(['success'=>'true','code'=>'400','data'=>$data]);
	}   
}
