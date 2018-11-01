<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/7/31
 * Time: 下午4:15
 */

class GateController extends FController
{
    public $defaultAction = 'index';

    public function __construct($id, $module = null)
    {

        parent::__construct($id, $module);

    }

    protected function beforeAction($action)
    {

        parent::beforeAction($action);

        return true;
    }

    public function actionIndex()
    {
        echo "11111";exit;
    }

}