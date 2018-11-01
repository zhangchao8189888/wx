<?php
/**
 * 短信列表
 *
 */
class MessageController extends FController
{

    private $sendMessage_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->sendMessage_model = new SendMessage();

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
        $data['count'] = $this->sendMessage_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();


        $data['msgList'] = $this->sendMessage_model->findAll($condition_arr);
        $data['page'] = $pages;

        $this->render('index',$data);
    }

    public function actionMessage () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $mobile_no = trim($this->request->getParam('mobile_no') ? $this->request->getParam('mobile_no') : '');
        $send_time_begin = trim($this->request->getParam('send_time_begin') ? $this->request->getParam('send_time_begin') : '');
        $send_time_end = trim($this->request->getParam('send_time_end') ? $this->request->getParam('send_time_end') : '');

        $condition_arr = array(
            'select' => 'mobile_no,count(mobile_no) as cnt',
            'group' => 'mobile_no',
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $where ='1=1';
        if ($mobile_no) {
            $where.= " and mobile_no = '$mobile_no' ";
        }
        if($send_time_begin){
            $where .= " and send_time >= '$send_time_begin'";
        }
        if($send_time_end){
            $where .= " and send_time <= '$send_time_end'";
        }
        $condition_arr['condition'] = $where;
        //分页
        $data['count'] = $this->sendMessage_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['msgList'] = $this->sendMessage_model->findAll($condition_arr);
        $data['mobile_no'] = $mobile_no;
        $data['send_time_begin'] = $send_time_begin;
        $data['send_time_end'] = $send_time_end;
        $data['test'] = Yii::app()->request->userHostAddress;
        $data['page'] = $pages;
        $this->render('message',$data);
    }





}