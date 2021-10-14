<?php

class Model {

	public static function exists($modelname){
		$fullpath = self::getFullpath($modelname);
		$found=false;
		if(file_exists($fullpath)){
			$found = true;
		}
		return $found;
	}

	public static function getFullpath($modelname){
		return "core/app/model/".$modelname.".php";
	}

	public static function many($query,$model=null){
		$elements = [];
		while($row = $query->fetch_array()){
			$element = $model != null ? new $model : [];
			foreach ($row as $key => $value) {
				if(is_int($key)) {
					continue;
				}
				if($model == null) {
					$element[$key] = utf8_encode($value);
				} else {
					$element->$key = utf8_encode($value);
				}
			}
			$elements[] = $element;
		}
		return $elements;
	}
	//////////////////////////////////
	public static function one($query,$model=null){
		$result = Model::many($query, $model);
		if(count($result) == 0) {
			return null;
		}
		return $result[0]; 
	}

}

?>