<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-6-20
 * Time: 上午11:43
 */
class TestCommand  extends CConsoleCommand
{

    /*public function run($args)
    {
        echo '测试command';
    }*/
    public function actionTest () {
        $employ_model = new Employ();
        $arr = $employ_model->find("e_num=:e_num",array("e_num"=>'110103198811141840'));
        //$arr = $employ_model->findAll();
        print_r($arr);
    }
}