<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/7/31
 * Time: 下午4:15
 */

class MakeSalaryController extends FController
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

    public $defaultAction = 'toSalaryPage';


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
    public function actionToIndex () {
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
        $this->render('index',$data);
    }
    public function actionToSalaryPage () {
        if ($this->user->admin_type == 3) {
            $data['custom_list'] = $this->getAdminCompanyList();
        } else {
            $data = $this->actionSearchCompany();

        }
        $searchCompanyData = $this->searchCompany();
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();

        //$this->customer_model = new Customer();
        /*$data['company']=$this->customer_model->findAll(array(
            'select'=>'id,customer_name'
        ));*/
        $this->render("salaryPage",$data);
    }
    public function actionToErSalaryPage () {

        if ($this->user->admin_type == 3) {
            $data['custom_list'] = $this->getAdminCompanyList();
        } else {
            $data = $this->actionSearchCompany();

        }
        $searchCompanyData = $this->searchCompany();
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();
        $this->render("salaryErPage",$data);
    }
    public function actionToNianSalaryPage () {
        if ($this->user->admin_type == 3) {
            $data['custom_list'] = $this->getAdminCompanyList();
        } else {
            $data = $this->actionSearchCompany();

        }
        $searchCompanyData = $this->searchCompany();
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();
        $this->render("salaryNianPage",$data);
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


    public function getSalaryTime ($date) {
        $arr = array();
        $salaryTime = $this->salary_time_model->findAll('op_id=:id and salaryTime=:date',array('id'=>$this->user->id,':date'=>$date));
        foreach ($salaryTime as $v) {
            $arr[$v->companyId] = $v;
        }
        return $arr;
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
    /**
     * 工资审核
     */
    public function actionExamineSalary () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');

        $companyStr = $this->getAdminCompanyStr();

            //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if (empty($companyStr)) {
            $data['list'] = array();
            $pages = new FPagination(0);
            $pages->makePages();
            $data['page'] = $pages;
            $pages->setPageSize($page_size);
            $pages->setCurrent($page);
            $this->render('examineSalary',$data);
            return;
        }
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        //分页
        $data['count'] = $this->salary_time_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['list'] = $this->salary_time_model->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('examineSalary',$data);
    }
    /**
     * 二次工资审核
     */
    public function actionExamineErSalary () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');

        $companyStr = $this->getAdminCompanyStr();

            //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if (empty($companyStr)) {
            $data['list'] = array();
            $pages = new FPagination(0);
            $pages->makePages();
            $data['page'] = $pages;
            $pages->setPageSize($page_size);
            $pages->setCurrent($page);
            $this->render('examineSalary',$data);
            return;
        }
        $type = FConfig::item('config.salary_type');
        $condition_arr['condition'] = 'companyId in ('.$companyStr.') and salaryType = '.$type['SALARY_ER'];
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        //分页
        $data['count'] = $this->salarytime_other_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['list'] = $this->salarytime_other_model->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('examineErSalary',$data);
    }
    /**
     * 年终奖审核
     */
    public function actionExamineNianSalary () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');

        $companyStr = $this->getAdminCompanyStr();

            //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if (empty($companyStr)) {
            $data['list'] = array();
            $pages = new FPagination(0);
            $pages->makePages();
            $data['page'] = $pages;
            $pages->setPageSize($page_size);
            $pages->setCurrent($page);
            $this->render('examineSalary',$data);
            return;
        }
        $type = FConfig::item('config.salary_type');
        $condition_arr['condition'] = 'companyId in ('.$companyStr.') and salaryType = '.$type['SALARY_NIAN'];
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        //分页
        $data['count'] = $this->salarytime_other_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        //'order'=>'t.id DESC',
        $condition_arr['order'] = 't.id DESC';
        $data['list'] = $this->salarytime_other_model->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('examineNianSalary',$data);
    }
    public function actionUpdateNianYear () {
        $sal_id = $this->request->getParam('sal_id');
        $salaryYear = $this->request->getParam('salaryYear');
        $this->salarytime_other_model->updateByPk($sal_id,array('year'=>$salaryYear));
        $response['status'] = 100000;
        $response['content'] = '更新成功';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionUpdateExamineStatus () {
        $response = array();
        $type = $this->request->getParam('type');
        $id = $this->request->getParam('id');
        if ($type == 'first') {

            $res = $this->salary_time_model->updateByPk($id,array('salary_status'=>1),'salary_status=:status',array(':status'=>0));
        } elseif ($type == 'er') {
            $res = SalarytimeOther::model()->updateByPk($id,array('salary_status'=>1),'salary_status=:status',array(':status'=>0));
        } elseif ($type == 'nian') {
            $res = SalarytimeOther::model()->updateByPk($id,array('salary_status'=>1),'salary_status=:status',array(':status'=>0));
        }
        if ($res) {
            $response['status'] = 100000;
            $response['content'] = '申请成功';
        } else {
            print_r(SalarytimeOther::model()->getErrors());
            $response['status'] = 100001;
            $response['content'] = '申请失败，请检查您的操作';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    /**
     * 工资查询
     */
    public function actionSalaryFirstSearchPage () {
        $data = array();
        $data['active'] = 'first';
        $data['searchName'] = $this->request->getParam('name') ? trim($this->request->getParam('name')) : '';
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';

        $companyStr = $this->getAdminCompanyStr();

        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        if (empty($companyStr)) {
            $data['salaryTimeList'] = array();
            $pages = new FPagination(0);
            $pages->makePages();
            $data['page'] = $pages;
            $pages->setPageSize($page_size);
            $pages->setCurrent($page);
            $this->render('salaryFirstSearch',$data);
            return;
        }
        $condition_arr = array(

            'order'=>'t.id DESC',
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND customer.customer_name like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }

        if ($this->request->getParam('start_date')) {
            $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
            $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
        }
        if ($this->request->getParam('end_date')) {
            $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
            $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
        }
        //分页
        $data['count'] = $this->salary_time_model->with('customer')->count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $data['salaryTimeList'] = $this->salary_time_model->with('customer')->findAll($condition_arr);
        $data['page'] = $pages;

        $this->render('salaryFirstSearch',$data);
    }
    public function actionCheckSalaryList() {
        $this->layout = 'main_no_menu';
        $data['id'] = $this->request->getParam('id');
        $data['type'] = $type = $this->request->getParam('type');
        $bDetail = $this->request->getParam('bDetail') ? $this->request->getParam('bDetail') : 0;//是否显示详细工资表
        // 二次工资详细
        if ($type && $type == 'second') {
            $conditionArr = array(
                'condition' => 't.salaryTimeId=:id',
                'params' => array(
                    ':id' => $data['id']
                ),
            );
            $salaryList = $this->er_salary_model->with('employ')->findAll($conditionArr);
            $sal_mov_head = array();
            foreach ($salaryList as $key => $val) {
                $data['content'][$key][] = $val->employ->e_company;
                $data['content'][$key][] = $val->employ->e_name;
                $data['content'][$key][] = $val->employid;
                if ($bDetail) {
                    $array = json_decode($val->add_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$key][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    }
                }
                $data['content'][$key][] = $val->dangyueyingfa;
                $data['content'][$key][] = $val->ercigongziheji;
                $data['content'][$key][] = $val->yingfaheji;
                /*$data['content'][$key][] = $val->shiye;
                $data['content'][$key][] = $val->yiliao;
                $data['content'][$key][] = $val->yanglao;
                $data['content'][$key][] = $val->gongjijin;*/
                $data['content'][$key][] = $val->yingkoushui;
                $data['content'][$key][] = $val->yikoushui;
                $data['content'][$key][] = $val->bukoushui;
                $data['content'][$key][] = $val->jinka;
                $data['content'][$key][] = $val->jiaozhongqi;
            }
            if ($bDetail) {
                $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                    SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
            } else {
                $data['header'] = array('公司名称','姓名','身份证号','当月应发合计','二次工资合计','应发合计','应扣税','已扣税','补扣税','进卡','缴中企基业');//'失业','医疗','养老','公积金',
            }

            $salarySum = $this->salary_er_total_model->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
            $sum = $this->getSumErSalaryArray($salarySum,$data['header'],$data['content']);
            $data['content'] = $sum;
            $this->render('salaryViewPage',$data);
            exit;
        }
        // 年终奖详细
        if ($type && $type == 'nian') {
            $conditionArr = array(
                'condition' => 't.salaryTimeId=:id',
                'params' => array(
                    ':id' => $data['id']
                ),
            );
            $salaryList = $this->salary_nian_model->with('employ')->findAll($conditionArr);
            $sal_mov_head = array();
            foreach ($salaryList as $key => $val) {
                $data['content'][$key][] = $val->employ->e_company;
                $data['content'][$key][] = $val->employ->e_name;
                $data['content'][$key][] = $val->employid;
                if ($bDetail) {
                    $array = json_decode($val->add_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$key][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    }
                }
                $data['content'][$key][] = $val->nianzhongjiang;
                $data['content'][$key][] = $val->nian_daikoushui;
                $data['content'][$key][] = $val->yingfaheji;
                $data['content'][$key][] = $val->shifajinka;
                $data['content'][$key][] = $val->jiaozhongqi;
            }
            if ($bDetail) {
                $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                    SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
            } else {
                $data['header'] = array('公司名称','姓名','身份证号','年终奖','代扣税','应发合计','实发合计','缴中企基业');
            }

            $salarySum = $this->salary_nian_total_model->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
            $sum = $this->getSumNianSalaryArray($salarySum,$data['header'],$data['content']);
            $data['content'] = $sum;
            $this->render('salaryViewPage',$data);
            exit;
        }

        // 一次工资详细
        $conditionArr = array(
            'select' => 't.employid,t.sal_add_json,t.per_yingfaheji,t.per_shiye,t.per_yiliao,t.per_yanglao,t.per_gongjijin,t.per_daikoushui,t.per_koukuangheji,t.per_shifaheji,t.com_shiye,t.com_yiliao,t.com_yanglao,t.com_gongshang,t.com_shengyu,t.com_gongjijin,t.com_heji,t.laowufei,t.canbaojin,t.danganfei,t.paysum_zhongqi',
            'condition' => 't.salaryTimeId=:id',
            'params' => array(
                ':id' => $data['id']
            ),
        );
        $salaryList = $this->salary_model->with('employ')->findAll($conditionArr);
        $sal_mov_head = array();
        foreach ($salaryList as $key => $val) {
            $data['content'][$key][] = $val->employ->e_company;
            $data['content'][$key][] = $val->employ->e_name;
            $data['content'][$key][] = $val->employid;
            if ($bDetail) {
                $array = json_decode($val->sal_add_json);
                $heads =array();
                foreach ($array as $mov) {
                    $heads[] = urldecode($mov->key);
                    $data['content'][$key][] = $mov->value;
                }

                if (empty($sal_mov_head)) {
                    $sal_mov_head = $heads;
                }
            }
            $data['content'][$key][] = $val->per_yingfaheji;
            $data['content'][$key][] = $val->per_shiye;
            $data['content'][$key][] = $val->per_yiliao;
            $data['content'][$key][] = $val->per_yanglao;
            $data['content'][$key][] = $val->per_gongjijin;
            $data['content'][$key][] = $val->per_daikoushui;
            $data['content'][$key][] = $val->per_koukuangheji;
            $data['content'][$key][] = $val->per_shifaheji;
            $data['content'][$key][] = $val->com_shiye;
            $data['content'][$key][] = $val->com_yiliao;
            $data['content'][$key][] = $val->com_yanglao;
            $data['content'][$key][] = $val->com_gongshang;
            $data['content'][$key][] = $val->com_shengyu;
            $data['content'][$key][] = $val->com_gongjijin;
            $data['content'][$key][] = $val->com_heji;
            $data['content'][$key][] = $val->laowufei;
            $data['content'][$key][] = $val->canbaojin;
            $data['content'][$key][] = $val->danganfei;
            $data['content'][$key][] = $val->paysum_zhongqi;
        }
        if ($bDetail) {
            $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
        } else {
            $data['header'] = array('公司名称','姓名','身份证号','个人应发合计','个人失业','个人医疗','个人养老','个人公积金','个人代扣税','个人扣款合计','实发合计','单位失业','单位医疗','单位养老','单位工伤','单位生育','单位公积金','单位合计','劳务费','残保金','档案费','缴中企基业合计');
        }

        $salarySum = $this->salary_total_model->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
        $sum = $this->getSumSalaryArray($salarySum,$data['header'],$data['content']);
        $data['content'] = $sum;
        $this->render('salaryViewPage',$data);
    }
    private function getSumSalaryArray ($salarySum,$head,$data) {
        $hei_count = array_search('个人应发合计', $head);

        for($j = 0; $j < $hei_count; $j ++) {
            if ($j == 0) {
                $sumArr [] = "合计";
            } else {
                $sumArr [] = " ";
            }
        }
        $sumArr[] = $salarySum['sum_per_yingfaheji'];
        $sumArr[] = $salarySum['sum_per_shiye'];
        $sumArr[] = $salarySum['sum_per_yiliao'];
        $sumArr[] = $salarySum['sum_per_yanglao'];
        $sumArr[] = $salarySum['sum_per_gongjijin'];
        $sumArr[] = $salarySum['sum_per_daikoushui'];
        $sumArr[] = $salarySum['sum_per_koukuangheji'];
        $sumArr[] = $salarySum['sum_per_shifaheji'];
        $sumArr[] = $salarySum['sum_com_shiye'];
        $sumArr[] = $salarySum['sum_com_yiliao'];
        $sumArr[] = $salarySum['sum_com_yanglao'];
        $sumArr[] = $salarySum['sum_com_gongshang'];
        $sumArr[] = $salarySum['sum_com_shengyu'];
        $sumArr[] = $salarySum['sum_com_gongjijin'];
        $sumArr[] = $salarySum['sum_com_heji'];
        $sumArr[] = $salarySum['sum_laowufei'];
        $sumArr[] = $salarySum['sum_canbaojin'];
        $sumArr[] = $salarySum['sum_danganfei'];
        $sumArr[] = $salarySum['sum_paysum_zhongqi'];

        $data [] = $sumArr;
        return $data;
    }
    private function getSumErSalaryArray ($salarySum,$head,$data) {
        $hei_count = array_search('当月应发合计', $head);

        for($j = 0; $j < $hei_count; $j ++) {
            if ($j == 0) {
                $sumArr [] = "合计";
            } else {
                $sumArr [] = " ";
            }
        }
        $sumArr[] = $salarySum['sum_dangyueyingfa'];
        $sumArr[] = $salarySum['sum_ercigongziheji'];
        $sumArr[] = $salarySum['sum_yingfaheji'];
       /* $sumArr[] = $salarySum['sum_shiye'];
        $sumArr[] = $salarySum['sum_yiliao'];
        $sumArr[] = $salarySum['sum_yanglao'];
        $sumArr[] = $salarySum['sum_gongjijin'];*/
        $sumArr[] = $salarySum['sum_yingkoushui'];
        $sumArr[] = $salarySum['sum_yikoushui'];
        $sumArr[] = $salarySum['sum_bukoushui'];
        $sumArr[] = $salarySum['sum_jinka'];
        $sumArr[] = $salarySum['sum_jiaozhongqi'];

        $data [] = $sumArr;
        return $data;
    }
    private function getSumNianSalaryArray ($salarySum,$head,$data) {
        $hei_count = array_search('年终奖', $head);

        for($j = 0; $j < $hei_count; $j ++) {
            if ($j == 0) {
                $sumArr [] = "合计";
            } else {
                $sumArr [] = " ";
            }
        }
        $sumArr[] = $salarySum['sum_nianzhongjiang'];
        $sumArr[] = $salarySum['sum_daikoushui'];
        $sumArr[] = $salarySum['sum_yingfaheji'];
        $sumArr[] = $salarySum['sum_shifajika'];
        $sumArr[] = $salarySum['sum_jiaozhongqi'];

        $data [] = $sumArr;
        return $data;
    }
    /**
     * 二次工资查询
     */
    public function actionSalarySecondSearchPage () {
        $data = array();
        $data['active'] = 'second';
        $data['searchName'] = $this->request->getParam('name') ? trim($this->request->getParam('name')) : '';
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';
        $list = $this->admin_company_model->findAll('adminId=:adminId',array(':adminId'=>$this->user->id));
        foreach ($list as $v) {
            $companyArr[] = $v->companyId;
        }
        $companyStr = implode(",",$companyArr);
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'order'=>'t.id DESC',
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 't.companyId in ('.$companyStr.') AND salaryType=5';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND customer.customer_name like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('start_date')) {
            $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
            $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
        }
        if ($this->request->getParam('end_date')) {
            $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
            $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
        }
        //分页
        $data['count'] = $this->salarytime_other_model->with('customer')->count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $data['salaryTimeList'] = $this->salarytime_other_model->with('customer')->findAll($condition_arr);
        $data['page'] = $pages;
        $data['salaryTypeStr'] = "er";
        $this->render('salaryErSearch',$data);
    }
    /**
     * 年终奖查询
     */
    public function actionSalaryNianSearchPage () {
        $data = array();
        $data['active'] = 'nian';
        $data['searchName'] = $this->request->getParam('name') ? trim($this->request->getParam('name')) : '';
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';
        $list = $this->admin_company_model->findAll('adminId=:adminId',array(':adminId'=>$this->user->id));
        foreach ($list as $v) {
            $companyArr[] = $v->companyId;
        }
        $companyStr = implode(",",$companyArr);
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'order'=>'t.id DESC',
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 't.companyId in ('.$companyStr.') AND salaryType=6';
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND customer.customer_name like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('start_date')) {
            $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
            $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
        }
        if ($this->request->getParam('end_date')) {
            $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
            $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
        }
        //分页
        $data['count'] = $this->salarytime_other_model->with('customer')->count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $data['salaryTimeList'] = $this->salarytime_other_model->with('customer')->findAll($condition_arr);
        $data['page'] = $pages;
        $data['salaryTypeStr'] = "nian";
        $this->render('salaryErSearch',$data);
    }
    public function getAdminCompany () {
        $condition_arr = array(
            'params' => array(
                ':adminId'=>$this->user->id,
            ),
            'order'=>'id DESC',
        );
        $condition_arr['condition'] = 'adminId=:adminId';
        $res = $this->admin_company_model->findAll($condition_arr);
        $i = 0;
        foreach ($res as $row) {
            $data['custom_list'][$i]['id'] = $row->companyId;
            $data['custom_list'][$i]['name'] = $row->companyName;
            $i++;
        }
        return $data;
    }
    public function actionToAddSalaryPage () {
        $id = trim($this->request->getParam('id'))>0 ? $this->request->getParam('id') : '';
        if (!$id) {
            echo '参数错误！';
            exit;
        }
        $data = $this->salary_time_model->with('customer')->find('t.id=:id', array(':id'=>$id));
        $this->render("addSalaryPage",$data);
    }
    public function actionGetSalHeadJson () {
        $salaryTimeId = $_REQUEST['salTimeId'];

        $salaryPo = $this->salary_model->with('employ')->find('t.salaryTimeId=:id', array(':id'=>$salaryTimeId));
        $salaryAddJson = $salaryPo->sal_add_json;
        $sal_del_json = $salaryPo->sal_del_json;
        $sal_free_json = $salaryPo->sal_free_json;
        $salHead = array();
        $salHead[] = '姓名';
        $salHead[] = '身份证号';
        $salHead[] = '卡号';
        $addJson = json_decode($salaryAddJson,true);
        $delJson = json_decode($sal_del_json,true);
        $freeJson = json_decode($sal_free_json,true);
        if (is_array($addJson)) {
            foreach($addJson as $val) {
                $key = urldecode($val['key']);
                $salHead[] = $key;
            }
        }
        if (is_array($delJson)) {
            foreach($delJson as $val) {
                $key = urldecode($val['key']);
                $salHead[] = $key;
            }
        }
        if (is_array($freeJson)) {
            foreach($freeJson as $val) {
                $key = urldecode($val['key']);
                $salHead[] = $key;
            }
        }
        $headData =array();
        $headData[] = $salHead;
        echo json_encode($headData);
        exit;
    }
    public function actionSumSalary() {
        $dataExcel = $_REQUEST['data'];
        $shenfenzheng = $_POST ['shenfenzheng']-1;
        $addArray = $_POST ['add'];
        $delArray = $_POST ['del'];
        if ($_POST ['freeTex']) {
            $freeTex = $_POST ['freeTex'];
        }
        $splitStr = "+";

        $shifajian = $_POST ['shifajian'];
        $addArray = explode ( $splitStr, $addArray );
        if (! empty ( $delArray )) {
            $delArray = explode ($splitStr, $delArray );
        } else {
            $delArray = "";
        }

        $head = $dynamic_head = $dataExcel [0];
        $addHeadArr =  array();
        foreach ( $addArray as $row ) {
            $addHeadArr[] = trim($dynamic_head [$row-1]);
        }
        $delHeadArr = array();
        if (! empty ( $delArray )) {
            foreach ( $delArray as $row ) {
                $delHeadArr[] = trim($head [$row-1]);
            }
        }

        $head = array_merge(SalaryConst::$salary_base_name_list,SalaryConst::$salary_required_name_list,
            $addHeadArr,$delHeadArr,
            SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);

        $count = count($head);
        if (! empty ( $freeTex )) {
            $head [$count] = "免税项";
        }
        if (! empty ( $_POST ['shifajian'] )) {
            $head [($count + 1)] = "实发合计减后项";
            $head [($count + 2)] = "交中企基业减后项";
        }

        $jisuan_var = array ();
        $error = array ();

        // 根据身份证号查询出员工身份类别
        $errorRow = 0;
        $userType = FConfig::item("config.employ_type");
        for($i = 1; $i < count ( $dataExcel ); $i ++) {
            if($dataExcel [$i] [$shenfenzheng] =='null') {
                continue;
            }
            $employ = $this->employ_model->find('e_num = :e_num',array(":e_num"=>trim($dataExcel[$i][$shenfenzheng])));

            if ($employ) {
                //基本信息
                $jisuan_var [$i] ['company_name'] = $employ ['e_company'];
                $jisuan_var [$i] ['e_name'] = $employ ['e_name'];
                $jisuan_var [$i] ['e_num'] = $employ ['e_num'];
                $jisuan_var [$i] ['e_teshu_state'] = $employ ['e_teshu_state'];
                $jisuan_var [$i] ['bank_num'] = $employ ['bank_num'];

                $jisuan_var [$i] ['yinhangkahao'] = $employ ['bank_num'];
                $jisuan_var [$i] ['shenfenleibie'] = $userType[$employ ['e_type']];
                $jisuan_var [$i] ['shebaojishu'] = $employ ['shebaojishu'];
                $jisuan_var [$i] ['gongjijinjishu'] = $employ ['gongjijinjishu'];
                $jisuan_var [$i] ['laowufei'] = $employ ['laowufei'];
                $jisuan_var [$i] ['canbaojin'] = $employ ['canbaojin'];
                $jisuan_var [$i] ['danganfei'] = $employ ['danganfei'];
            } else {
                $error [$errorRow] ["error"] = "第$i 行:未查询到该员工身份类别！";
                $errorRow++;
                continue;
            }
            $addValue = 0;
            $delValue = 0;
            $f= 0;
            foreach ( $addArray as $row ) {
                $addVal = trim($dataExcel [$i] [$row-1]);
                $headVal = urlencode(trim($head[$row-1]));
                if (preg_match('/^(-?\d+)(\.\d+)?$/i', $addVal)) {

                    $move [$i]['add'][$f] ['key'] = $headVal;
                    $move [$i]['add'][$f] ['value'] = $addVal;
                    $f++;
                    $addValue += $addVal;
                } else {
                    $dataExcel [$i] [$row-1] = $addVal.':无数值';
                    $error [$errorRow] ["error"] = "第$i 行 $headVal 列所加项非数字类型";
                    $errorRow++;
                    continue;
                }
            }

            $f= 0;
            if (! empty ( $delArray )) {
                foreach ( $delArray as $row ) {

                    $delVal = trim($dataExcel [$i] [$row-1]);
                    $headVal = trim($head [$row]);
                    if (preg_match('/^(-?\d+)(\.\d+)?$/i', $delVal)) {
                        $move [$i]['del'][$f] ['key'] = $headVal;
                        $move [$i]['del'][$f] ['value'] = $delVal;
                        $delValue += $delVal;
                        $f++;
                    } else {
                        $dataExcel [$i] [($row - 1)] = $delVal.':无数值';
                        $error [$errorRow] ["error"] = "第$i 行 第$row 列所加项非数字类型";
                        $errorRow++;
                        continue;
                    }
                }
            }
            $jisuan_var [$i] ["addValue"] = $addValue;
            $jisuan_var [$i] ["delValue"] = $delValue;
            if (! empty ( $freeTex )) {
                $jisuan_var [$i] ['freeTex'] = trim($dataExcel [$i] [$freeTex]);
                $move [$i]['freeTex'] ['key'] = urlencode($head [($freeTex)]);
                $move [$i]['freeTex'] ['value'] =  trim($dataExcel [$i] [($freeTex)]);
            } else {
                $jisuan_var [$i] ['freeTex'] = 0;
            }
        }
        $sumclass = new FSumSalary();
        $sumclass->getSumSalary ( $jisuan_var );
        $sumYingfaheji = 0;
        $sumGerenshiye = 0;
        $sumGerenyiliao = 0;
        $sumGerenyanglao = 0;
        $sumGerengongjijin = 0;
        $sumDaikousui = 0;
        $sumKoukuanheji = 0;
        $sumShifaheji = 0;
        $sumDanweishiye = 0;
        $sumDanweiyiliao = 0;
        $sumDanweiyanglao = 0;
        $sumDanweigongshang = 0;
        $sumDanweishengyu = 0;
        $sumDanweigongjijin = 0;
        $sumDanweiheji = 0;
        $sumLaowufeiheji = 0;
        $sumCanbaojinheji = 0;
        $sumDanganfeiheji = 0;
        $sumJiaozhongqiheji = 0;
        $data = array();

        for($i = 1; $i < count ( $dataExcel ); $i ++) {
            if($dataExcel [$i] [$shenfenzheng] =='null') {
                continue;
            }

            $salary = array();
            $salary [] = $jisuan_var [$i] ['company_name'];
            $salary [] = $jisuan_var [$i] ['e_name'];
            $salary [] = $jisuan_var [$i] ['e_num'];

            $salary [] = $jisuan_var [$i] ['yinhangkahao'];
            $salary [] = $jisuan_var [$i] ['shenfenleibie'];
            $salary [] = $jisuan_var [$i] ['shebaojishu'];
            $salary [] = $jisuan_var [$i] ['gongjijinjishu'];

            foreach ( $addArray as $row ) {
                $salary [] = trim($dataExcel [$i] [$row-1]);
            }
            if (! empty ( $delArray )) {
                foreach ( $delArray as $row ) {
                    $salary [] = trim($dataExcel [$i] [$row-1]);
                }
            }

            $sumYingfaheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['yingfaheji'] ) + 0;
            $sumGerenshiye += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenshiye'] ) + 0;
            $sumGerenyiliao += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenyiliao'] ) + 0;
            $sumGerenyanglao += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['gerenyanglao'] ) + 0;
            $sumGerengongjijin += $salary [] = $jisuan_var [$i] ['gerengongjijin'] + 0;

            $sumDaikousui += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['daikousui'] ) + 0;
            $sumKoukuanheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['koukuanheji'] ) + 0;
            $sumShifaheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['shifaheji'] ) + 0;

            $sumDanweishiye += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweishiye'] ) + 0;
            $sumDanweiyiliao += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiyiliao'] ) + 0;
            $sumDanweiyanglao += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiyanglao'] ) + 0;
            $sumDanweigongshang += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweigongshang'] ) + 0;
            $sumDanweishengyu += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweishengyu'] ) + 0;
            $sumDanweigongjijin += $salary [] = $jisuan_var [$i] ['danweigongjijin'] + 0;
            $sumDanweiheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danweiheji'] ) + 0;
            $sumLaowufeiheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['laowufei'] ) + 0;
            $sumCanbaojinheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['canbaojin'] ) + 0;
            $sumDanganfeiheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['danganfei'] ) + 0;
            $sumJiaozhongqiheji += $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['jiaozhongqiheji'] ) + 0;
            if (! empty ( $freeTex )) {
                $salary [] = sprintf ( "%01.2f", $jisuan_var [$i] ['freeTex'] ) + 0;
            }
            if (! empty ( $_POST ['shifajian'] )) {
                $salary [] = sprintf ( "%01.2f", ($jisuan_var [$i] ['shifaheji'] - $salary [$shifajian]) ) + 0;
                $salary [] = sprintf ( "%01.2f", ($jisuan_var [$i] ['jiaozhongqiheji'] - $salary [$shifajian]) ) + 0;
            }
            $data [] = $salary;
        }

        // 计算合计行
        $hejiArr = array();
        $hei_count = array_search('个人应发合计', $head);

        for($j = 0; $j < $hei_count; $j ++) {
            if ($j == 0) {
                $hejiArr [$j] = "合计";
            } else {
                $hejiArr [$j] = " ";
            }
        }
        $hejiArr[] = $sumYingfaheji;
        $hejiArr[] = $sumGerenshiye;
        $hejiArr[] = $sumGerenyiliao;
        $hejiArr[] = $sumGerenyanglao;
        $hejiArr[] = $sumGerengongjijin;
        $hejiArr[] = $sumDaikousui;
        $hejiArr[] = $sumKoukuanheji;
        $hejiArr[] = $sumShifaheji;
        $hejiArr[] = $sumDanweishiye;
        $hejiArr[] = $sumDanweiyiliao;
        $hejiArr[] = $sumDanweiyanglao;
        $hejiArr[] = $sumDanweigongshang;
        $hejiArr[] = $sumDanweishengyu;
        $hejiArr[] = $sumDanweigongjijin;
        $hejiArr[] = $sumDanweiheji;
        $hejiArr[] = $sumLaowufeiheji;
        $hejiArr[] = $sumCanbaojinheji;
        $hejiArr[] = $sumDanganfeiheji;
        $hejiArr[] = $sumJiaozhongqiheji;
        $data [] = $hejiArr;


        $result['result'] = 'ok';
        $result['shenfenleibie'] = array_search(SalaryConst::$salary_required_name_list[SalaryConst::PERSON_TYPE], $head);
        $result['move'] = $move;
        $result['data'] = $data;
        $result['head'] = $head;
        $result['error'] = $error;
        echo json_encode($result);
        exit;
    }
    public function actionSalaryPerSearch() {
        if ($this->user->admin_type == 3) {
            $data['custom_list'] = $this->getAdminCompanyList();
        } else {
            $data = $this->actionSearchCompany();

        }

        $this->render('salaryPerSearch',$data);
    }
    public function actionGetPerSalaryAjax () {
        $companyId = $this->request->getParam("company_id");
        $e_name = $this->request->getParam('e_name');
        $e_num = $this->request->getParam('e_num');
        $start_date = $this->request->getParam('start_date');
        $end_date = $this->request->getParam('end_date');
        $bDetail = false;
        $firstSalaryList = array();
        $erSalaryList = array();
        $nianSalaryList = array();
        if ($bDetail) {
            //$data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
              //  SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
        } else {
            $response['content']['header']['er'] = array('公司名称','工资月份','姓名','身份证号','当月应发合计','二次工资合计','应发合计','应扣税','已扣税','补扣税','进卡','缴中企基业');//'失业','医疗','养老','公积金',
            $response['content']['header']['nian'] = array('公司名称','工资月份','姓名','身份证号','年终奖','代扣税','应发合计','实发合计','缴中企基业');
            $response['content']['header']['first'] = array('公司名称','工资月份','姓名','身份证号','个人应发合计','个人失业','个人医疗','个人养老','个人公积金','个人代扣税','个人扣款合计','实发合计','单位失业','单位医疗','单位养老','单位工伤','单位生育','单位公积金','单位合计','劳务费','残保金','档案费','缴中企基业合计');
        }
        if (!empty($e_name) || !empty($e_num)) {
            if (!empty($e_name)) {
                $condition['condition']     = "e_name = :e_name";
                $condition['params'] = array(":e_name" => $e_name);
            } else if (!empty($e_num)) {
                $condition['condition']     = "e_num = :e_num";
                $condition['params'] = array(":e_num" => $e_num);
            }

            $employList =Employ::model()->findAll($condition);
            if (count($employList) > 0) {

                foreach ($employList as $val) {
                    if (!empty($start_date) || !empty($end_date)) {
                        $salTimeCondition["condition"] = " companyId={$val->e_company_id} ";
                        if (!empty($start_date)) {

                            $salTimeCondition["condition"] .= "and salaryTime >= '".$start_date."-01"."'";
                        }
                        if (!empty($end_date)) {
                            $salTimeCondition["condition"] .= "and salaryTime <= '".$end_date."-01"."'";
                        }
                        $salTimeModel = SalaryTime::model()->findAll($salTimeCondition);
                        $salTimeIds = array();
                        foreach ($salTimeModel as $v) {
                            $salTimeIds[] = $v->id;
                        }
                        $salTimeStr = implode(",",$salTimeIds);
                        $salTimeOtherModel = SalarytimeOther::model()->findAll($salTimeCondition);
                        $salTimeOtherIds = array();
                        foreach ($salTimeOtherModel as $v) {
                            $salTimeOtherIds[] = $v->id;
                        }
                        $salTimeOtherStr = implode(",",$salTimeOtherIds);
                    }
                    $salFCondition['condition'] = "employid = :employid";
                    if (!empty($salTimeStr)) {
                        $salFCondition['condition'] .= " and salaryTimeId in (".$salTimeStr.")";
                    }
                    $salFCondition['params'] = array(":employid" => $val->e_num);
                    $salOCondition['condition'] = "employid = :employid";
                    if (!empty($salTimeOtherStr)) {
                        $salOCondition['condition'] .= " and salaryTimeId in (".$salTimeOtherStr.")";
                    }
                    $salOCondition['params'] = array(":employid" => $val->e_num);
                    //一次工资
                    $salList = Salary::model()->findAll($salFCondition);
                    $firstSalaryList = $this->getFirstSalaryExcelTableFromSalData($salList,$firstSalaryList);
                    //二次工资
                    $salList = ErSalary::model()->findAll($salOCondition);
                    $erSalaryList = $this->getErSalaryExcelTableFromSalData($salList,$erSalaryList);
                    //年终奖
                    $salList = NianSalary::model()->findAll($salOCondition);
                    $nianSalaryList = $this->getNianSalaryExcelTableFromSalData($salList,$nianSalaryList);
                }

            }
        } else {

            $condition['condition'] = "e_company_id = :e_company_id";

            $condition['params'] = array(":e_company_id" => $companyId);
        }
        $response['content']['firstSalaryList'] = $firstSalaryList;
        $response['content']['erSalaryList'] = $erSalaryList;
        $response['content']['nianSalaryList'] = $nianSalaryList;
        //公司不存在
        $response['status'] = 100000;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    // FIXME 保存工资
    public function actionNewAddFirstSalary() {
        $excelMove = $_POST ['excelMove'];
        $excelHead = $_POST ['excelHead'];
        $company_id = $_POST ['company_id'];
        $salTimeId = $_POST ['salTimeId'];
        $salaryList = $_POST['data'];
        //$salaryList = array_slice($salaryList,0,count($salaryList)-1);
        $mark = $_POST ['mark'];
        foreach ( $excelHead  as $num => $row ) {
            if (strstr( $row, "身份证号" )) {
                $sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
            } elseif (strstr ( $row, "个人应发合计" )) {
                $sit_gerenyinfaheji = $num; // 得到个人应发合计字段的标志位
            }
        }
        // 开始事务
        $transaction = $this->customer_model->dbConnection->beginTransaction();
        // 查询公司信息
        $company = $this->customer_model->findByPk($company_id);
        if (! empty ( $company )) {

            $companyId = $company ['id'];
            // 根据日期查询公司时间
            $salaryTime = SalaryTime::model()->findByPk($salTimeId);
            if (empty ( $salaryTime ['id'] )) {
                $response['status'] = 100001;
                $response['content'] = " {$company['customer_name']} 本月未做工资 ,有问题请联系财务！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            } elseif ($salaryTime->salary_status > 0) {
                print_r($salaryTime);

                $response['status'] = 100001;
                $response['content'] = " {$company['customer_name']} 该月工资已经提交处理不能继续添加！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }

        } else {
            //公司不存在
            $response['status'] = 100001;
            $response['content'] = " 公司不存在！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $lastSalaryTimeId = $salaryTime ['id'] ;
        for($i = 0; $i < count ($salaryList); $i ++) {
            // 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
            $salary_list = array ();
            $salary_list ['per_yingfaheji'] = $salaryList [$i] [$sit_gerenyinfaheji];
            $salary_list ['per_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 1)];
            $salary_list ['per_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 2)];
            $salary_list ['per_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 3)];
            $salary_list ['per_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 4)];
            $salary_list ['per_daikoushui'] = $salaryList [$i] [($sit_gerenyinfaheji + 5)];
            $salary_list ['per_koukuangheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 6)];
            $salary_list ['per_shifaheji'] = $salaryList [$i] [($sit_gerenyinfaheji + 7)];
            $salary_list ['com_shiye'] = $salaryList [$i] [($sit_gerenyinfaheji + 8)];
            $salary_list ['com_yiliao'] = $salaryList [$i] [($sit_gerenyinfaheji + 9)];
            $salary_list ['com_yanglao'] = $salaryList [$i] [($sit_gerenyinfaheji + 10)];
            $salary_list ['com_gongshang'] = $salaryList [$i] [($sit_gerenyinfaheji + 11)];
            $salary_list ['com_shengyu'] = $salaryList [$i] [($sit_gerenyinfaheji + 12)];
            $salary_list ['com_gongjijin'] = $salaryList [$i] [($sit_gerenyinfaheji + 13)];
            $salary_list ['com_heji'] = $salaryList [$i] [($sit_gerenyinfaheji + 14)];
            $salary_list ['laowufei'] = $salaryList [$i] [($sit_gerenyinfaheji + 15)];
            $salary_list ['canbaojin'] = $salaryList [$i] [($sit_gerenyinfaheji + 16)];
            $salary_list ['danganfei'] = $salaryList [$i] [($sit_gerenyinfaheji + 17)];
            $salary_list ['paysum_zhongqi'] = $salaryList [$i] [($sit_gerenyinfaheji + 18)];

            $salary_list ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
            $salary_list ['salaryTimeId'] = $lastSalaryTimeId;
            if (!empty($excelMove [$i+1]['add'])){
                $salary_list ['sal_add_json'] = json_encode($excelMove [$i+1]['add']);
            }
            if (!empty($excelMove [$i+1]['del'])){
                $salary_list ['sal_del_json'] = json_encode($excelMove [$i+1]['del']);
            }
            if (!empty($excelMove [$i+1]['freeTex'])){
                $salary_list ['sal_free_json'] = json_encode($excelMove [$i+1]['freeTex']);
            }
            if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
                $arrTotal = array(
                    "condition" => "salaryTime_id = $salTimeId"
                );
                $salSumPo = Total::model()->find($arrTotal);
                $salSumPo['sum_per_yingfaheji'] += $salary_list ['per_yingfaheji'] ;
                $salSumPo['sum_per_shiye'] += $salary_list ['per_shiye']  ;
                $salSumPo ['sum_per_yiliao'] += $salary_list ['per_yiliao'] ;
                $salSumPo ['sum_per_yanglao'] += $salary_list ['per_yanglao'];
                $salSumPo ['sum_per_gongjijin'] += $salary_list ['per_gongjijin'];
                $salSumPo ['sum_per_daikoushui'] += $salary_list ['per_daikoushui'] ;
                $salSumPo ['sum_per_koukuangheji'] += $salary_list ['per_koukuangheji'];
                $salSumPo ['sum_per_shifaheji'] += $salary_list ['per_shifaheji'];
                $salSumPo ['sum_com_shiye'] += $salary_list ['com_shiye'];
                $salSumPo ['sum_com_yiliao'] += $salary_list ['com_yiliao'];
                $salSumPo ['sum_com_yanglao'] += $salary_list ['com_yanglao'];
                $salSumPo['sum_com_gongshang'] += $salary_list ['com_gongshang'];
                $salSumPo['sum_com_shengyu'] += $salary_list ['com_shengyu'];
                $salSumPo['sum_com_gongjijin'] += $salary_list ['com_gongjijin'];
                $salSumPo['sum_com_heji'] += $salary_list ['com_heji'];
                $salSumPo ['sum_laowufei'] += $salary_list ['laowufei'];
                $salSumPo ['sum_canbaojin'] += $salary_list ['canbaojin'];
                $salSumPo ['sum_danganfei'] += $salary_list ['danganfei'];
                $salSumPo ['sum_paysum_zhongqi'] += $salary_list ['paysum_zhongqi'];

                // 以上保存成功后，保存合计项
                $lastSumSalaryId = $salSumPo->update($update);
                if (! $lastSumSalaryId) {
                    $transaction->rollback ();
                    $response['status'] = 100001;
                    $response['content'] = " 保存合计工资失败！";
                    Yii::app()->end(FHelper::json($response['content'],$response['status']));
                }
            } else {
                if (empty($salaryList [$i] [$sit_gerenyinfaheji])) {
                    continue;
                }
                $salaryModel = Salary::model()->find("salaryTimeId = $salTimeId and employid = {$salary_list ['employid']}");
                if (!empty($salaryModel)) {
                    $transaction->rollback ();
                    $response['status'] = 100001;
                    $response['content'] = " 身份证号：{$salary_list ['employid']} {$salaryTime['salaryTime']}已经做过一次工资！";
                    Yii::app()->end(FHelper::json($response['content'],$response['status']));
                }
                $this->salary_model = new Salary();
                $this->salary_model->attributes = $salary_list;
                $lastSalaryId = $this->salary_model->save();
            }
            if (! $lastSalaryId && $lastSalaryId != 0) {
                $transaction->rollback ();
                $response['status'] = 100001;
                $response['content'] = " 保存固定工资失败！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }

        }

        // 事务提交
        $transaction->commit ();
        $response['content'] = "保存一次工资成功";
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    // 保存工资合计项
    private function saveSumSalary($salary) {
        $sql = "insert  into  oa_total (salaryTime_Id,sum_per_yingfaheji,sum_per_shiye,sum_per_yiliao,sum_per_yanglao,sum_per_gongjijin,sum_per_daikoushui
    	,sum_per_koukuangheji,sum_per_shifaheji,sum_com_shiye,sum_com_yiliao,sum_com_yanglao,sum_com_gongshang,sum_com_shengyu,sum_com_gongjijin,sum_com_heji,
    	sum_laowufei,sum_canbaojin,sum_danganfei,sum_paysum_zhongqi
    	) values({$salary['salaryTimeId']},{$salary['per_yingfaheji']},
    	{$salary['per_shiye']},{$salary['per_yiliao']},{$salary['per_yanglao']},{$salary['per_gongjijin']},
    	{$salary['per_daikoushui']},{$salary['per_koukuangheji']},{$salary['per_shifaheji']},{$salary['com_shiye']},{$salary['com_yiliao']},
    	{$salary['com_yanglao']},{$salary['com_gongshang']},{$salary['com_shengyu']},{$salary['com_gongjijin']},
    	{$salary['com_heji']},{$salary['laowufei']},{$salary['canbaojin']},{$salary['danganfei']},{$salary['paysum_zhongqi']});";

        return $sql;
    }

    /**
     * @param $salList
     * @param $firstSalaryList 返回的变量值
     * @param bool $bDetail
     * @return array
     */
    private function getFirstSalaryExcelTableFromSalData ($salList,$firstSalaryList,$bDetail = false) {
        foreach ($salList as $val) {
            $sqlPO = array();
            $sqlPO[] = $val->employ->e_company;
            $time = strtotime($val->salaryTime->salaryTime);
            $sqlPO[] = date("Y年m月",$time);
            $sqlPO[] = $val->employ->e_name;
            $sqlPO[] = $val->employid;
            if ($bDetail) {
                $array = json_decode($val->sal_add_json);
                $heads =array();
                foreach ($array as $mov) {
                    $heads[] = urldecode($mov->key);
                    $sqlPO[] = $mov->value;
                }

                if (empty($sal_mov_head)) {
                    $sal_mov_head = $heads;
                }
            }
            $sqlPO[] = $val->per_yingfaheji;
            $sqlPO[] = $val->per_shiye;
            $sqlPO[] = $val->per_yiliao;
            $sqlPO[] = $val->per_yanglao;
            $sqlPO[] = $val->per_gongjijin;
            $sqlPO[] = $val->per_daikoushui;
            $sqlPO[] = $val->per_koukuangheji;
            $sqlPO[] = $val->per_shifaheji;
            $sqlPO[] = $val->com_shiye;
            $sqlPO[] = $val->com_yiliao;
            $sqlPO[] = $val->com_yanglao;
            $sqlPO[] = $val->com_gongshang;
            $sqlPO[] = $val->com_shengyu;
            $sqlPO[] = $val->com_gongjijin;
            $sqlPO[] = $val->com_heji;
            $sqlPO[] = $val->laowufei;
            $sqlPO[] = $val->canbaojin;
            $sqlPO[] = $val->danganfei;
            $sqlPO[] = $val->paysum_zhongqi;
            $firstSalaryList[] = $sqlPO;
        }
        return $firstSalaryList;
    }
    private function getErSalaryExcelTableFromSalData ($salList,$erSalaryList,$bDetail = false) {
        foreach ($salList as $val) {
            $sqlPO = array();
            $sqlPO[] = $val->employ->e_company;
            $time = strtotime($val->salaryTime->salaryTime);
            $sqlPO[] = date("Y年m月",$time);
            $sqlPO[] = $val->employ->e_name;
            $sqlPO[] = $val->employid;
            if ($bDetail) {
                $array = json_decode($val->add_json);
                $heads =array();
                foreach ($array as $mov) {
                    $heads[] = urldecode($mov->key);
                    $sqlPO[] = $mov->value;
                }

                if (empty($sal_mov_head)) {
                    $sal_mov_head = $heads;
                }
            }
            $sqlPO[] = $val->dangyueyingfa;
            $sqlPO[] = $val->ercigongziheji;
            $sqlPO[] = $val->yingfaheji;
            $sqlPO[] = $val->yingkoushui;
            $sqlPO[] = $val->yikoushui;
            $sqlPO[] = $val->bukoushui;
            $sqlPO[] = $val->jinka;
            $sqlPO[] = $val->jiaozhongqi;
            $erSalaryList[] = $sqlPO;
        }
        return $erSalaryList;
    }
    private function getNianSalaryExcelTableFromSalData ($salList,$nianSalaryList,$bDetail = false) {
        foreach ($salList as $val) {
            $sqlPO = array();
            $sqlPO[] = $val->employ->e_company;
            $time = strtotime($val->salaryTime->salaryTime);
            $sqlPO[] = date("Y年m月",$time);
            $sqlPO[] = $val->employ->e_name;
            $sqlPO[] = $val->employid;
            if ($bDetail) {
                $array = json_decode($val->add_json);
                $heads =array();
                foreach ($array as $mov) {
                    $heads[] = urldecode($mov->key);
                    $sqlPO[] = $mov->value;
                }

                if (empty($sal_mov_head)) {
                    $sal_mov_head = $heads;
                }
            }
            $sqlPO[] = $val->nianzhongjiang;
            $sqlPO[] = $val->nian_daikoushui;
            $sqlPO[] = $val->yingfaheji;
            $sqlPO[] = $val->shifajinka;
            $sqlPO[] = $val->jiaozhongqi;
            $nianSalaryList[] = $sqlPO;
        }
        return $nianSalaryList;
    }
}