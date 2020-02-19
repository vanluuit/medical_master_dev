<?php 
namespace App\Helpers;
use DB;
class Csv {
	public static function downloadCsv($data , $fileName = '')
	{
		\Excel::create($fileName, function($excel)  use ($data) {
            $excel->sheet('Sheet1', function($sheet)  use ($data) {
                $sheet->fromArray($data);
            });
        })->download('csv');
	}
	public static function importCsv($file , $table, $column='')
	{
		$count = 0;
		$results = \Excel::load($file, function($reader) {
    	})->get();
		foreach ($results as $key => $row) {
			$data = $row->toArray();
			if(!empty($column)) {
				$check = DB::table($table)->where($column, '=', $data[$column])->count();
				if(!$check){
					$rs = DB::table($table)->insert($data);
					if($rs) $count++;
				}
			}else{
				$rs = DB::table($table)->insert($data);
				if($rs) $count++;
			}
		}
		return $count;
	}
}