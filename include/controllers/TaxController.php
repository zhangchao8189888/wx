<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 17/5/30
 * Time: 下午2:08
 */
class TaxController extends FController{
    public $defaultAction = 'taxAdmin';
    public function __construct($id, $module = null) {

        parent::__construct($id, $module);

    }

    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }
    public function actionTaxAdmin () {
        $data = array();
        $data['searchName'] = $this->request->getParam('name');
        $data['sort'] = $sort = $this->request->getParam('sort');
        //分页参数

        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if ($this->request->getParam('name')) {
            $condition_arr['condition'] .= "customer_name like :name ";//
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('name').'%';
        }
        if ($sort == 1) {
            $condition_arr['order'] = " salary_send ";
        } elseif ($sort == 2) {
            $condition_arr['order'] = " salary_send DESC";
        }
        //分页
        $data['count'] = Customer::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $returnList = Customer::model()->findAll($condition_arr);

        $data['list'] = $returnList;
        $data['page'] = $pages;
        $this->render('taxAdmin', $data);
    }
    public function actionModifySalSend () {
        $send = $this->request->getParam("sal_send");
        $id = $this->request->getParam("id");
        $cusObj = new Customer();
        $res = $cusObj->updateByPk($id,array("salary_send"=>$send));
        if (!$res) {
            $response['status'] = 100001;
            $response['content'] = "设置失败";

        }
        $response['status'] = 100000;
        $response['content']= '设置成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
    public function actionTaxExport(){
        $send = $this->request->getParam("sal_send");
        $data =array();
        $this->render('taxList', $data);
    }

    public function actionNianTaxExport(){
        $send = $this->request->getParam("sal_send");
        $data =array();
        $this->render('nianTaxList', $data);
    }

    public function actionGetNianTaxList () {

        $data = array();
        $data['searchName'] = $this->request->getParam('com_name');
        $data['searchDate'] = $this->request->getParam('date');


        $condition_arr['condition'] = ' salaryType = 6' ;
        if ($this->request->getParam('com_name')) {
            $condition_arr['condition'] .= " AND companyName like :name ";
            $condition_arr['params'][':name'] = '%'.$this->request->getParam('com_name').'%';
        }
        if ($this->request->getParam('date')) {
            $condition_arr['condition'] .= " AND year = :date ";
            $condition_arr['params'][':date'] = $this->request->getParam('date');
        }
        $condition_arr['order'] = " id DESC ";

        //print_r($condition_arr);
        $list = SalarytimeOther::model()->findAll($condition_arr);

        $returnList= array();
        $i =1;
        foreach ($list as $val) {
            $row = $val->getAttributes();
            $totalModel = NianSalary::model()->find("salaryTimeId = {$row['id']}");
            $con = array();
            $con['condition'] = "e_num = :e_num";
            $con['params'] = array(":e_num"=>$totalModel->employid);
            $ss = new Employ();
            //print_r($con);
            $employ = $ss->find($con);
            $row['index'] = $i;$i++;
            $row['e_name'] = $employ->e_name;
            $row['e_num'] = $totalModel->employid;
            $row['nianzhongjiang'] = $totalModel->nianzhongjiang;
            $row['nian_daikoushui'] = $totalModel->nian_daikoushui;
            $row['yingfaheji'] = $totalModel->yingfaheji;
            $row['shifajinka'] = $totalModel->shifajinka;
            $row['jiaozhongqi'] = $totalModel->jiaozhongqi;
            $returnList [] = $row;
        }

        $response['status'] = 100000;
        $response['content']= $returnList;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionGetTaxList () {
        $date = $this->request->getParam("date")."-01";
        $com_name = $this->request->getParam("com_name");
        if (empty($date)) {
            $date = date("Y-m-01",time());
        }
        $lastMonth = date("Y-m-01",strtotime("$date -1 month"));
        $last2Month = date("Y-m-01",strtotime("$date -2 month"));
        $condition_arr = array();
        if (!empty($com_name)) {
            $condition_arr['condition'] .= "customer_name like :name ";//
            $condition_arr['params'][':name'] = '%'.$com_name.'%';
        }
        $comRes = Customer::model()->findAll($condition_arr);
        $returnList = array();
        $i = 1;
        foreach ($comRes as $com) {

            if (Customer::SALARY_SEND_TYPE_CURRENT_SEND == $com->salary_send) {
                $searchDate = $lastMonth;
            } elseif(Customer::SALARY_SEND_TYPE_NEXT_SEND == $com->salary_send) {
                $searchDate = $last2Month;
            } else {
                //$searchDate = $last2Month;
                continue;
            }
            $sql = "SELECT oa_salary_time.*,oa_salary.* from oa_salary inner join oa_salary_time on oa_salary.salaryTimeId = oa_salary_time.id  
where salaryTime = '{$searchDate}' and salary_status < 3 and oa_salary_time.companyId = {$com->id};";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->queryAll();
            foreach ($result as $row) {
                $obj = array();
                $obj[0] = $i;$i++;
                $obj[2] = $row['companyName'];

                $obj[4] = $row['employid'];
                $obj[1] = $row['salaryTime'];
                $obj[5] = $row['per_daikoushui'];
                $con = array();
                $con['condition'] = "e_num = :e_num";
                $con['params'] = array(":e_num"=>$row['employid']."");
                $ss = new Employ();
                $employ = $ss->find($con);
                $obj[3] = $employ->e_name;
                $erType = SalaryConst::SALARY_ER_TYPE;
                $res_er = null;
                $res_nian = null;
                $erSql = "select sum(oa_er_salary.bukoushui) as bukoushui_sum  from oa_salarytime_other inner join oa_er_salary on oa_salarytime_other.id = oa_er_salary.salaryTimeId where
 oa_er_salary.employid = '{$row['employid']}'  and oa_salarytime_other.salaryTime='{$searchDate}'  and salary_status < 3;";
                $command = Yii::app()->db->createCommand($erSql);
                $res_er = $command->queryAll();

                if (!empty($res_er)) {
                    $obj[6] = (float)$res_er[0]['bukoushui_sum'];
                } else {
                    $obj[6] = (float)0.00;
                }

                $nianSql = "select * from oa_salarytime_other inner join oa_nian_salary on oa_salarytime_other.id = oa_nian_salary.salaryTimeId where 
oa_nian_salary.employid = '{$row['employid']}' and oa_salarytime_other.salaryTime='{$searchDate}'  and salary_status < 3 ;";
                $command = Yii::app()->db->createCommand($nianSql);
                $res_nian = $command->queryAll();
                if (!empty($res_nian)) {
                    $obj[7] = (float)$res_nian[0]['nian_daikoushui'];
                } else {
                    $obj[7] = (float)0.00;
                }

                $obj[8] = bcadd(bcadd($obj['geshui_2'],$obj['geshui_1'],2),$obj['geshui_nian'],2);
                $returnList[] = $obj;
            }
        }
        $response['status'] = 100000;
        $response['content']= $returnList;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionGetFirstComSumTax(){
        $date = $this->request->getParam("date")."-01";
        if (empty($date)) {
            $date = date("Y-m-01",time());
        }
        $lastMonth = date("Y-m-01",strtotime("$date -1 month"));
        $last2Month = date("Y-m-01",strtotime("$date -2 month"));

        $comRes = Customer::model()->findAll();
        $returnList = array();
        $i = 1;
        foreach ($comRes as $com) {
            $obj = array();
            if (Customer::SALARY_SEND_TYPE_CURRENT_SEND == $com->salary_send) {
                $searchDate = $lastMonth;
            } elseif(Customer::SALARY_SEND_TYPE_NEXT_SEND == $com->salary_send) {
                $searchDate = $last2Month;
            } else {
                //$searchDate = $last2Month;
                continue;
            }
            $sql = "select sum(os.per_daikoushui) as sum_val from oa_salary_time ost inner join oa_salary os on ost.id = os.salaryTimeId
            where ost.salaryTime = '{$searchDate}'  and salary_status < 3 and ost.companyId = {$com->id};";
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryAll();//print_r($res);
            if($res[0]['sum_val'] == null)  continue;
            $adminId = AdminCompany::model()->find(array(
                "condition" => "companyId = {$com->id}",
                "select" => "adminId",
            ));
            $adminPO = OAAdmin::model()->findByPk($adminId->adminId);
            $obj['admin_name'] = $adminPO['name'];
            $obj['com_name'] = $com->customer_name;
            $obj['send_date'] = $searchDate;
            $obj['val'] = $res[0]['sum_val'];
            $returnList[] = $obj;
        }
        $head = array(0=>array("客服","公司名称","发放月份","代扣税"));
        $this->excelExport($head,$returnList);
    }
    public function actionGetErComSumTax(){
        $date = $this->request->getParam("date")."-01";
        if (empty($date)) {
            $date = date("Y-m-01",time());
        }
        $lastMonth = date("Y-m-01",strtotime("$date -1 month"));
        $last2Month = date("Y-m-01",strtotime("$date -2 month"));

        $comRes = Customer::model()->findAll();
        $returnList = array();
        $i = 1;
        foreach ($comRes as $com) {
            $obj = array();
            if (Customer::SALARY_SEND_TYPE_CURRENT_SEND == $com->salary_send) {
                $searchDate = $lastMonth;
            } elseif(Customer::SALARY_SEND_TYPE_NEXT_SEND == $com->salary_send) {
                $searchDate = $last2Month;
            } else {
                //$searchDate = $last2Month;
                continue;
            }
            $erType = FConfig::item('config.salary_type.SALARY_ER');
            $sql = "select sum(os.bukoushui) as sum_val from oa_salarytime_other ost inner join oa_er_salary os on ost.id = os.salaryTimeId
            where ost.salaryTime = '{$searchDate}'  and salary_status < 3 and ost.companyId = {$com->id} and salaryType = {$erType};";
            //error_log($sql."\n",3,"/tmp/error.log");
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryAll();//print_r($res);
            if($res[0]['sum_val'] == null)  continue;
            $adminId = AdminCompany::model()->find(array(
                "condition" => "companyId = {$com->id}",
                "select" => "adminId",
            ));
            $adminPO = OAAdmin::model()->findByPk($adminId->adminId);
            $obj['admin_name'] = $adminPO['name'];
            $obj['com_name'] = $com->customer_name;
            $obj['send_date'] = $searchDate;
            $obj['val'] = $res[0]['sum_val'];
            $returnList[] = $obj;
        }
        $head = array(0=>array("客服","公司名称","发放月份","二次补扣税"));
        $this->excelExport($head,$returnList);
    }

}