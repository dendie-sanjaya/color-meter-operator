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
	    $config = Config::where([['is_delete','no'],['name','color_tolerance']])->first();
	    $color_tolerance = 0;
	    if(!empty($config)) {
	   	  $color_tolerance = $config->val;	   	
	    }

	   	if(!empty($request->color_pattern_id)) {
	   		$where = [ 'rgb' => $request->rgb,
	   			   	   'color_pattern_id' => $request->color_pattern_id
	   			 	 ];  	   		
	   	} else {
	   		$where = [ 'rgb' => $request->rgb ];  	   			   		
	   	}	    	

	   	$status_similiar = 'no';
	   	$data = ColorList::select('color_grade.name as grade_name','color_pattern.name as pattern_name','color_pattern.description as pattern_description','color_list.hexadecimal','color_list.rgb')
				->join('color_grade', 'color_grade.id', '=', 'color_list.color_grade_id')
				->join('color_pattern', 'color_pattern.id', '=', 'color_list.color_pattern_id')
				->where($where)
	   			->first();	

	    if($color_tolerance > 0) {
	        $rgb_arr = explode(',',$request->rgb);
	        $rgb_min = $this->up_down_val('down',$rgb_arr[0],$color_tolerance).','.$this->up_down_val('down',$rgb_arr[1] ,$color_tolerance).','.$this->up_down_val('down',$rgb_arr[2],$color_tolerance);	
	        $rgb_max = $this->up_down_val('up',$rgb_arr[0],$color_tolerance).','.$this->up_down_val('up',$rgb_arr[1],$color_tolerance).','.$this->up_down_val('up',$rgb_arr[2],$color_tolerance);	

		   	if(empty($data)) {
	   			$data = ColorList::select('color_grade.name as grade_name','color_pattern.name as pattern_name','color_pattern.description as pattern_description','color_list.hexadecimal','color_list.rgb')
						->join('color_grade', 'color_grade.id', '=', 'color_list.color_grade_id')
						->join('color_pattern', 'color_pattern.id', '=', 'color_list.color_pattern_id')
		                 ->where(function($query) use ($rgb_min,$rgb_max){
		                     $query->whereRaw('rgb BETWEEN ' . "'".$rgb_min."'" . ' AND ' . "'".$rgb_max."'");
		                 })
						->where(['color_pattern_id' => $request->color_pattern_id])					
			   			->get();

			   	if(count($data) > 0) {
			   		$status_similiar = 'yes';
			   	    $data = $data[floor(count($data) / 2)];	
			   	} else {
			   		$data = null;
			   	}		
			   				        
		    }    
	    }

	   	if(!empty($data)) {
	   		$msg = '';

	   		if($status_similiar == 'yes') {
	   			$msg .= 'SIMILAR <br />';
	   		} else {
	   			$msg .= 'EXACTLY SAME <br />';
	   		}

	   		$msg .= $data->pattern_name.','.$data->grade_name.'<br />'.'RGB: '.$data->rgb.' Hexa: '.$data->hexadecimal.'<br />'.$data->pattern_description;

	   		if($color_tolerance > 0) {
	   		   $msg .= '<br />Tolerance Color '.$color_tolerance.'%'.'<br />Range RGB : '.$rgb_min.' to '.$rgb_max;	   			
	   		}

			return response()->json(['success'=>'true','code'=>'200','data'=>$msg]);	   		   		
	   	} else {
			return response()->json(['success'=>'true','code'=>'400','data'=>$data]);	   		
	   	}
	}   

	private function up_down_val($type, $val, $percent) {
		$add_val = round(($val/100) * $percent);
		$new_val = 0;
		if($type == 'up') {
		   $new_val =  $val + $add_val;	
		} else {
		   $new_val = $val - $add_val;				
		}
		return $new_val;
	}
}
