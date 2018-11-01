<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-3-30
 * Time: 下午4:11
 */
class MongoTest extends EMongoDocument {
    public $name;
    public $array;
    public static function model($className = __CLASS__){
        return parent::model($className);
    }

    public function getCollectionName()
    {
        return 'movie';
    }

    public function addInfo() {
        //$this->z='1234';
        $this->array='1234';
        $arr = array(
            'pageSize'  => 20,
            'employ_status' => array(
                '1' =>  '在职',
                '2' =>  '离职',
                '3' =>  '退休',
            ),
            'employ_type' => array(
                0=> "未缴纳保险",
                1 => "本市城镇职工",
                2 => "外埠城镇职工",
                3 => "本市农村劳动力",
                4 => "外地农村劳动力",
                5 => "本市农民工",
                6 => "外地农民工",
            ),
            'employ_sex' => array(
                1 => "男",
                2 => "女",
            ),
        );
        $this->array=$arr;
        $this->save();
    }
}