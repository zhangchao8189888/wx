<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 17/8/20
 * Time: 上午11:30
 */
class FExcelFun {
    public static function checkIsEmptyHeaderList ($excel_data) {
        foreach ($excel_data as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }
    public static function  getHeaderListByIndex ($excel_data,$index) {
        $return_arr = array();
        foreach ($excel_data as $val) {
            if (isset($return_arr[$index])) {
                $return_arr[] = $val;
            }
        }
        return $return_arr;
    }
    public  static function delArrayList ($list, $index) {
        foreach ($list as $key=>$val) {
            unset($list[$key][$index]);
        }
        $list = array_values($list);
        return $list;
    }
}