<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/7/31
 * Time: 下午4:15
 */

class AdminCompanyController extends FController
{
    private $employ_model;
    private $customer_model;
    private $salary_time_model;
    private $salary_model;
    private $salary_total_model;
    private $er_salary_model;
    private $salarytime_other_model;
    private $admin_company_model;
    private $salary_er_total_model;
    private $salary_nian_model;
    private $salary_nian_total_model;

    public $defaultAction = 'unit';


    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->employ_model = new Employ();
        $this->customer_model = new Customer();
        $this->salary_time_model = new SalaryTime();
        $this->salary_model = new Salary();
        $this->salary_total_model = new Total();
        $this->er_salary_model = new ErSalary();
        $this->salarytime_other_model = new SalarytimeOther();
        $this->admin_company_model = new AdminCompany();
        $this->salary_er_total_model = new ErTotal();
        $this->salary_nian_model = new NianSalary();
        $this->salary_nian_total_model = new NianTotal();

    }
    /**
     * 单位管理
     */
    public function actionUnit () {
        $data = array();
        $searchCompanyData = $this->searchCompany();
        $data['companyArr'] = $searchCompanyData['data'];
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();

        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date') ? $this->request->getParam('date') : date('Y-m');

        $data['salaryTime'] = $this->getSalaryTime($data['searchDate'].'-01');
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'params' => array(
                ':adminId'=>$this->user->id,
            ),
            'order'=>'id DESC',
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'adminId=:adminId';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        //分页
        $data['count'] = $this->admin_company_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['customList'] = $this->admin_company_model->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('unit',$data);
    }

    public function actionAjaxUnit () {
        //分页参数
        $condition_arr = array(
            /*'params' => array(
                ':op_id'=>$this->user->id
            ),*/
        );
        //$condition_arr['condition'] = 'op_id=:op_id';
        $condition_arr['condition'] = '1=1';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND customer_name like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        $data['customList'] = $this->customer_model->findAll($condition_arr);

        if ($data['customList']) {
            $i = 0;
            foreach ($data['customList'] as $row) {
                $custom_list[$i]['id'] = $row->id;
                $custom_list[$i]['name'] = $row->customer_name;
                $i++;
            }
            $response['status'] = 100000;
            $response['content'] = $custom_list;
        } else {
            $response['status'] = 100001;
            $response['content'] = '获取数据失败，请重试！';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    public function actionAjaxAddUnit () {
        $companyArr = array();
        $error = array();
        $ids = $this->request->getParam('id');
        $list = $this->admin_company_model->findAll('adminId=:adminId',array(':adminId'=>$this->user->id));
        foreach ($list as $v) {
            $companyArr[] = $v->companyId;
        }
        if (!empty($ids)) {
            $searchCompanyData = $this->searchCompany();
            $data['companyArr'] = $searchCompanyData['data'];
            foreach ($ids as $v) {
                if (!in_array($v,$companyArr)) {
                    $adminCompanyModel = new AdminCompany();
                    $adminCompanyModel->adminId = $this->user->id;
                    $adminCompanyModel->companyId = $v;
                    $adminCompanyModel->companyName = $searchCompanyData['data'][$v]['name'];
                    $adminCompanyModel->opTime = date('Y-m-d H:i:s');
                    if (!$adminCompanyModel->save()) {
                        $error[] = $v;
                    }
                }
            }
        }
        if (empty($error)) {
            $response['status'] = 100000;
            $response['content'] = '添加成功';
        } else {
            $response['status'] = 100001;
            $response['content'] = implode('，',$error);
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    /**
     * 取消管理
     */
    public function actionCancelUnit () {
        $response = array();
        $ids = $this->request->getParam('id');
        $res = $this->admin_company_model->deleteByPk($ids);
        if ($res) {
            $response['status'] = 100000;
            $response['content'] = '取消管理成功';
        } else {
            $response['status'] = 100001;
            $response['content'] = '取消管理失败，请检查您的操作';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function getSalaryTime ($date) {
        $arr = array();
        $salaryTime = $this->salary_time_model->findAll('op_id=:id and salaryTime=:date',array('id'=>$this->user->id,':date'=>$date));
        foreach ($salaryTime as $v) {
            $arr[$v->companyId] = $v;
        }
        return $arr;
    }

    public function actionGetCompanyListExcel () {
        $data = array();
        $head = array(0=>array(0=>"id",1=>"单位名称"));
        $company = Customer::model()->findAll();
        foreach ($company as $com) {
            $obj = array();
            $obj[0] = $com->id;
            $obj[1] = $com->customer_name;
            $data[] = $obj;
        }
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
}