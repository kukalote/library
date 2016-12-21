<?php

//取多维数据中某[些]字段的值
if ( ! function_exists('array_muliti_field'))
{
	function array_muliti_field($array, $fields)
	{
		$resp = array();
		foreach($array as $val) {
			if(is_array($fields)) {
				foreach($fields as $field) {
					if(isset($val[$field]) && $val[$field] !== null) {
						$resp[$field][$val[$field]] = $val[$field];
					}
				}
			} elseif(isset($val[$fields]) && $val[$fields] !== null){
				$resp[] = $val[$fields];
			}
		}
        return $resp;
    }
}
/*
  *  将多为数组中的某一个元素作为键名
 * $array = array(0=>array('id'=>10,'title'=>'t10'),1=>array('id'=>11,'title'=>'t11'));
 * $array = array_set_key($array, 'id');
 * array(10=>array('id'=>10,'title'=>'t10'),11=>array('id'=>11,'title'=>'t11'));
 *
 * $array = array_set_key($array, 'id', 'title');
 * array(10=>'t10',11=>'t11');
 */
if ( ! function_exists('array_set_key'))
{
	function array_set_key($array,$key='',$valuekey=''){
		$return = array();
        foreach($array as $k=>$v)
        {
			if ($key==''){
				$return[] = ($valuekey!='' ? $v[$valuekey] : $v);
			} else {
				$return[$v[$key]] = ($valuekey!='' ? $v[$valuekey] : $v);
			}
		}
		reset($array);
		return $return;
	}
}

/*
  *  将多为数组中的某两个元素作为键名组成二维数组
 * $array = array(0=>array('id'=>10,'title'=>'t10'),1=>array('id'=>11,'title'=>'t11'),2=>array('id'=>11,'title'=>'t12'));
 * $array = array_set_keys($array, 'id', 'title');
 * array(10 => array(
 *					't10' => array('id'=>10,'title'=>'t10')),
 		 11 => array(
 		 			't11' => array('id'=>11,'title'=>'t11'),
 		 			't12' => array('id'=>11,'title'=>'t12')));
*/
if ( ! function_exists('array_set_keys'))
{
	function array_set_keys($array, $key1, $key2 = '', $valuekey = ''){
		$return = array();
		while(list($k, $v) = each($array)){
			$k1 = $v[$key1];
			$vl = $valuekey != '' ? $v[$valuekey] : $v;
			if ($key2 == ''){
				$return[$k1][] = $vl;
			} else {
				$return[$k1][$v[$key2]] = $vl;
			}
		}
		reset($array);
		return $return;
	}
}
