<?php
/**
 * Created by PhpStorm.
 * User: zhangchao-rj
 * Date: 2018/10/8
 * Time: 上午9:56
 */
class CustomerController extends FController
{
    private $user_model;
    private $userInfo_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->user_model = new User();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);
        //若登录则无法访问
        if($this -> is_login() && in_array($action -> getId(), $this -> access)){

            $this->request->redirect(FF_DOMAIN);
        }

        return true;
    }
}