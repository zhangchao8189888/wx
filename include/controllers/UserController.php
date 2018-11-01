<?php
/**
 * 用户列表
 *
 */
class UserController extends FController
{
    private $user_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->user_model = new User();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }

    public function actionIndex () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //分页
        $data['count'] = $this->user_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();


        $data['userList'] = $this->user_model->findAll($condition_arr);
        $data['page'] = $pages;

        $this->render('index',$data);
    }

    /**
     * 停用 modify
     */
    public function actionModifyUser () {
        $uid = $this->request->getParam('uid');
        $flag = $this->request->getParam('flag');
        $arr = array(
            'user_status'   =>  $flag,
        );
        $res = $this->user_model->updateByPk($uid,$arr);
        if($res){
            $response['status'] = 100000;
            $response['content'] = 'success';
        }else{
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    public function actionMessage () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //分页
        $data['count'] = $this->sendMessage_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();


        $data['msgList'] = $this->sendMessage_model->findAll($condition_arr);
        $data['page'] = $pages;

        $this->render('message',$data);
    }
}