<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FController extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    //public $layout='//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    /**
     * User info
     *
     * @var JUser
     */
    public $user;

    public $userInfo;
    /**
     * @var CHttpRequest
     */
    public $request;

    /**
     * @var string;
     */
    public $pageTitle;
    /**
     * @var string
     */
    public $pagekeywords;
    /**
     * @var string;
     */
    public $pageDescription;
    /**
     * @var string;
     */
    public $auth_list;
    /**
     * @var string;
     */
    protected $_controller;
    /**
     * @var string;
     */
    protected $_action;

    public $user_menu_list = array();
    /**
     * @var string
     */
    public $returnurl;

    private $access = array('login','getLogin','setcookie','error');

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this -> auth_list  =  FConfig::item('admin.memu');
        $this -> request      = Yii::app()->getRequest();
    }
    /*
    *判断当前用户是否登录
    */
    public function is_login(){



        return isset($this->userInfo['id']) && $this->userInfo['id'] ? true : false ;
    }
    protected function beforeAction($action) {
        $this -> user = Yii::app()-> user ->loadUser();
        if (!$this -> user&& !in_array($action -> getId(), $this -> access))
        {
            Yii::app()->getRequest() ->redirect(FF_DOMAIN."/login");
        } else {
            //获取用户权限
            // $mc_menu_key = md5('mc_menu_key' . $this -> user -> id);
            // $this -> user_menu_list = Yii::app() ->cache ->get($mc_menu_key);
            // Yii::app() -> cache ->set($mc_menu_key , $this -> user_menu_list , 600);

            //用户-> 用户-组关系 -> 组 -> 组-权限
            if(!empty($this -> user -> admin_user_group)){
                $user_auth_list = explode(',',$this -> user -> admin_user_group -> group -> rules);
            }
            $allow_controller = array('gate');
            if(!empty($user_auth_list)){
                foreach ($user_auth_list as $k => $v) {
                    //菜单
                    $this -> user_menu_list[] =   $v;

                    $allow_controller[] = $this -> auth_list[$v]['controller'];
                }
            }

            //判断是否有访问权限
            $this -> _controller = $action -> getController() ->getId();


            if(!in_array( $this ->_controller,$allow_controller))
            {
                Yii::app()->getRequest() ->redirect(FF_DOMAIN.'/site');
            }

        }
        $this -> _action =  $action ->getId();

        return true;
    }
    protected function getUserinfo($uid) {
        $userModel = new User();
        $attr = array(
            'condition'=>"id=:id",
            'params' => array(':id'=>$uid,),

        );
        $user = $userModel->find($attr);
        $account = $user->getAttributes();
        return $account;
    }

    //查询公司
    public function actionSearchCompany(){
        $customer_model = new Customer();
        //查询公司
        $condition_arr = array(
            'condition'=>"op_id=:op_id",
            'params' => array(
                ':op_id'=>$this->user->id,
            ),
        );
        $res = $customer_model->findAll($condition_arr);
        $i = 0;
        foreach ($res as $row) {
            $data['custom_list'][$i]['id'] = $row->id;
            $data['custom_list'][$i]['name'] = $row->customer_name;
            $i++;
        }
        return $data;
    }
    protected  function getAllCompany () {
        $res = Customer::model()->findAll();
        $i = 0;
        $customerList = array();
        foreach ($res as $row) {
            $customerList[$i]['id'] = $row->id;
            $customerList[$i]['name'] = $row->customer_name;
            $i++;
        }
        return $customerList;
    }
    protected function getAdminCompanyStr () {
        $list = AdminCompany::model()->findAll('adminId=:adminId',array(':adminId'=>$this->user->id));
        foreach ($list as $v) {
            $companyArr[] = $v->companyId;
        }
        if (empty($companyArr)) {
            return "";
        }
        $companyStr = implode(",",$companyArr);
        return $companyStr;
    }
    protected function getAdminCompanyList () {
        $companyStr = $this->getAdminCompanyStr();
        if (empty($companyStr)) {
            return array();
        }
        $condition_arr['condition'] = 'id in ('.$companyStr.')';
        $res = Customer::model()->findAll($condition_arr);
        $i = 0;
        $customerList = array();
        foreach ($res as $row) {
            $customerList[$i]['id'] = $row->id;
            $customerList[$i]['name'] = $row->customer_name;
            $i++;
        }
        return $customerList;
    }
    public function searchCompany(){
        $data = array();
        $customer_model = new Customer();
        //查询公司
        $condition_arr = array(
            'condition'=>"1=1",
            'params' => array(
                //':op_id'=>$this->user->id,
            ),
        );
        $res = $customer_model->findAll($condition_arr);
        foreach ($res as $row) {
            $data['data'][$row->id]['id'] = $row->id;
            $data['data'][$row->id]['name'] = $row->customer_name;
            $data['list'][] = $row->customer_name;
        }
        return $data;
    }
    protected function updateCustomerAccountValById($id,$accountVal) {
        return Customer::model()->updateByPk($id,array(
            'account_val' => $accountVal
        ));
    }

    public function actionSalaryExport () {
        $data = $this->request->getParam('excel_data');
        $head = $this->request->getParam('head');
        $data = json_decode($data);
        $head = array(0=>json_decode($head));
        //$head[0] = array('序号','已发/未发','公司名称','姓名','身份证号','个人应发合计','个人失业','个人医疗','个人养老','个人公积金','个人代扣税','个人扣款合计','实发合计','单位失业','单位医疗','单位养老','单位工伤','单位生育','单位公积金','单位合计','劳务费','残保金','档案费','缴中企基业合计');;
        /*print_r($data);
        print_r($head);exit;*/
        require "include/extensions/excel/php-excel.class.php";
        $produceList = array_merge($head,$data);
        //print_r($data);
        //print_r($produceList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($produceList);
        $xls->generateXML("salaryList");
        exit;
    }

    protected  function excelExport ($head,$data) {

        //$head[0] = array('序号','已发/未发','公司名称','姓名','身份证号','个人应发合计','个人失业','个人医疗','个人养老','个人公积金','个人代扣税','个人扣款合计','实发合计','单位失业','单位医疗','单位养老','单位工伤','单位生育','单位公积金','单位合计','劳务费','残保金','档案费','缴中企基业合计');;
        /*print_r($data);
        print_r($head);exit;*/
        require "include/extensions/excel/php-excel.class.php";
        $produceList = array_merge($head,$data);
        //print_r($data);
        //print_r($produceList);exit;
        ob_end_clean();

        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($produceList);
        $xls->generateXML("salaryList");
        exit;
    }

    protected function excelReaderFun_bak ($filePath) {

        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        $_ReadExcel = new PHPExcel_Reader_Excel2007 ();
        if (!$_ReadExcel->canRead($filePath))
            $_ReadExcel = new PHPExcel_Reader_Excel5 ();
        $_phpExcel = $_ReadExcel->load($filePath);
        $_newExcel = array();
        for ($_s = 0; $_s < 1; $_s++) {
            $_currentSheet = $_phpExcel->getSheet($_s);
            $_allColumn = $_currentSheet->getHighestColumn();
            $_allRow = $_currentSheet->getHighestRow();
            $temp = 0;
            for ($_r = 1; $_r <= $_allRow; $_r++) {
                for ($_currentColumn = 'A'; $_currentColumn <= $_allColumn; $_currentColumn++) {
                    $address = $_currentColumn . $_r;
                    $val = $_currentSheet->getCell($address)->getValue();
                    if ($val instanceof PHPExcel_RichText) {
                        $val = $val->getPlainText();
                    }
                    $_newExcel [$temp] [] = $val;
                }
                $temp++;
            }
        }
        //print_r($_newExcel);
        //array_shift($_newExcel);
        return $_newExcel;

    }
    protected function excelReaderFun ($filePath) {

        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
        $objPHPExcelReader = PHPExcel_IOFactory::load($filePath);
        foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            $temp = 0;
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                /*if($row->getRowIndex())  //确定从哪一行开始读取
                {
                    continue;
                }*/
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    $data = $cell->getValue(); //获取cell中数据
                    if ($data instanceof PHPExcel_RichText) {
                        $data = $data->getPlainText();
                    }
                    $_newExcel [$temp] [] = $data;
                }
                $temp++;
            }
        }
        //print_r($_newExcel);exit;
        //array_shift($_newExcel);
        return $_newExcel;

    }
}