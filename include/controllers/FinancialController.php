<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/7/30
 * Time: 下午11:06
 */
class FinancialController extends FController
{
    private $employ_model;
    private $employInfo_model;
    private $customer_model;
    private $m_employ_model;
    private $m_employ_construct_model;
    private $m_social_model;
    private $m_gjjin_model;
    private $admin_model;
    private $bankDeal_model;
    private $salary_time_model;
    private $admin_company_model;
    private $salary_model;
    private $salary_total_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->employ_model = new Employ();
        $this->employInfo_model = new EmployInfo();
        $this->customer_model = new Customer();
        $this->m_employ_model = new MEmploy();
        $this->m_employ_construct_model = new MEmployConstruct();
        $this->m_social_model = new MSocial();
        $this->m_gjjin_model = new MGjjin();
        $this->admin_model = new Admin();
        $this->bankDeal_model = new BankDeal();
        $this->salary_time_model = new SalaryTime();
        $this->admin_company_model = new AdminCompany();
        $this->salary_model = new Salary();
        $this->salary_total_model = new Total();

    }

    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }
    public function actionToBankIntoOutPage () {
        //$data = $this->actionSearchCompany();
        $res = Customer::model()->findAll();
        $i = 0;
        foreach ($res as $row) {
            $data['custom_list'][$i]['id'] = $row->id;
            $data['custom_list'][$i]['name'] = $row->customer_name;
            $data['companyList'][] = $row->customer_name;
            $i++;
        }

        $data['user'] = $this->user;
        $this->render('bankIntoOut',$data);
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
    public function actionGetDealList() {
        $condition_arr = array(
            'order' => 'id ',
        );
        $condition_arr['condition'] = '1=1';
        if (!empty($_POST['period']) && in_array($_POST['period'],array(1,2,3))) {
            $startDate = $_POST['period'] == 1 ? date('Y-m-d',strtotime('-7 days')) : ($_POST['period']==2 ? date('Y-m-d',strtotime('-1 months')) : date('Y-m-d',strtotime('-3 months')));
            $condition_arr['condition'] .= ' and deal_date>:date';
            $condition_arr['params'] = array(':date'=>$startDate);
        } else {
            if ($_POST['start_date']) {
                $condition_arr['condition'] .= ' and deal_date>:start_date';
                $condition_arr['params'][':start_date'] = $_POST['start_date'];
            }
            if ($_POST['start_date'] && $_POST['end_date']) {
                $condition_arr['condition'] .= ' and deal_date<:end_date';
                $condition_arr['params'][':end_date'] = $_POST['end_date'];
            } else if ($_POST['end_date']) {
                $condition_arr['condition'] .= ' and deal_date<:end_date';
                $condition_arr['params'] = array(':end_date'=>$_POST['end_date']);
            }
        }
        if (!empty($_POST['companyList'])) {
            $condition_arr['condition'] .= ' and deal_company_name=:deal_company_name';
            $condition_arr['params'][':deal_company_name'] = $_POST['companyList'];
        }
        $result = $this->bankDeal_model->findAll($condition_arr);
        $dealList = array();
        $deal_status_text = array(
            0=>'待确认',1=>'已确认',2=>'已拒绝',
        );
        foreach ($result as $row) {
            $deal = array();
            $deal['row_id'] = $row->id;
            $deal['deal_company_id'] = $row->deal_company_id;
            $deal['deal_company_name'] = $row->deal_company_name;
            $deal['deal_date'] = $row->deal_date;
            $deal['deal_name'] = $row->deal_name;
            $deal['deal_type'] = $row->deal_type;
            $deal['deal_mark'] = $row->deal_mark;
            $deal['deal_status'] = $row->deal_status;
            if ($row->deal_type == 1) {//贷入
                $deal['deal_into_val'] = $row->deal_val;
                $deal['deal_out_val'] = '';

            } elseif ($row->deal_type == 2) {//支出

                $deal['deal_into_val'] = '';
                $deal['deal_out_val'] = $row->deal_val;
            }
            if ($row->deal_company_id && $this->user->admin_type == 4) {
                if ($deal['deal_status'] < 1) {

                    $deal['option'] = "<a  class='confirm' data-id='{$row->id}' style='color: #00B83F;cursor: pointer;'>确认</a> | <a href='#' class='cancel' data-id='{$row->id}' style='color: red;cursor: pointer;'>拒绝</a>";

                } else {
                    $color = 'blue';
                    $class_name = "";
                    if ($deal['deal_status'] == 1) {
                        $color = 'green';
                    } elseif ($deal['deal_status'] == 2){
                        $color = 'red';
                        $class_name = "reconfirm";
                    }
                    $deal['option'] = "<span style='color: {$color}' data-id='{$row->id}'  class='{$class_name}'>{$deal_status_text[$deal['deal_status']]}</span>";
                }
            } else {
                $color = 'blue';
                if ($deal['deal_status'] == 1) {
                    $color = 'green';
                } elseif ($deal['deal_status'] == 2){
                    $color = 'red';
                    $class_name = "reconfirm";
                }
                $deal['option'] = "<span style='color: {$color}' data-id='{$row->id}'  class='{$class_name}'>{$deal_status_text[$deal['deal_status']]}</span>";
            }
            $dealList[] = $deal;

        }
        $data['data_list'] = $dealList;
        echo json_encode($data);
        exit;
    }
    public function actionUpdateCurrentAccount () {
        $updateData = $this->request->getParam('data');
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($updateData as $row) {

                $dealModel = new BankDeal();
                $company = Customer::model()->find("customer_name=:customer_name",array("customer_name"=>$row['deal_company_name']));
                //添加单位入账明细

                $condition_arr = array(
                    "deal_company_id" => $company['id'],
                    "deal_company_name" => $row['deal_company_name'],
                    "update_time" => date("Y-m-d H:i:s",time())
                );
                $result = $dealModel->updateByPk($row['row_id'],$condition_arr);
            }
            $transaction->commit ();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
        }
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionCancelDeal () {

        $dealId = $this->request->getParam('dealId');
        BankDeal::model()->updateByPk($dealId,array("deal_status"=>'2'));
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionReconfirmDeal () {

        $dealId = $this->request->getParam('dealId');
        BankDeal::model()->updateByPk($dealId,array("deal_status"=>'0'));
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionConfirmDeal () {
        $dealId = $this->request->getParam('dealId');
        $transaction = Yii::app()->db->beginTransaction();
        try
        {

            $dealModel = new BankDeal();
            $dealPO = $dealModel->findByPk($dealId);

            $row = $dealPO->getAttributes();

            BankDeal::model()->updateByPk($dealId,array("deal_status"=>'1'));
            $row['row_id'] = $dealId;
            $company = Customer::model()->find("customer_name=:customer_name",array("customer_name"=>$row['deal_company_name']));


            $currentAccount = CurrentAccount::model()->find("source_id = {$row['row_id']} and source_type = 'BankDeal'");
            if (empty($currentAccount)) {
                $currentAccountModel = new CurrentAccount();
                $currentAccountModel->company_id = $company['id'];
                $currentAccountModel->company_name = $row['deal_company_name'];
                $currentAccountModel->source_id = $row['row_id'];
                $currentAccountModel->source_type = get_class($dealModel);
                $currentAccountModel->account_val = $company['account_val'];
                $currentAccountModel->pay_val = $row['deal_val'];
                $currentAccountModel->remian_val = $accountVal = $company['account_val'] + $row['deal_val'];
                $currentAccountModel->memo = $row['deal_date'].'日'.$row['deal_company_name'].'入账';
                $currentAccountModel->op_date = $row['deal_date'];
                $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
                $currentAccountModel->u_time = date("Y-m-d H:i:s",time());

                $result = $currentAccountModel->save();

                $this->updateCustomerAccountValById($company['id'],$accountVal);
            } else {//如果单位更改
                if($row['deal_company_name'] == '暂无') {
                    $oldCompany = Customer::model()->findByPk($currentAccount['company_id']);
                    $accountVal = $oldCompany['account_val'] - $row['deal_into_val'];
                    $this->updateCustomerAccountValById($currentAccount['company_id'],$accountVal);
                    $currentAccount->deleteByPk($currentAccount['id']);
                } else {
                    //todo 先恢复原来单位余额
                    $oldCompany = Customer::model()->findByPk($currentAccount['company_id']);
                    $currentAccountModel = new CurrentAccount();
                    $currentAccountModel->company_id = $currentAccount['company_id'];
                    $currentAccountModel->company_name = $currentAccount['company_name'];
                    $currentAccountModel->source_id = $row['row_id'];
                    $currentAccountModel->source_type = 'accountUpdate';
                    $currentAccountModel->account_val = $oldCompany['account_val'];
                    $currentAccountModel->pay_val = -$row['deal_into_val'];
                    $currentAccountModel->remian_val = $accountVal = $oldCompany['account_val'] - $row['deal_into_val'];
                    $currentAccountModel->memo = $row['deal_date'].'日'.$row['deal_company_name'].'单位变更账户修改';
                    $currentAccountModel->op_date = $row['deal_date'];
                    $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
                    $currentAccountModel->u_time = date("Y-m-d H:i:s",time());
                    $currentAccountModel->save();
                    $this->updateCustomerAccountValById($oldCompany['id'],$accountVal);
                    //todo 修改现在单位余额
                    $currentAccount->company_id = $company['id'];
                    $currentAccount->company_name = $row['deal_company_name'];
                    $currentAccount->source_id = $row['row_id'];
                    $currentAccount->source_type = get_class($dealModel);
                    $currentAccount->account_val = $company['account_val'];
                    $currentAccount->pay_val = $row['deal_into_val'];
                    $currentAccount->remian_val = $accountVal = $company['account_val'] + $row['deal_into_val'];
                    $currentAccount->memo = $row['deal_date'].'日'.$row['deal_company_name'].'入账';
                    $currentAccount->u_time = date("Y-m-d H:i:s",time());
                    $currentAccount->update();
                    $this->updateCustomerAccountValById($company['id'],$accountVal);
                }

            }
            $transaction->commit ();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
        }
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionSaveOrUpdateDeal () {
        $updateData = $this->request->getParam('data');

        $error_list = array();
        $success_list = array();
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            foreach ($updateData as $row) {

                $dealModel = new BankDeal();
                $company = Customer::model()->find("customer_name=:customer_name",array("customer_name"=>$row['deal_company_name']));

                if ($row['row_id'] == 'null' || empty($row['row_id'])) {
                    if (!empty($company)) {

                        $dealModel->deal_company_id = $company['id'];
                    }
                    $dealModel->deal_company_name = $row['deal_company_name'];
                    $dealModel->deal_date = $row['deal_date'];
                    $dealModel->deal_name = $row['deal_name'];
                    $dealModel->deal_mark = $row['deal_mark'];
                    if ($row['deal_into_val'] > 0) {
                        $dealModel->deal_type = 1;
                        $dealModel->deal_val = $row['deal_into_val'];
                    } elseif ($row['deal_out_val'] > 0) {
                        $dealModel->deal_type = 2;
                        $dealModel->deal_val = $row['deal_out_val'];
                    }
                    $dealModel->add_time = date("Y-m-d H:i:s",time());
                    $dealModel->update_time = date("Y-m-d H:i:s",time());
                    $result = $dealModel->save();
                    if ($result) {
                        $row['row_id'] = $dealModel->id;
                        $success_list[] = $row;
                    }
                }else {
                    if ($row['deal_into_val'] > 0) {
                        $deal_type = 1;
                        $deal_val = $row['deal_into_val'];
                    } elseif ($row['deal_out_val'] > 0) {
                        $deal_type = 2;
                        $deal_val = $row['deal_out_val'];
                    }
                    $condition_arr = array(
                        "deal_company_id" => $company['id'],
                        "deal_company_name" => $row['deal_company_name'],
                        "deal_date" => $row['deal_date'],
                        "deal_name" => $row['deal_name'],
                        "deal_mark" => $row['deal_mark'],
                        "deal_val" => $deal_val,
                        "deal_type" => $deal_type,
                        "update_time" => date("Y-m-d H:i:s",time())
                    );
                    $result = $dealModel->updateByPk($row['row_id'],$condition_arr);
                    if ($result) {
                        $success_list[] = $row;
                    }
                }


                $error = $dealModel->getErrors();
                if (!empty($error)) {
                    $error_list[] = $error;
                }
            }
            $transaction->commit ();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
        }
        $response['content']['successList'] = $success_list;
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionGetCustomerListJson () {
        $customer_name = $this->request->getParam('query');
        $condition_arr = array(
            'condition' => 'customer_name like "%'.$customer_name.'%" ',
            'params' => array(
                'customer_name' => $customer_name
            )
        );
        $result = $this->customer_model->findAll($condition_arr);//print_r($result);
        $customerList = array();
        $customerList[] = '暂无';
        foreach ($result as $row) {
            /*$customer = array();
            $customer['id'] = $row->id;real_name
            $customer['customer_name'] = $row->customer_name;*/
            $customerList[] = $row->customer_name;
        }
        $response['status'] = 100000;
        $response['content'] = $customerList;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    public function actionDelBankIntoOut () {
        $ids = $this->request->getParam('ids');
        $bError = false;
        foreach ($ids as $_id)  {
            $res = $this->bankDeal_model->deleteByPk($_id);
            if (!$res) {
                $bError = true;
            }
        }
        if (!$bError) {
            $response['status'] = 100000;
            $response['content'] = '删除成功！';
        } else {
            $response['status'] = 100001;
            $response['content'] = '删除失败！';
        }

        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
   public function actionTest () {
       echo 'test';exit;
   }

    /**
     * 工资审核
     */
    public function actionExamineSalary () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');
        $data['sort'] = $sort = $this->request->getParam('sort');
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')'.' and salary_status > 0' ;
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = $this->salary_time_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $list = $this->salary_time_model->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = Total::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_per_shifaheji;
            $row['pay_zhongqi'] = $totalModel->sum_paysum_zhongqi;
            $row['sum_per_daikoushui'] = $totalModel->sum_per_daikoushui;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;
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
        $data['sort'] = $sort = $this->request->getParam('sort');
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        $condition_arr['condition'] .= "  and salary_status > 0";
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        $condition_arr['condition'] .= " AND salaryType = 5 ";

        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = SalarytimeOther::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $list = SalarytimeOther::model()->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = ErTotal::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_jinka;
            $row['pay_zhongqi'] = $totalModel->sum_jiaozhongqi;
            $row['sum_bukoushui'] = $totalModel->sum_bukoushui;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;
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
        $data['sort'] = $sort = $this->request->getParam('sort');
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        $condition_arr['condition'] .= "  and salary_status > 0";
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        $condition_arr['condition'] .= " AND salaryType = 6 ";

        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = SalarytimeOther::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $list = SalarytimeOther::model()->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = NianTotal::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_shifajika;
            $row['pay_zhongqi'] = $totalModel->sum_jiaozhongqi;
            $row['sum_daikoushui'] = $totalModel->sum_daikoushui;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;
        $data['page'] = $pages;
        $this->render('examineNianSalary',$data);
    }

    public function actionDealExamine () {
        $response = array();
        $id = $this->request->getParam('id');
        $val = $this->request->getParam('val');
        $type = $this->request->getParam('type');
        if ($val == 1) $status = 2;
        else $status = $val;
        if (in_array($val,array(1,2,3))) {
            if ($type == 'first') {
                $res = $this->salary_time_model->updateByPk($id,array('salary_status'=>$status));
                if ($val == 1) {
                    $model = array ();
                    $salaryTimeModel= SalaryTime::model()->findByPk($id);
                    $salaryTotalModel = Total::model()->find("salaryTime_id = $id");
                    $customerModel = Customer::model()->findByPk($salaryTimeModel->companyId);
                    $model['company_id'] = $salaryTimeModel->companyId;
                    $model['company_name'] = $salaryTimeModel->companyName;
                    $model['source_id'] = $id;
                    $model['model_class'] = "SalaryTime";
                    $model['account_val'] = $customerModel->account_val;
                    $model['shifa'] = -floatval($salaryTotalModel->sum_per_shifaheji);
                    $model['canbaojin'] = -floatval($salaryTotalModel->sum_canbaojin);
                    $model['laowufei'] = -floatval($salaryTotalModel->sum_laowufei);
                    $model['text'] = $salaryTimeModel->companyName.date("Y-m",strtotime($salaryTimeModel->salaryTime));

                    $account_val = $this->addShifaAccountItem($model);
                    $model['account_val'] = $account_val;
                    $account_val = $this->addCanbaojinAccountItem($model);
                    $model['account_val'] = $account_val;
                    $this->addLaofuweiAccountItem($model);

                }

            } elseif ($type == 'nian' || $type == 'er') {
                $res = SalarytimeOther::model()->updateByPk($id,array('salary_status'=>$status));
                if ($val == 1) {
                    $model = array ();
                    $salaryTimeOtherModel= SalarytimeOther::model()->findByPk($id);
                    if ($type == 'nian') {
                        $salaryTotalModel = NianTotal::model()->find("salaryTime_id = $id");
                        $model['deal_out_val'] = -floatval($salaryTotalModel->sum_shifajika);
                    }
                    elseif($type == 'er') {
                        $salaryTotalModel = ErTotal::model()->find("salaryTime_id = $id");
                        $model['deal_out_val'] = -floatval($salaryTotalModel->sum_jinka);
                    }
                    $customerModel = Customer::model()->findByPk($salaryTimeOtherModel->companyId);
                    $model['company_id'] = $salaryTimeOtherModel->companyId;
                    $model['company_name'] = $salaryTimeOtherModel->companyName;
                    $model['source_id'] = $id;
                    $model['model_class'] = "SalarytimeOther";
                    $model['account_val'] = $customerModel->account_val;

                    $textSalaryType = $type == 'nian'?'年终奖':'二次';
                    $model['text'] = $salaryTimeOtherModel->companyName.date("Y-m",strtotime($salaryTimeOtherModel->salaryTime)).$textSalaryType.'工资发放支出';
                    $this->addCurrentAccount($model);

                }
            }
            if ($res) {
                $response['status'] = 100000;
                $response['content'] = '操作成功';
            } else {
                $response['status'] = 100002;
                $response['content'] = '操作失败，请检查您的操作';
            }
        } else {
            $response['status'] = 100001;
            $response['content'] = '请检查您的操作';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionSalaryAccount () {
        $adminStr = $this->getAdminCompanyStr();
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        //分页参数
        //$companyStr = $this->getAdminCompanyStr();
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //$condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        $condition_arr['condition'] .= " 1=1 ";
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND customer_name like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        $condition_arr['condition'] .= " AND id in ($adminStr)";
        //分页
        $data['count'] = Customer::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['list'] = Customer::model()->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('salaryAccount',$data);
    }
    public function actionSalaryAccountDetail () {
        $company['companyId'] = $this->request->getParam('companyId');
        $company['name'] = $this->request->getParam('companyName');
        $data['company'] = $company;
        $this->render('salaryAccountDetail',$data);
    }
    public function actionGetDetailListAjax () {
        $companyId = $this->request->getParam('companyId');
        $condition_arr['condition'] = "company_id = $companyId";
        $condition_arr['order'] = "c_time";
        $result = CurrentAccount::model()->findAll($condition_arr);
        $data = array();
        foreach ($result as $row) {
            $res = $row->getAttributes();

            $res['into_val'] = 0.00;
            $res['shifa_val'] = 0.00;
            $res['canbao_val'] = 0.00;
            $res['laowu_val'] = 0.00;
            $res['shebao_val'] = 0.00;
            $res['geshui_val'] = 0.00;

            if ($res['deal_type'] == 0) {
                $res['into_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 1) {
                $res['shifa_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 2) {
                $res['canbao_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 3) {
                $res['laowu_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 4) {
                $res['shebao_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 5) {
                $res['geshui_val'] = $res['pay_val'];
            } elseif ($res['deal_type'] == 6) {
                $res['gongjijin_val'] = $res['pay_val'];
            }
            $data[] = $res;
        }
        $response['status'] = 100000;
        $response['content'] = $data;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    private function addCurrentAccount ($model) {
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['deal_out_val'];
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['deal_out_val']);
        $currentAccountModel->memo = $model['text'];
        $currentAccountModel->op_date = date("Y-m-d H:i:s",time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s",time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'],$accountVal),true);

    }
    private function addShifaAccountItem ($model) {
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['shifa'];
        $currentAccountModel->deal_type = 1;//实发
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['shifa']);
        $currentAccountModel->memo = $model['text']."实发进卡支出";
        $currentAccountModel->op_date = date("Y-m-d H:i:s",time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s",time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'],$accountVal),true);
        return $accountVal;
    }
    private function addCanbaojinAccountItem ($model) {
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['canbaojin'];
        $currentAccountModel->deal_type = 2;//残保金
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['canbaojin']);
        $currentAccountModel->memo = $model['text']."残保金支出";
        $currentAccountModel->op_date = date("Y-m-d H:i:s",time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s",time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'],$accountVal),true);
        return $accountVal;
    }
    private function addLaofuweiAccountItem ($model) {
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['laowufei'];
        $currentAccountModel->deal_type = 3;//劳务费
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['laowufei']);
        $currentAccountModel->memo = $model['text']."劳务费支出";
        $currentAccountModel->op_date = date("Y-m-d H:i:s",time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s",time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s",time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'],$accountVal),true);
        return $accountVal;
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
            $salaryList = ErSalary::model()->with('employ')->findAll($conditionArr);
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

            $salarySum = ErTotal::model()->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
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
            $salaryList = NianSalary::model()->with('employ')->findAll($conditionArr);
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
                    $array = json_decode($val->del_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$key][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    } else {
                        $sal_mov_head = array_merge($sal_mov_head,$heads);
                    }
                    $array = json_decode($val->free_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$key][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    } else {
                        $sal_mov_head = array_merge($sal_mov_head,$heads);
                    }
                }
                $data['content'][$key][] = $val->nianzhongjiang;//
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

            $salarySum = NianTotal::model()->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
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
    public function actionSalaryListPage () {
        $data = array();
        $data['active'] = 'first';
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');
        $data['sort'] = $sort = $this->request->getParam('sort');
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';

        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        //$page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        //$page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            //'limit' => $page_size,
            //'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')'.' and salary_status > 0' ;
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName=:name ";
            $condition_arr['params'][':name'] = $this->request->getParam('name');
        }
        if (!empty($data['start_date'])||!empty($data['end_date'])) {
            if (!empty($data['start_date'])) {

                $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
                $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
            }
            if (!empty($data['end_date'])) {
                $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
                $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
            }
        }
        elseif ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('end_date') . '-01';
        } else {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $data['searchDate'] = $condition_arr['params'][':date'] = date("Y-m",time()) . '-01';
        }
        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = $this->salary_time_model-> count($condition_arr);
        /*$pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();*/
        $list = $this->salary_time_model->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = Total::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_per_shifaheji;
            $row['pay_zhongqi'] = $totalModel->sum_paysum_zhongqi;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;
        //$data['page'] = $pages;

        $data['salary_status'] = FConfig::item('config.grant_status');

        $searchCompanyData = $this->searchCompany();
        $data['companyArr'] = $searchCompanyData['data'];
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();
        $this->render('salaryListPage',$data);
    }
    public function actionSalarySecondSearchPage () {
        $data = array();
        $data['active'] = 'second';
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');
        $data['sort'] = $sort = $this->request->getParam('sort');
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        //$page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        //$page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array();
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        $condition_arr['condition'] .= "  and salary_status > 0";
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName=:name ";
            $condition_arr['params'][':name'] = $this->request->getParam('name');
        }
        if (!empty($data['start_date'])||!empty($data['end_date'])) {
            if (!empty($data['start_date'])) {

                $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
                $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
            }
            if (!empty($data['end_date'])) {
                $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
                $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
            }
        }
        elseif ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        } else {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $data['searchDate'] = $condition_arr['params'][':date'] = date("Y-m",time()) . '-01';
        }
        $condition_arr['condition'] .= " AND salaryType = 5 ";

        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = SalarytimeOther::model()-> count($condition_arr);

        $list = SalarytimeOther::model()->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = ErTotal::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_jinka;
            $row['pay_zhongqi'] = $totalModel->sum_jiaozhongqi;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;

        $data['salaryTypeStr'] = "er";
        $data['salary_status'] = FConfig::item('config.grant_status');

        $searchCompanyData = $this->searchCompany();
        $data['companyArr'] = $searchCompanyData['data'];
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();

        $this->render('salaryErSearch',$data);
    }
    /**
     * 年终奖查询
     */
    public function actionSalaryNianSearchPage () {
        $data = array();
        $data['active'] = 'nian';
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');
        $data['sort'] = $sort = $this->request->getParam('sort');
        $data['start_date'] = $this->request->getParam('start_date') ? $this->request->getParam('start_date') : '';
        $data['end_date'] = $this->request->getParam('end_date') ? $this->request->getParam('end_date') : '';
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        $condition_arr = array();
        $condition_arr['condition'] = 'companyId in ('.$companyStr.')';
        $condition_arr['condition'] .= "  and salary_status > 0";
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName=:name ";
            $condition_arr['params'][':name'] = $this->request->getParam('name');
        }
        if (!empty($data['start_date'])||!empty($data['end_date'])) {
            if (!empty($data['start_date'])) {

                $condition_arr['condition'] .= " AND salaryTime >= :start_date ";
                $condition_arr['params'][':start_date'] = $this->request->getParam('start_date') . '-01';
            }
            if (!empty($data['end_date'])) {
                $condition_arr['condition'] .= " AND salaryTime <= :end_date ";
                $condition_arr['params'][':end_date'] = $this->request->getParam('end_date') . '-01';
            }
        }
        elseif ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND salaryTime = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date') . '-01';
        }
        $condition_arr['condition'] .= " AND salaryType = 6 ";

        if ($sort == 1) {
            $condition_arr['order'] = " salary_status ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_status DESC";
        }
        //分页
        $data['count'] = SalarytimeOther::model()-> count($condition_arr);
        $list = SalarytimeOther::model()->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = NianTotal::model()->find("salaryTime_id = {$row['id']}");
            $row['shifa'] = $totalModel->sum_shifajika;
            $row['pay_zhongqi'] = $totalModel->sum_jiaozhongqi;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;

        $data['salaryTypeStr'] = "nian";
        $data['salary_status'] = FConfig::item('config.grant_status');

        $searchCompanyData = $this->searchCompany();
        $data['companyArr'] = $searchCompanyData['data'];
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();

        $this->render('salaryErSearch',$data);
    }
    public function actionSalaryListExport() {
        $ids = $this->request->getParam('salaryIdList');
        $type = $this->request->getParam('type');
        $idArr = explode(",",$ids);
        $data = array();
        if ($type == 'first') {
            $data = $this->getFirstSalaryList($idArr);

        }elseif ($type == 'second') {
            $data = $this->getErSalaryList($idArr);
        }elseif ($type == 'nian') {
            $data = $this->getNianSalaryList($idArr);
        }
        $this->render('salaryViewPage',$data);
    }
    private function getFirstSalaryList ($ids,$bDetail = 0) {
        $i = 0;
        $salarySum = array();
        foreach ($ids as $id) {
            $salaryTime = SalaryTime::model()->findByPk($id);
            $doText = '已做未发';
            if ($salaryTime->salary_status == 2) {
                $doText = "已做已发";
            }
            $conditionArr = array(
                'select' => 't.employid,t.sal_add_json,t.per_yingfaheji,t.per_shiye,t.per_yiliao,t.per_yanglao,t.per_gongjijin,t.per_daikoushui,t.per_koukuangheji,t.per_shifaheji,t.com_shiye,t.com_yiliao,t.com_yanglao,t.com_gongshang,t.com_shengyu,t.com_gongjijin,t.com_heji,t.laowufei,t.canbaojin,t.danganfei,t.paysum_zhongqi',
                'condition' => 't.salaryTimeId=:id',
                'params' => array(
                    ':id' => $id
                ),
            );
            $salaryList = $this->salary_model->with('employ')->findAll($conditionArr);
            $sal_mov_head = array();

            foreach ($salaryList as $key => $val) {
                $data['content'][$i][] = $i+1;
                $data['content'][$i][] = $doText;
                $data['content'][$i][] = $val->employ->e_company;
                $data['content'][$i][] = $val->employ->e_name;
                $data['content'][$i][] = $val->employid;
                if ($bDetail) {
                    $array = json_decode($val->sal_add_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$i][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    }
                }
                $salarySum['sum_per_yingfaheji'] += $data['content'][$i][] = $val->per_yingfaheji;
                $salarySum['sum_per_shiye'] += $data['content'][$i][] = $val->per_shiye;
                $salarySum['sum_per_yiliao'] += $data['content'][$i][] = $val->per_yiliao;
                $salarySum['sum_per_yanglao'] += $data['content'][$i][] = $val->per_yanglao;
                $salarySum['sum_per_gongjijin'] += $data['content'][$i][] = $val->per_gongjijin;
                $salarySum['sum_per_daikoushui'] += $data['content'][$i][] = $val->per_daikoushui;
                $salarySum['sum_per_koukuangheji'] += $data['content'][$i][] = $val->per_koukuangheji;
                $salarySum['sum_per_shifaheji'] += $data['content'][$i][] = $val->per_shifaheji;
                $salarySum['sum_com_shiye'] += $data['content'][$i][] = $val->com_shiye;
                $salarySum['sum_com_yiliao'] += $data['content'][$i][] = $val->com_yiliao;
                $salarySum['sum_com_yanglao'] += $data['content'][$i][] = $val->com_yanglao;
                $salarySum['sum_com_gongshang'] += $data['content'][$i][] = $val->com_gongshang;
                $salarySum['sum_com_shengyu'] += $data['content'][$i][] = $val->com_shengyu;
                $salarySum['sum_com_gongjijin'] += $data['content'][$i][] = $val->com_gongjijin;
                $salarySum['sum_com_heji'] += $data['content'][$i][] = $val->com_heji;
                $salarySum['sum_laowufei'] += $data['content'][$i][] = $val->laowufei;
                $salarySum['sum_canbaojin'] += $data['content'][$i][] = $val->canbaojin;
                $salarySum['sum_danganfei'] += $data['content'][$i][] = $val->danganfei;
                $salarySum['sum_paysum_zhongqi'] += $data['content'][$i][] = $val->paysum_zhongqi;
                $i++;
            }


            //$salarySum = $this->salary_total_model->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));

        }
        if ($bDetail) {
            $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
        } else {
            $data['header'] = array('序号','已发/未发','公司名称','姓名','身份证号','个人应发合计','个人失业','个人医疗','个人养老','个人公积金','个人代扣税','个人扣款合计','实发合计','单位失业','单位医疗','单位养老','单位工伤','单位生育','单位公积金','单位合计','劳务费','残保金','档案费','缴中企基业合计');
        }
        $sum = $this->getSumSalaryArray($salarySum,$data['header'],$data['content']);
        $data['content'] = $sum;
        //print_r($data['content']);exit;
        return $data;
    }
    private function getNianSalaryList ($ids,$bDetail = 0) {
        $i = 0;
        $salarySum = array();
        foreach ($ids as $id) {
            $salaryTime = SalarytimeOther::model()->findByPk($id);
            $doText = '已做未发';
            if ($salaryTime->salary_status == 2) {
                $doText = "已做已发";
            }
            $conditionArr = array(
                'condition' => 't.salaryTimeId=:id',
                'params' => array(
                    ':id' => $id
                ),
            );
            $salaryList = NianSalary::model()->with('employ')->findAll($conditionArr);
            $sal_mov_head = array();
            foreach ($salaryList as $key => $val) {
                $data['content'][$i][] = $i+1;
                $data['content'][$i][] = $doText;
                $data['content'][$i][] = $val->employ->e_company;
                $data['content'][$i][] = $val->employ->e_name;
                $data['content'][$i][] = $val->employid;
                if ($bDetail) {
                    $array = json_decode($val->add_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$i][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    }
                }

                $salarySum['sum_nianzhongjiang'] += $data['content'][$i][] = $val->nianzhongjiang;//
                $salarySum['sum_daikoushui'] += $data['content'][$i][] = $val->nian_daikoushui;
                $salarySum['sum_yingfaheji'] += $data['content'][$i][] = $val->yingfaheji;
                $salarySum['sum_shifajika'] += $data['content'][$i][] = $val->shifajinka;
                $salarySum['sum_jiaozhongqi'] += $data['content'][$i][] = $val->jiaozhongqi;
                $i++;
            }
        }
        if ($bDetail) {
            $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
        } else {
            $data['header'] = array('序号','已发/未发','公司名称','姓名','身份证号','年终奖','代扣税','应发合计','实发合计','缴中企基业');
        }

        //$salarySum = NianTotal::model()->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
        $sum = $this->getSumNianSalaryArray($salarySum,$data['header'],$data['content']);
        $data['content'] = $sum;
        return $data;
    }
    private function getErSalaryList ($ids,$bDetail = 0) {
        $i = 0;
        $salarySum = array();

        foreach ($ids as $id) {
            $salaryTime = SalarytimeOther::model()->findByPk($id);
            $doText = '已做未发';
            if ($salaryTime->salary_status == 2) {
                $doText = "已做已发";
            }
            $conditionArr = array(
                'condition' => 't.salaryTimeId=:id',
                'params' => array(
                    ':id' => $id
                ),
            );
            $salaryList = ErSalary::model()->with('employ')->findAll($conditionArr);
            $sal_mov_head = array();
            foreach ($salaryList as $key => $val) {
                $data['content'][$i][] = $i+1;
                $data['content'][$i][] = $doText;
                $data['content'][$i][] = $val->employ->e_company;
                $data['content'][$i][] = $val->employ->e_name;
                $data['content'][$i][] = $val->employid;
                if ($bDetail) {
                    $array = json_decode($val->add_json);
                    $heads =array();
                    foreach ($array as $mov) {
                        $heads[] = urldecode($mov->key);
                        $data['content'][$i][] = $mov->value;
                    }

                    if (empty($sal_mov_head)) {
                        $sal_mov_head = $heads;
                    }
                }
                $salarySum['sum_dangyueyingfa'] += $data['content'][$i][] = $val->dangyueyingfa;
                $salarySum['sum_ercigongziheji'] += $data['content'][$i][] = $val->ercigongziheji;
                $salarySum['sum_yingfaheji'] += $data['content'][$i][] = $val->yingfaheji;
                $salarySum['sum_yingkoushui'] += $data['content'][$i][] = $val->yingkoushui;
                $salarySum['sum_yikoushui'] += $data['content'][$i][] = $val->yikoushui;
                $salarySum['sum_bukoushui'] += $data['content'][$i][] = $val->bukoushui;
                $salarySum['sum_jinka'] += $data['content'][$i][] = $val->jinka;
                $salarySum['sum_jiaozhongqi'] += $data['content'][$i][] = $val->jiaozhongqi;
                $i++;
            }

        }
        if ($bDetail) {
            $data['header'] = array_merge(SalaryConst::$salary_base_name_list,$sal_mov_head,
                SalaryConst::$salary_head_name_list,SalaryConst::$zhongqi_fee_head_name_list);
        } else {
            $data['header'] = array('序号','已发/未发','公司名称','姓名','身份证号','当月应发合计','二次工资合计','应发合计','应扣税','已扣税','补扣税','进卡','缴中企基业');//'失业','医疗','养老','公积金',
        }

        //$salarySum = ErTotal::model()->find(array('condition' =>"salaryTime_id = :salaryTime_id","params" =>array(":salaryTime_id"=>$data['id'])));
        $sum = $this->getSumErSalaryArray($salarySum,$data['header'],$data['content']);
        $data['content'] = $sum;
        return $data;
    }
    public function actionExcelReader () {
        print_r($_FILES);exit;
        $_ReadExcel = new PHPExcel_Reader_Excel2007 ();
        if (!$_ReadExcel->canRead($path))
            $_ReadExcel = new PHPExcel_Reader_Excel5 ();
        $_phpExcel = $_ReadExcel->load($path);
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
                    $_newExcel ['moban'] [$temp] [] = $val;
                }
                $temp++;
            }
        }
    }
    public function actionExcelTest(){
        /*Yii::$enableIncludePath = false;
        Yii::import('application.extensions.PHPExcel.PHPExcel', 1);*/
        echo Yii::$enableIncludePath;exit;
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'xiaoqiang-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');

    }
    public function actionGetSocialList (){
        $data['searchDate'] = $currentYear = $this->request->getParam('date');
        if (empty($currentYear)) {
            $data['searchDate'] = $currentYear = date('Y');
        }
        $dateList = $this->getCurrentMonthOfYear($currentYear);
        //$companyStr = $this->getAdminCompanyStr();
        $viewList = array();

        foreach ($dateList as $date) {
            $obj = array();
            $obj['date'] =  $date;
            $condition = array();
            $condition['select'] = "id";
            $condition['condition'] = "salaryTime = :salaryTime and salary_status < 3";//and companyId in ('.$companyStr.')
            $condition['params'][':salaryTime'] = $date."-01";
            $ids = SalaryTime::model()->findAll($condition);
            $list = array();

            foreach ($ids as $id){

                $list[] = $id->id;
            }
            $idsSql = implode(",",$list);
            if (empty($idsSql)) continue;
            $sql = "select sum(per_shiye+per_yanglao+com_shiye+com_yanglao+com_gongshang+com_shengyu) as fourSum , sum(com_yiliao+per_yiliao) as yiliao,sum(com_gongjijin+per_gongjijin) as gongjijin
             from oa_salary where salaryTimeId in (".$idsSql.")
            ";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->queryAll();
            $obj['fourSum'] =  $result[0]['fourSum'];
            $obj['yiliao'] =  $result[0]['yiliao'];
            $obj['gongjijin'] =  $result[0]['gongjijin'];
            $viewList[] = $obj;
        }

        $data['viewList'] = $viewList;
        $this->render('socialSumList',$data);
    }
    public function actionSocialViewPage () {
        $this->layout = 'main_no_menu';
        $salaryMonth = $this->request->getParam("salMonth");
        $condition = array();
        $condition['select'] = "id";
        $condition['condition'] = "salaryTime = :salaryTime  and salary_status < 3";//and companyId in ('.$companyStr.')
        $condition['params'][':salaryTime'] = $salaryMonth."-01";
        $ids = SalaryTime::model()->findAll($condition);
        $viewList = array();
        $i = 1;
        foreach ($ids as $id){
            $salary = array();
            $salCondition = array();
            $salCondition['condition'] = "salaryTimeId = :salaryTimeId";
            $salCondition['params'][':salaryTimeId'] = $id->id;
            $res = Salary::model()->findAll($salCondition);
            foreach ($res as $sal) {
                $salary['num'] = $i;$i++;
                $salary['company'] = $sal->employ->e_company;
                $salary['name'] = $sal->employ->e_name;
                $salary['e_no'] = $sal->employid;
                $salary['per_shiye'] = $sal->per_shiye;
                $salary['per_yiliao'] = $sal->per_yiliao;
                $salary['per_yanglao'] = $sal->per_yanglao;
                $salary['per_gongjijin'] = $sal->per_gongjijin;
                $salary['com_shiye'] = $sal->com_shiye;
                $salary['com_yiliao'] = $sal->com_yiliao;
                $salary['com_yanglao'] = $sal->com_yanglao;
                $salary['com_gongshang'] = $sal->com_gongshang;
                $salary['com_shengyu'] = $sal->com_shengyu;
                $salary['com_gongjijin'] = $sal->com_gongjijin;
                $viewList[] = $salary;
            }

        }
        $data['header'] = array("序号","部门","姓名","身份证号","个人失业","个人医疗","个人养老","个人公积金","单位失业","单位医疗","单位养老","单位工伤","单位生育","单位公积金");
        $data['viewList'] = $viewList;
        $this->render('socialViewPage',$data);
    }
    private function getCurrentMonthOfYear($year){
        $nowMonth = strtotime(date("Y-m-01",time()));
        $returnList = array();
        for ($i = 1; $i <= 12; $i++) {
            $times = strtotime($year."-$i-01");

            if ($times <= $nowMonth) {
                $returnList[] = date("Y-m",$times);
            }
        }
        return $returnList;
    }

    public function actionGetNianSalaryList () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['searchDate'] = $this->request->getParam('date');
        //分页参数
        $companyStr = $this->getAdminCompanyStr();
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        $condition_arr['condition'] = ' salaryType = 6' ;
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND year = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date');
        }
        $condition_arr['order'] = " id DESC ";

        //分页
        $data['count'] = SalarytimeOther::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $list = SalarytimeOther::model()->findAll($condition_arr);
        $returnList= array();
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = NianTotal::model()->find("salaryTime_id = {$row['id']}");
            $row['sum_nianzhongjiang'] = $totalModel->sum_nianzhongjiang;
            $row['sum_daikoushui'] = $totalModel->sum_daikoushui;
            $row['sum_yingfaheji'] = $totalModel->sum_yingfaheji;
            $row['sum_shifajika'] = $totalModel->sum_shifajika;
            $row['sum_jiaozhongqi'] = $totalModel->sum_jiaozhongqi;
            $returnList [] = $row;
        }
        $data['list'] = $returnList;
        $data['page'] = $pages;

        $this->render('nianSalaryList',$data);
    }

    public function actionNianSalaryAccountDetail () {}

    public function actionCompanyAccountUpdate () {
        $data = array();
        $this->render('companyAccountUpdate',$data);
    }

    public function actionUpdateAccountValAjax () {
        //$data = array();
        $dataList = $this->request->getParam('data');
        //print_r($dataList);exit;
        $dataList = json_decode($dataList,true);
        $error_list = array();
        foreach ($dataList as $k => $data) {
            if (empty($data['com_name'])) {
                continue;
            }
            if (!is_numeric($data['account_val'])) {
                $error_list[$k] = '余额非数字类型';continue;
            }
            $company = Customer::model()->findByAttributes(array(
                "customer_name" => $data['com_name'],
            ));

            if (empty($company)) {
                $error_list[$k] = '公司名称未查询到';continue;
            }
            $res = Customer::model()->updateByPk($company->id,array("account_val" => $data['account_val']));
            if ($res) {
                $error_list[$k] = '修改成功';
            } else {
                $error_list[$k] = '修改值和原来一致';
            }
        }

        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['errorList'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
}