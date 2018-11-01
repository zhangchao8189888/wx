<?php
/**
 * 派遣管理
 *
 */
class SalaryController extends FController
{
    private $employ_model;
    private $customer_model;
    private $salary_time_model;
    private $salary_model;
    private $salary_total_model;
    private $er_salary_model;
    private $nian_salary_model;
    private $salarytime_other_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->employ_model = new Employ();
        $this->customer_model = new Customer();
        $this->salary_time_model = new SalaryTime();
        $this->salary_model = new Salary();
        $this->salary_total_model = new Total();
        $this->er_salary_model = new ErSalary();
        $this->nian_salary_model = new NianSalary();
        $this->salarytime_other_model = new SalarytimeOther();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }
    public function actionSalaryMake() {

        $this->layout = 'main_no_menu';
        $data = $this->actionSearchCompany();
        $this->customer_model = new Customer();
        $data['company']=$this->customer_model->findAll(array(
            'select'=>'id,customer_name'
        ));
        $this->render("salaryMake",$data);
    }
    public function actionSumSalary() {
        $dataExcel = json_decode($_REQUEST['data'],true);
        $shenfenzheng = $_POST ['shenfenzheng'];
        $addArray = $_POST ['add'];
        $delArray = $_POST ['del'];
        if ($_POST ['freeTex']) {
            $freeTex = $_POST ['freeTex'];
        }
        $splitStr = ",";

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
            $addHeadArr[] = trim($dynamic_head [$row]);
        }
        $delHeadArr = array();
        if (! empty ( $delArray )) {
            foreach ( $delArray as $row ) {
                $delHeadArr[] = trim($head [$row]);
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
        $userType = FConfig::item("config.employ_type_val");//print_r($userType) ;exit;
        for($i = 1; $i < count ( $dataExcel ); $i ++) {
            if($dataExcel [$i] [$shenfenzheng] =='null') {
                continue;
            }
            $employ = $this->employ_model->find('e_num = :e_num',array(":e_num"=>trim($dataExcel[$i][$shenfenzheng])));
           /* print_r($employ);
            echo $dataExcel[$i][$shenfenzheng]."\n";exit;*/
           //echo $employ ['e_type_name']."\n";
           //echo $userType[$employ ['e_type_name']]."\n";
            if ($employ) {
                //基本信息
                $jisuan_var [$i] ['company_name'] = $employ ['e_company'];
                $jisuan_var [$i] ['e_name'] = $employ ['e_name'];
                $jisuan_var [$i] ['e_num'] = $employ ['e_num'];
                $jisuan_var [$i] ['e_teshu_state'] = $employ ['e_teshu_state'];
                $jisuan_var [$i] ['bank_num'] = $employ ['bank_num'];

                $jisuan_var [$i] ['yinhangkahao'] = $employ ['bank_num'];
                $jisuan_var [$i] ['shenfenleibie'] = $employ ['e_type_name'];
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
                $addVal = trim($dataExcel [$i] [$row]);
                $headVal = urlencode(trim($dynamic_head [$row]));
                if (preg_match('/^(-?\d+)(\.\d+)?$/i', $addVal)) {

                    $move [$i]['add'][$f] ['key'] = $headVal;
                    $move [$i]['add'][$f] ['value'] = $addVal;
                    $f++;
                    $addValue += $addVal;
                } else {
                    $dataExcel [$i] [$row] = $addVal.':无数值';
                    $error [$errorRow] ["error"] = "第$i 行 $headVal 列所加项非数字类型";
                    $errorRow++;
                    continue;
                }
            }

            $f= 0;
            if (! empty ( $delArray )) {
                foreach ( $delArray as $row ) {

                    $delVal = trim($dataExcel [$i] [$row]);
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
        //print_r($jisuan_var);
        $sumclass = new FSumSalary();
        $sumclass->getSumSalary ( $jisuan_var );
        //print_r($jisuan_var);exit;
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
                $salary [] = trim($dataExcel [$i] [$row]);
            }

            if (! empty ( $delArray )) {
                foreach ( $delArray as $row ) {
                    $salary [] = trim($dataExcel [$i] [$row]);
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
    // FIXME 保存工资
    public function actionSaveSalary() {
        $excelMove = json_decode($_POST ['excelMove'],true);
        //print_r($excelMove);exit;
        $excelHead = $_POST ['excelHead'];
        $companySearch = $_POST ['companySearch'];
        $companyPO = Customer::model()->find("customer_name = '{$companySearch}'");
        if (empty($companyPO)) {
            $response['status'] = 100001;
            $response['content'] = " 填写的单位不存在，请重新填写！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $company_id = $companyPO->id;
        $list = AdminCompany::model()->findAll('adminId=:adminId and companyId=:companyId',
            array(':adminId'=>$this->user->id,':companyId'=>$company_id,));
        if (!$list) {
            $response['status'] = 100001;
            $response['content'] = " 该单位你没有管理，请添加管理再保存！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $comname = $_GET ['salaryDate'];
        $salaryTimeDate = $_POST ['salaryDate']."-01";
        $time   =   FHelper::AssignTabMonth ($salaryTimeDate,0);
        $shifajian = $_POST ['shifajian'];
        $salaryList = json_decode($_POST['data'],true);
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
        $transaction = Yii::app()->db->beginTransaction();
        // 查询公司信息
        $company = $this->customer_model->findByPk($company_id);
        if (! empty ( $company )) {

            $companyId = $company ['id'];
            $arr = array(
                'condition' => " companyId = :companyId and salaryTime = :salaryTime",
                'params' => array(
                    ':companyId' => $companyId,
                    ':salaryTime' => $time["first"],
                ),
            );
            // 根据日期查询公司时间
            $salaryTime = $this->salary_time_model->find($arr);
            if (! empty ( $salaryTime ['id'] )) {
                $response['status'] = 100001;
                $response['content'] = " {$company['customer_name']} 本月已做工资 ,有问题请联系财务！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }

        } else {
            //公司不存在
        }
        // 添加工资日期
        $this->salary_time_model = new SalaryTime();
        $this->salary_time_model->companyId = $companyId;
        $this->salary_time_model->companyName = $company['customer_name'];
        $this->salary_time_model->salaryTime = $salaryTimeDate;
        $this->salary_time_model->op_salaryTime = date ( "Y-m-d H:i:s",time());
        $this->salary_time_model->mark = $mark;
        $this->salary_time_model->op_id = $this->user->id;
        $this->salary_time_model->save($salaryTime);
        $lastSalaryTimeId = $this->salary_time_model->id;
        if (! $lastSalaryTimeId) {
            $transaction->rollback ();
            $response['status'] = 100001;
            $response['content'] = " 保存工资时间失败！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
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
                // 以上保存成功后，保存合计项
                $sql = $this->saveSumSalary($salary_list);//print_r($salary_list);echo $sql;exit;
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->execute();
                if (! $result) {
                    $transaction->rollback ();
                    $response['status'] = 100001;
                    $response['content'] = " 保存合计工资失败！";
                    Yii::app()->end(FHelper::json($response['content'],$response['status']));
                }
            } else {
                if (empty($salaryList [$i] [$sit_gerenyinfaheji])) {
                    continue;
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

    //计算年终奖
    public function actionSumNianSalary(){
        $dataExcel = json_decode($_REQUEST['data'],true);
        $shenfenzheng = $this->request->getParam('shenfenzheng');
        $salaryTime = $this->request->getParam('salaryTime_nian')."-01";


        $isFirst = $this->request->getParam('isMakeFirst');
        $nian = $this->request->getParam('nian');

        $head = array_merge($dataExcel[0],SalaryConst::$salary_nian_head_name_list);

        $sumshifaheji=0;$sumNianzhongjiang = 0;$sumYingfaheji = 0;$sumshifajinka = 0;$sumJiaozhongqiheji = 0;$sumNianzhongjiangdai = 0;

        $error = array ();
        $salaryList = array();
        $i = 0;
        // 根据年终奖月份和身份证号查询该员工的当月应发合计项
        $dataExcel = array_splice($dataExcel,1,-1);

        foreach ($dataExcel as $nianSalary) {
            if ($nianSalary[$shenfenzheng] == 'null') {
                continue;
            }
            $jisuan_var = array ();
            $nianSalaryResult = array ();
            $shenfenzhengSql = trim ($nianSalary[$shenfenzheng]);

            $employ = Yii::app()->db->createCommand("select oa_salary_time.*,oa_salary.*  from oa_salary_time,oa_salary where oa_salary.salaryTimeId=oa_salary_time.id  and oa_salary_time.salaryTime='$salaryTime' and oa_salary.employid='$shenfenzhengSql' ")->queryAll();

            if (!empty($employ)) {
                $employ = $employ[0];
            }
            if (! $isFirst) { // 未做一次工资
                if (! $employ) { // 员工不为空
                    $result = $this->makeDefaultFirstSalary($shenfenzhengSql,$salaryTime);
                    if ($result) {
                        $employ = $result;
                    }
                }
            }
            if ($employ) {
                $sql = " select  *  from  oa_er_salary  ,oa_salarytime_other  where oa_salarytime_other.id=oa_er_salary.salaryTimeId and  oa_salarytime_other.salaryTime='" . $salaryTime . "' and oa_er_salary.employid='" . $shenfenzhengSql."'";
                $erSal = Yii::app()->db->createCommand($sql)->queryAll();
                $jisuan_var ['yingfaheji'] = $employ ['per_yingfaheji'];
                $jisuan_var['shifaheji']=$employ['per_shifaheji'];

                if ($erSal) {
                    //print_r($erSal);exit;
                    foreach ($erSal as $erVal) {

                        $jisuan_var ['yingfaheji'] += $erVal ['ercigongziheji'];
                        $jisuan_var ['shifaheji'] += $erVal ['jinka'];
                    }
                }
                $sumNianzhongjiang += $jisuan_var ['nianzhongjiang'] = $nianSalary[$nian];

                $sumClass = new FSumSalary();
                $sumClass->sumNianSal( $jisuan_var ); // 计算年终奖


                $sumYingfaheji += $nianSalaryResult[] = sprintf ( "%01.2f", $jisuan_var ['yingfaheji'] ) + 0;
                $sumshifaheji  += $nianSalaryResult[] = sprintf ( "%01.2f", $jisuan_var ['shifaheji'] ) + 0;

                $canjiren = $this->getCanjiRen($shenfenzhengSql);//取得残疾人状态
                if($canjiren==1){
                    $sumNianzhongjiangdai += $nianSalaryResult[] =  sprintf ( "%01.2f", $jisuan_var ['niandaikoushui']/= 2) + 0;
                } else {
                    $sumNianzhongjiangdai += $nianSalaryResult[] = sprintf ( "%01.2f", $jisuan_var ['niandaikoushui'] ) + 0;
                }

                $sumshifajinka += $nianSalaryResult[] = sprintf ( "%01.2f", $jisuan_var ['shifajinka'] ) + 0;
                $sumJiaozhongqiheji += $nianSalaryResult[] = sprintf ( "%01.2f", $jisuan_var ['jiaozhongqi'] ) + 0;

                $nianSalary = array_merge($nianSalary,$nianSalaryResult);
                $salaryList[] = $nianSalary;
            } else {
                $error [$i] ["error"] = "{$salaryList['Sheet1'][$i][$shenfenzheng]}:未查询到该员工身份类别！";
                continue;
            }
        }
        // 计算合计行
        $hejiArr = array();
        $hei_count = array_search('当月应发合计', $head);

        for($j = 0; $j < $hei_count; $j ++) {
            if ($j == 0) {
                $hejiArr [$j] = "合计";
            } elseif ($j == $nian) {
                $hejiArr [$j] = $sumNianzhongjiang;
            } else {
                $hejiArr [$j] = " ";
            }
        }

        $hejiArr[] = $sumYingfaheji;
        $hejiArr[] = $sumshifaheji;
        $hejiArr[] = $sumNianzhongjiangdai;
        $hejiArr[] = $sumshifajinka;
        $hejiArr[] = $sumJiaozhongqiheji;
        $salaryList [] = $hejiArr;

        $result['data'] = $salaryList; // 数组0为字段名，去掉数组【0】
        $result['head'] = $head;
        $result['error'] = $error;
        $result['result'] = 'ok';
        $result['nianzhong_bit'] = $nian;
        echo json_encode($result);
        exit;
    }
    public function actionSalarySearchList () {
        $where=array();
        $user = $_SESSION ['admin'];
        $companyId = $this->request->getParam("company_id");
        $where['companyId'] = $companyId;
        $salTime = $_REQUEST['salaryTime'];
        $opTime = $_REQUEST['op_salaryTime'];
        if($opTime) {
            $time = FHelper::AssignTabMonth($opTime,0);
            $where['op_salaryTime']=$time["next"];
            $where['op_time']   =   $time["data"];
        }
        $where['salaryTime']=$salTime;
        if($salTime) {
            $time = FHelper::AssignTabMonth($salTime,0);
            $where['salaryTime']=$time["month"];
        }
        //$pageSize=PAGE_SIZE;
        $pageSize=10;
        $count = intval($_GET['c']);
        $page = intval($_GET['page']);
        if ($count == 0){
            $count = $pageSize;
        }
        if ($page == 0){
            $page = 1;
        }


        $startIndex = ($page-1)*$count;

        if (empty($sorts)){
            $sorts = "op_salaryTime" ;
        }
        if (empty($dir)) {
            $dir = "desc";
        }
        $sum =$this->objDao->searhSalaryTimeListCount($where);
        $result=$this->objDao->searhSalaryTimeListPage($startIndex,$pageSize,$sorts." ".$dir,$where);
        $total = $sum;
        $pages = new JPagination($total);
        $pages->setPageSize($pageSize);
        $pages->setCurrent($page);
        $pages->makePages();
        $salaryTimeList = array();
        //company_code,company_name,com_contact,contact_no,company_address,com_bank,bank_no,company_level,company_type,company_status
        while ($row = mysql_fetch_array($result)) {
            $salary = array();
            $salary['id'] = $row['id'];
            $salary['salaryTime'] = $row['salaryTime'];
            $salary['companyId'] = $row['companyId'];
            $salary['company_name'] = $row['company_name'];
            $salary['op_salaryTime'] = $row['op_salaryTime'];
            $salaryTimeList[] = $salary;
        }
        $this->objForm->setFormData("total",$total);
        $this->objForm->setFormData("page",$pages);
        $this->objForm->setFormData("companyId",$where['companyId']);
        $this->objForm->setFormData("salaryTime",$salTime);
        $this->objForm->setFormData("op_salaryTime",$opTime);
        $this->objForm->setFormData ( "salaryTimeList", $salaryTimeList );
    }

    // 二次工资
    public function actionSumErSalary () {
        $shenfenzheng = $_POST ['shenfenzheng'];
        $data = json_decode($_REQUEST['data'],true);

        $data = array_slice($data,0,-1);
        $salaryTime = $_POST ['salaryTime_er']."-01";
        $add = $_POST ['add'];
        $addArray = explode ( ",", $add );
        $count = count ($data[0]);

        $data [0] = array_merge($data[0],SalaryConst::$salary_er_head_name_list);

        $sumergongziheji = 0;
        $sumdangyuefafangheji = 0;
        $sumshijiyingfaheji = 0;
        $sumyingkoushui = 0;
        $sumyikoushui = 0;
        $sumbukoushui = 0;
        $sumjinka = 0;
        $sumjiaozhongqi = 0;
        $error = array ();
        // 根据年终奖月份和身份证号查询该员工的当月应发合计项
        for($i = 1; $i < count ( $data ); $i ++) {
            $jisuan_var = array ();
            $data [$i] [$shenfenzheng] = trim ( $data [$i] [$shenfenzheng] );
            $sql_one = "select oa_salary_time.*,oa_salary.*  from oa_salary_time,oa_salary where oa_salary.salaryTimeId=oa_salary_time.id  and oa_salary_time.salaryTime='$salaryTime' and oa_salary.employid='{$data[$i][$shenfenzheng]}' ";
            $res_one = Yii::app()->db->createCommand($sql_one)->queryAll();
            if (empty($res_one)) {
                $error [] ["error"] = "身份证号：{$data[$i][$shenfenzheng]}未查询到该员工一次工资！";
                continue;
            }
            $sql_two = "select  sum(oa_er_salary.ercigongziheji) as erSum ,sum(oa_er_salary.bukoushui) as erSumBukoushui ,oa_er_salary.employId  from oa_salarytime_other,oa_er_salary	where  oa_salarytime_other.salarytime='$salaryTime' and oa_salarytime_other.companyId={$res_one[0]['companyId']} and oa_salarytime_other.id=oa_er_salary.salarytimeId and oa_er_salary.employId='{$data[$i][$shenfenzheng]}' group by oa_er_salary.employId";
            $erSalaryTimePO = yii::app()->db->createCommand($sql_two)->queryAll();
            /*
             * $erSalaryTimePO //select * from OA_salarytime_other,OA_er_salary where OA_salarytime_other.salarytime='2012-12-01' and OA_salarytime_other.companyId=74 and OA_salarytime_other.id=OA_er_salary.salarytimeId group by OA_er_salary.employId; $erciSalaryPO=$this->objDao->searchErSalaryListBy_SalaryTimeId();
             */
            if ($res_one[0]) {
                $addValue = 0;
                $f = 0;
                foreach ( $addArray as $row ) {
                    $headVal = $data [0][$row];
                    $data [$i] [$row] = trim($data [$i] [$row] );
                    if (is_numeric ($data [$i] [$row])) {
                        $addValue += $data [$i] [$row];
                        $move [$i]['add'][$f] ['key'] = $headVal;
                        $move [$i]['add'][$f] ['value'] = $data [$i] [$row];
                        $f++;
                    } else {
                        $rowText = $row +1;
                        $error [] ["error"] = "第{$rowText}列所加项非数字类型{$data [$i] [$row]}";
                        continue;
                    }
                }
                $shenfenleibie = (count($addArray) + 1);
                if (! $erSalaryTimePO) {
                    $erSalaryTimePO[0] ['erSum'] = 0;
                }
                $jisuan_var ['ercigongziheji'] = $addValue;
                $jisuan_var ['yingfaheji'] = $res_one[0] ['per_yingfaheji'] + $erSalaryTimePO[0] ['erSum'];
                $jisuan_var ['shijiyingfaheji'] = $jisuan_var ['ercigongziheji'] + $jisuan_var ['yingfaheji'];
                $jisuan_var ['shiye'] = $res_one[0] ['per_shiye'];
                $jisuan_var ['yiliao'] = $res_one[0] ['per_yiliao'];
                $jisuan_var ['yanglao'] = $res_one[0] ['per_yanglao'];
                $jisuan_var ['gongjijin'] = $res_one[0] ['per_gongjijin'];
                $jisuan_var ['yikoushui'] = $res_one[0] ['per_daikoushui'] + $erSalaryTimePO[0]['erSumBukoushui'];
                $sumclass = new FSumSalary();
                $sumclass->sumErSal ( $jisuan_var );
                $attr = array(
                    'condition' => 'e_num = :e_num',
                    'select' => 'e_teshu_state',
                    'params' => array(
                        ':e_num' => $data [$i] [$shenfenzheng]
                    ),
                );
                $canjiren = $this->employ_model->find($attr)->e_teshu_state;

                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['ercigongziheji'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['yingfaheji'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['shijiyingfaheji'] ) + 0;
               /* $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['shiye'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['yiliao'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['yanglao'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['gongjijin'] ) + 0;*/
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['yingkoushui'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['yikoushui'] ) + 0;
                if($canjiren[0]==1){
                    $data [$i] [] = (sprintf ( "%01.2f", $jisuan_var ['bukoushui'] ) + 0)/2;
                } else {
                    $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['bukoushui'] ) + 0;
                }
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['shuangxinjinka'] ) + 0;
                $data [$i] [] = sprintf ( "%01.2f", $jisuan_var ['jiaozhongqi'] ) + 0;
            } else {
                $error [] ["error"] = "{$data[$i][$shenfenzheng]}:未查询到该员工一次工资！";
                continue;
            }
            $sumergongziheji += $data [$i] [($count + 0)];
            $sumdangyuefafangheji += $data [$i] [($count + 1)];
            $sumshijiyingfaheji += $data [$i] [($count + 2)];
            /*$sumshiye += $data [$i] [($count + 3)];
            $sumyiliao += $data [$i] [($count + 4)];
            $sumyanglao += $data [$i] [($count + 5)];
            $sumgongjijin += $data [$i] [($count + 6)];*/
            $sumyingkoushui += $data [$i] [($count + 3)];
            $sumyikoushui += $data [$i] [($count + 4)];
            $sumbukoushui += $data [$i] [($count + 5)];
            $sumjinka += $data [$i] [($count + 6)];
            $sumjiaozhongqi += $data [$i] [($count + 7)];
        }
        // 计算合计行

        $countLie = count ( $data ); // 代表一共多少行
        for($j = 0; $j < $count; $j ++) {
            if ($j == 0) {
                $data [$countLie] [$j] = "合计";
            } else {
                $data [$countLie] [$j] = " ";
            }
        }
        $data [$countLie] [($count + 0)] = $sumergongziheji;
        $data [$countLie] [($count + 1)] = $sumdangyuefafangheji;
        $data [$countLie] [($count + 2)] = $sumshijiyingfaheji;
        /*$data [$countLie] [($count + 3)] = $sumshiye;
        $data [$countLie] [($count + 4)] = $sumyiliao;
        $data [$countLie] [($count + 5)] = $sumyanglao;
        $data [$countLie] [($count + 6)] = $sumgongjijin;*/
        $data [$countLie] [($count + 3)] = $sumyingkoushui;
        $data [$countLie] [($count + 4)] = $sumyikoushui;
        $data [$countLie] [($count + 5)] = $sumbukoushui;
        $data [$countLie] [($count + 6)] = $sumjinka;
        $data [$countLie] [($count + 7)] = $sumjiaozhongqi;
        $result['result'] = 'ok';
        $result['shenfenleibie'] = $shenfenleibie;
        //print_r($data);exit;
//        $result['move'] = $move;
        $result['data'] = array_slice($data,1); // 数组0为字段名，去掉数组【0】
        $result['head'] = $data[0];
        $result['error'] = $error;
        $result['move'] = $move;
        echo json_encode($result);
        exit;
    }

    // 保存二次工资
    public function actionSaveErSalary() {
        $excelMove = $_POST ['excelMove'];
        $excelHead = $_POST ['excelHead'];
        $company_id = $_POST ['company_id'];
        $companyPO = Customer::model()->find("customer_name = '{$company_id}'");
        if (empty($companyPO)) {
            $response['status'] = 100001;
            $response['content'] = " 填写的单位不存在，请重新填写！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $company_id = $companyPO->id;
        $mark = $_POST ['mark'];

        $salaryTimeDate = $_POST ['salaryDate']."-01";
        $time   =   FHelper::AssignTabMonth ($salaryTimeDate,0);
//        $shifajian = $_POST ['shifajian'];
        $salaryList = json_decode($_POST['data'],true);
        //$salaryList = array_slice($salaryList,0,count($salaryList)-1);
        $mark = $_POST ['mark'];

        foreach ( $excelHead  as $num => $row ) {
            if (strstr( $row, "身份证" )) {
                $sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
            } elseif (strstr ( $row, "二次工资合计" )) {
                $sit_ercigongziheji = $num; // 得到二次工资合计字段的标志位
            }
        }
        // 开始事务
        $transaction = $this->customer_model->dbConnection->beginTransaction();
        // 查询公司信息
        $company = $this->customer_model->findByPk($company_id);
        if (! empty ( $company )) {

            $companyId = $company ['id'];

        } else {
            //公司不存在
            if (! empty ( $salaryTime ['id'] )) {
                $response['status'] = 100001;
                $response['content'] = " {$company['customer_name']} 公司不存在,有问题请联系财务！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }
        }
        // 添加工资日期
        $this->salarytime_other_model = new SalarytimeOther();
        $this->salarytime_other_model->companyId = $companyId;
        $this->salarytime_other_model->companyName = $company['customer_name'];
        $this->salarytime_other_model->salaryTime = $salaryTimeDate;
        $this->salarytime_other_model->op_salaryTime = date ( "Y-m-d H:i:s",time());
        $this->salarytime_other_model->salaryType = SalaryConst::SALARY_ER_TYPE; // 二次工资
        $this->salarytime_other_model->op_id = $this->user->id;
        $this->salarytime_other_model->add_time = date ( "Y-m-d H:i:s",time());
        $this->salarytime_other_model->salary_status = 0;
        $this->salarytime_other_model->mark = $mark;
        $this->salarytime_other_model->save($salaryTime);
        $lastSalaryTimeId = $this->salarytime_other_model->id;
        if (! $lastSalaryTimeId) {
            $transaction->rollback ();
            $response['status'] = 100001;
            $response['content'] = " 保存工资时间失败！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        for($i = 0; $i < count ($salaryList); $i ++) {
            // 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
            $salary_list = array ();
            $salary_list ['ercigongziheji'] = $salaryList [$i] [$sit_ercigongziheji];
            $salary_list ['dangyueyingfa'] = $salaryList [$i] [($sit_ercigongziheji + 1)];
            $salary_list ['yingfaheji'] = $salaryList [$i] [($sit_ercigongziheji + 2)];
            /*$salary_list ['shiye'] = $salaryList [$i] [($sit_ercigongziheji + 3)];
            $salary_list ['yiliao'] = $salaryList [$i] [($sit_ercigongziheji + 4)];
            $salary_list ['yanglao'] = $salaryList [$i] [($sit_ercigongziheji + 5)];
            $salary_list ['gongjijin'] = $salaryList [$i] [($sit_ercigongziheji + 6)];*/
            $salary_list ['yingkoushui'] = $salaryList [$i] [($sit_ercigongziheji + 3)];
            $salary_list ['yikoushui'] = $salaryList [$i] [($sit_ercigongziheji + 4)];
            $salary_list ['bukoushui'] = $salaryList [$i] [($sit_ercigongziheji + 5)];
            $salary_list ['jinka'] = $salaryList [$i] [($sit_ercigongziheji + 6)];
            $salary_list ['jiaozhongqi'] = $salaryList [$i] [($sit_ercigongziheji + 7)];

            $salary_list ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];// 身份证号
            $salary_list ['salaryTimeId'] = $lastSalaryTimeId;
            if (!empty($excelMove [$i+1]['add'])){
                $salary_list ['add_json'] = json_encode($excelMove [$i+1]['add']);
            }
            if (!empty($excelMove [$i+1]['del'])){
                $salary_list ['sal_del_json'] = json_encode($excelMove [$i+1]['del']);
            }
            if (!empty($excelMove [$i+1]['freeTex'])){
                $salary_list ['sal_free_json'] = json_encode($excelMove [$i+1]['freeTex']);
            }
            if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
                // 以上保存成功后，保存合计项
                $sql = $this->saveSumErSalary($salary_list);//print_r($salary_list);echo $sql;exit;
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->execute();
                if (! $result) {
                    $transaction->rollback ();
                    $response['status'] = 100001;
                    $response['content'] = " 保存合计工资失败！";
                    Yii::app()->end(FHelper::json($response['content'],$response['status']));
                }
            } else {
                if (empty($salaryList [$i] [$sit_ercigongziheji])) {
                    continue;
                }
                $this->er_salary_model = new ErSalary();
                $this->er_salary_model->attributes = $salary_list;
                $lastSalaryId = $this->er_salary_model->save();
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
        $response['content'] = "保存二次工资成功";
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    // 保存工资合计项
    private function saveSumErSalary($salary) {
        $sql = "insert  into  oa_er_total (salaryTime_id,sum_dangyueyingfa,sum_ercigongziheji,sum_yingfaheji,sum_yingkoushui,sum_yikoushui,sum_bukoushui,sum_jinka,sum_jiaozhongqi) values({$salary['salaryTimeId']},{$salary['dangyueyingfa']},
    	{$salary['ercigongziheji']},{$salary['yingfaheji']},
    	{$salary['yingkoushui']},{$salary['yikoushui']},{$salary['bukoushui']},{$salary['jinka']},
    	{$salary['jiaozhongqi']})";
      //sum_shiye,sum_yiliao,sum_yanglao,sum_gongjijin,
      //{$salary['shiye']},{$salary['yiliao']},{$salary['yanglao']},{$salary['gongjijin']},
        return $sql;
    }// 保存工资合计项
    private function saveSumNianSalary($salary) {
        $sql = "insert  into  oa_nian_total (salaryTime_Id,sum_nianzhongjiang,sum_daikoushui,sum_yingfaheji,sum_shifajika,sum_jiaozhongqi)
        values({$salary['salaryTimeId']},{$salary['nianzhongjiang']},{$salary['nian_daikoushui']},{$salary['yingfaheji']},{$salary['shifajinka']},{$salary['jiaozhongqi']});";

        return $sql;
    }
    private function makeDefaultFirstSalary ($employ_no,$salaryTime) {
        $employ = $this->employ_model->find( array(
            "condition"=>"e_num = :e_num",
            "params" => array(":e_num" => $employ_no)
        ));
        $company = $this->customer_model->findByPk($employ->e_company_id);
        $salaryTimePO = $this->salary_time_model->find( array(
            "condition"=>"companyId = :companyId and salaryTime = :salaryTime ",
            "params" => array(
                ":companyId" => $company->id,
                ":salaryTime" => $salaryTime,
            )
        ));
        if (! $salaryTimePO) {
            $salaryTimePPO = new SalaryTime();
            $salaryTimePPO->companyId = $company->id;
            $salaryTimePPO->salaryTime = $salaryTime;
            $salaryTimePPO->op_salaryTime = date ( "Y-m-d H:i:s",time());
            $salaryTimePPO->save();
            $saveLastId = $salaryTimePPO->id;
            $salaryTimePO ['id'] = $saveLastId;
        }
        $salaryList = new Salary();
        $salaryList ->per_yingfaheji = 0;
        $salaryList->per_shiye = 0;
        $salaryList->per_yiliao = 0;
        $salaryList->per_yanglao = 0;
        $salaryList->per_gongjijin = 0;
        $salaryList->per_daikoushui = 0;
        $salaryList->per_koukuangheji = 0;
        $salaryList->per_shifaheji = 0;
        $salaryList->com_shiye = 0;
        $salaryList->com_yiliao = 0;
        $salaryList->com_yanglao = 0;
        $salaryList->com_gongshang = 0;
        $salaryList->com_shengyu = 0;
        $salaryList->com_gongjijin = 0;
        $salaryList->com_heji = 0;
        $salaryList->laowufei = 0;
        $salaryList->canbaojin = 0;
        $salaryList->danganfei = 0;
        $salaryList->paysum_zhongqi = 0;

        $salaryList->employid = $employ_no;
        $salaryList->salaryTimeId = $saveLastId;
        $result = $salaryList->save();
        return $salaryList;
    }
    //FIXME 保存年终奖
    function actionSaveNianSalary() {
        $sit_nianzhongjiang = $_POST ['nian_bit'];
        $sit_shenfenzhenghao = $_POST ['shenfenzheng_bit'];
        $salaryYear = $_POST ['salaryYear'];
        $excelHead = $_POST ['excelHead'];

        $company_id = $_POST ['company_id'];
        $companyPO = Customer::model()->find("customer_name = '{$company_id}'");
        if (empty($companyPO)) {
            $response['status'] = 100001;
            $response['content'] = " 填写的单位不存在，请重新填写！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $company_id = $companyPO->id;

        $salaryTimeDate = $_POST ['salaryDate']."-01";

        $salaryList = json_decode($_POST['data'],true);
        //$salaryList = array_slice($salaryList,1,-1);
        $mark = $_POST ['mark'];

        foreach ( $excelHead  as $num => $row ) {
            if (strstr( $row, "身份证" )) {
                $sit_shenfenzhenghao = $num; // 等到“身份证”字段的标志位
            }
        }
        // 开始事务
        $transaction = $this->customer_model->dbConnection->beginTransaction();
        // 查询公司信息
        $company = $this->customer_model->findByPk($company_id);
        if (! empty ( $company )) {

            $companyId = $company ['id'];

        } else {
            //公司不存在
            if (! empty ( $salaryTime ['id'] )) {
                $response['status'] = 100001;
                $response['content'] = " {$company['customer_name']} 公司不存在,有问题请联系财务！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }
        }
        // 添加工资日期
        $this->salarytime_other_model = new SalarytimeOther();
        $this->salarytime_other_model->companyId = $companyId;
        $this->salarytime_other_model->companyName = $company['customer_name'];
        $this->salarytime_other_model->salaryTime = $salaryTimeDate;
        $this->salarytime_other_model->op_salaryTime = date ( "Y-m-d H:i:s",time());
        $this->salarytime_other_model->salaryType = SalaryConst::SALARY_NIAN_TYPE; // 二次工资
        $this->salarytime_other_model->op_id = $this->user->id;
        $this->salarytime_other_model->add_time = date ( "Y-m-d H:i:s",time());
        $this->salarytime_other_model->salary_status = 0;
        $this->salarytime_other_model->mark = $mark;
        $this->salarytime_other_model->year = $salaryYear;
        $this->salarytime_other_model->save($salaryTime);
        $lastSalaryTimeId = $this->salarytime_other_model->id;
        if (!$lastSalaryTimeId) {
            $transaction->rollback ();
            $response['status'] = 100001;
            $response['content'] = " 保存工资时间失败！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        for($i = 0; $i < count ($salaryList); $i ++) {
            // 如果是等于$sit_gerenyinfaheji标志位存储到固定工资表字段中
            $salary_list = array ();
            $salary_list ['nianzhongjiang'] = $salaryList [$i] [$sit_nianzhongjiang];
            $salary_list ['yingfaheji'] = $salaryList [$i] [($sit_nianzhongjiang + 1)];
            $salary_list ['nian_daikoushui'] = $salaryList [$i] [($sit_nianzhongjiang + 3)];
            $salary_list ['shifajinka'] = $salaryList [$i] [($sit_nianzhongjiang + 4)];
            $salary_list ['jiaozhongqi'] = $salaryList [$i] [($sit_nianzhongjiang + 5)];
            $salary_list ['employid'] = $salaryList [$i] [$sit_shenfenzhenghao];
            $salary_list ['salaryTimeId'] = $lastSalaryTimeId;

            if ($i == ((count ( $salaryList ) - 1))) { // 最后一行为合计所以需要减1
                // 以上保存成功后，保存合计项
                $sql = $this->saveSumNianSalary($salary_list);//print_r($salary_list);echo $sql;exit;
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->execute();
                if (! $result) {
                    $transaction->rollback ();
                    $response['status'] = 100001;
                    $response['content'] = " 保存合计工资失败！";
                    Yii::app()->end(FHelper::json($response['content'],$response['status']));
                }
            } else {
                if (empty($salaryList [$i] [$sit_nianzhongjiang])) {
                    continue;
                }
                $this->nian_salary_model = new NianSalary();
                $this->nian_salary_model->attributes = $salary_list;
                $lastSalaryId = $this->nian_salary_model->save();
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
        $response['content'] = "保存二次工资成功";
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionGetLastMonthSalary () {
        $companySearch = $this->request->getParam("company_id");
        $companyPO = Customer::model()->find("customer_name = '{$companySearch}'");
        if (empty($companyPO)) {
            $response['status'] = 100001;
            $response['content'] = " 填写的单位不存在，请重新填写！";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $companyId = $companyPO->id;
        $condition = array(
            'condition' => 'companyId = :companyId',
            'params' => array(
                ':companyId' => $companyId
            ),
            'order' => 't.salaryTime desc',
            'limit' => 1
        );
        $salaryTime = $this->salary_time_model->find($condition);
        if (!$salaryTime) {
            $response['content'] = "还没有做过工资";
            $response['status'] = "100001";
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $salaryList = $this->salary_model->findAll(array(
            'condition' => 'salaryTimeId = :salaryTimeId',
            'params' => array(
                'salaryTimeId' => $salaryTime->id,
            )
        ));
        $returnList = array();
        $head = SalaryConst::$salary_base_name_list;
        $i = 0;
        foreach ($salaryList as $salary) {
            $salaryPO = array();
            $employId = $salary->employid;
            $employModel = new Employ();
            $employ = $employModel->find(array(
                'condition' => 'e_num = :e_num',
                'params' => array(
                    'e_num' => $employId
                )
            ));
            $salaryPO[] = $employ->e_company;
            $salaryPO[] = $employ->e_name;
            $salaryPO[] = $employ->e_num;
            if ($i == 0) {
                $bool = true;
                $i ++;
            } else $bool = false;
                $movAdd = json_decode($salary->sal_add_json);
                foreach ($movAdd as $v) {
                    if ($bool) $head[] = urldecode(urldecode($v->key));
                    $salaryPO[] = $v->value;
                }
                if (!empty($salary->sal_del_json)) {
                    $movDel = json_decode($salary->sal_del_json);
                    foreach ($movDel as $v) {
                        if ($bool) $head[] = urldecode($v->key);
                        $salaryPO[] = $v->value;
                    }
                }
                if (!empty($salary->sal_free_json)) {
                    $movFree = json_decode($salary->sal_free_json);
                    foreach ($movFree as $v) {
                        if ($bool) $head[] = urldecode($v->key);
                        $salaryPO[] = $v->value;
                    }
                }
            if ($bool) $returnList[] = $head;
            $returnList[] =  $salaryPO;
        }
        $response['content'] = $returnList;
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionDelSalaryAjax () {
        $salaryTimeId = $this->request->getParam("salaryTimeId");
        $salaryType = $this->request->getParam("salaryType");
        $transaction = SalaryTime::model()->dbConnection->beginTransaction();
        if ($salaryType == 'first') {
            $salaryModel =  SalaryTime::model()->findByPk($salaryTimeId);
            if ($salaryModel && $salaryModel->salary_status > 0 && $salaryModel->salary_status != 3) {
                $response['status'] = 100001;
                $response['content'] = " 改工资已审核不能删除！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }
            SalaryTime::model()->deleteByPk($salaryTimeId);
            Salary::model()->deleteAll("salaryTimeId = $salaryTimeId");
            Total::model()->deleteAll("salaryTime_id = $salaryTimeId");
        } else {
            $salaryModel =  SalarytimeOther::model()->findByPk($salaryTimeId);
            if ($salaryModel && $salaryModel->salary_status > 0 && $salaryModel->salary_status != 3) {
                $response['status'] = 100001;
                $response['content'] = " 改工资已审核不能删除！";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }
            SalarytimeOther::model()->deleteByPk($salaryTimeId);
            if ($salaryType == 'er') {

                ErSalary::model()->deleteAll("salaryTimeId = $salaryTimeId");
                ErTotal::model()->deleteAll("salaryTime_id = $salaryTimeId");
            } elseif ($salaryType == 'nian') {
                NianSalary::model()->deleteAll("salaryTimeId = $salaryTimeId");
                NianTotal::model()->deleteAll("salaryTime_id = $salaryTimeId");
            }
        }
        $response['content'] = "删除成功";
        $response['status'] = 100000;
        $transaction->commit();
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    /**
     * @param $e_no 身份证号
     */
    private function getCanjiRen ($e_no) {
        $attr = array(
            'condition' => 'e_num = :e_num',
            'select' => 'e_teshu_state',
            'params' => array(
                ':e_num' => $e_no
            ),
        );
        $canjiren = $this->employ_model->find($attr)->e_teshu_state;
        return $canjiren[0];
    }
}