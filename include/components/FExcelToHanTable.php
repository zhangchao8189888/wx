<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-4-1
 * Time: 下午1:42
 */
class FExcelToHanTable {
    public function __construct($bNeedHead = 0){
        $this->_needHead = $bNeedHead;
    }
    private $_needHead = 0;
    /**
     * @var array
     * 表头字段
     */
    public $head_row = array();
    /**
     * @var array
     * 标题长度字段
     */
    public $head_width_arr = array();
    public $row_height = 40;
    /**
     * @var array
     * 标题高度字段
     */
    public $head_height_arr = array();
    public $table_data = array();
    private function isEmptyRow ($row) {
        $bEmpty = 0;
        foreach ($row as $val) {
            if (!empty($val)) {
                $bEmpty = 1;
            }
        }
        return $bEmpty;
    }
    public function getData ($excel_array) {
        if (!is_array($excel_array)) {
            return false;
        }
        //查找头部
        foreach ($excel_array[0] as $key => $val) {
            if (empty($val)) {
                throw new Exception("表头不能为空");
            }
            $len = mb_strlen($val,'UTF8');
            $this->head_width_arr[] = $len*15;
            $this->head_height_arr[] = $this->row_height;
            $this->head_row[] = $val;
        }
        if ($this->_needHead) {
            $init_num = 1;
        } else {
            $init_num = 0;
        }
        //查找集合
        for ($i = $init_num; $i < count($excel_array); $i++) {
            if (!$this->isEmptyRow($excel_array[$i])) {
                continue;
            }
            $this->table_data[] = $excel_array[$i];
        }
    }
    public function checkHead ($head_row) {
        foreach ($head_row as $val) {
            /*if () {

            }*/
        }
    }
}