<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/9/10
 * Time: 下午12:14
 */
class RunDataCommand  extends CConsoleCommand
{

    /*public function run($args)
    {
        echo '测试command';
    }*/
    public function actionCopyCompanyData () {
        $oldCompany_model = new OldCompany();
        $arr = $oldCompany_model->findAll();
        foreach ($arr as $company) {
            $customer_model = new Customer();
            $customer_model->customer_name =  $arr->company_name;
            $res = $customer_model->find(array("condition"=>"customer_name = '{$company->company_name}'"));
            if ($res) {
                /*$updateArr = array(
                    "customer_principal_level" => 1
                );
                //$updateRes = Customer :: model()->updateByPk($res->id,$updateArr);
                $updateRes = Customer :: model()->findByPk($res->id);
                $updateRes->id = $company->id;
                $updateRes->update();*/

            } else {
                $save = new Customer();
                $save->id = $company->id;
                $save->op_id = 1;
                $save->customer_name = $company->company_name;
                $save->save();
                print_r($save);
            }
        }
    }

    /**
     * @param $companyName
     * php yiic.php runData copyEmployData --companyName=经管学院
     */
    public function actionCopyEmployData ($companyName) {
        $condition = "";
        if (!empty($companyName)) {
            $condition = "e_company = '{$companyName}'";
        }
        $oldEmploy_model = new OldEmploy();
        $arr = $oldEmploy_model->findAll($condition);
        $userType = FConfig::item("config.employ_type_val");
        $i = 0;
        foreach ($arr as $old_employ) {

            $employ = Employ::model()->find('e_num = :e_num',array(":e_num"=>trim($old_employ->e_num)));
            if (!$employ) {
                /**
                 * @property integer $id
                 * @property integer $e_company_id
                 * @property string $e_name
                 * @property string $e_company
                 * @property string $e_num
                 * @property string $bank_name
                 * @property string $bank_num
                 * @property integer $e_type
                 * @property string $e_type_name
                 * @property string $shebaojishu
                 * @property string $gongjijinjishu
                 * @property string $laowufei
                 * @property string $canbaojin
                 * @property string $danganfei
                 * @property string $memo
                 * @property integer $e_status
                 * @property integer $e_hetongnian
                 * @property string $e_hetong_date
                 * @property integer $e_teshu_state
                 * @property integer $department_id
                 * @property integer $e_sort
                 * @property string $update_time
                 */
                //print_r($old_employ);exit;
                $save = new Employ();
                //$save->id = $old_employ->id;
                $res = Customer::model()->find(array("condition"=>"customer_name = '{$old_employ->e_company}'"));
                $save->e_company_id = $res->id;
                $save->e_company = $old_employ->e_company;
                $save->e_name = $old_employ->e_name;
                $save->e_num = $old_employ->e_num;
                $save->bank_name = $old_employ->bank_name;
                $save->bank_num = $old_employ->bank_num;
                $save->e_type = $userType[$old_employ->e_type];
                $save->e_type_name = $old_employ->e_type;
                $save->shebaojishu = $old_employ->shebaojishu;
                $save->gongjijinjishu = $old_employ->gongjijinjishu;
                $save->laowufei = $old_employ->laowufei;
                $save->canbaojin = $old_employ->canbaojin;
                $save->danganfei = $old_employ->danganfei;
                $save->memo = $old_employ->memo;
                $save->e_status = 1;
                $save->e_hetongnian = $old_employ->e_hetongnian;
                $save->e_teshu_state = $old_employ->e_teshu_state;
                $save->e_hetong_date = $old_employ->e_hetong_date;
                $save->update_time = date("Y-m-d H:i:s",time());
                $save->save();
                //print_r($save);
                //exit;
                echo $old_employ->id.":$save->e_name"."\n";
                $i++;
            } else {
                $arrE = array(
                    "e_num" => $old_employ->e_num,
                    "e_name" => $old_employ->e_name,
                    "shebaojishu" => $old_employ->shebaojishu,
                    "gongjijinjishu" => $old_employ->gongjijinjishu,
                    "laowufei" => $old_employ->laowufei,
                    "e_type_name" => $old_employ->e_type,
                    "e_type" => $userType[$old_employ->e_type],
                );
                Employ::model()->updateByPk($employ->id,$arrE);
                echo $old_employ->id."更新:$old_employ->e_name"."\n";
            }
        }
        echo $i."\n";
    }
    public function actionModifyEType () {
        $Employ_model = new Employ();
        $arr = $Employ_model->findAll();
        $userType = FConfig::item("config.employ_type_val");
        foreach ($arr as $employ) {
            $e_type = $userType[$employ->e_type_name];
            if ($e_type || $e_type == 0) {

                Employ::model()->updateByPk($employ->id,array("e_type" => $e_type));
            } else {
                echo $employ->e_type_name."|".$employ->id."\n";
            }
        }
    }
    public function actionUpdateCompanyName () {
        $filePath = "/root/updateComName.xls";
        $readExcel = "";
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
                    $_newExcel [$temp] [] = $val;
                }
                $temp++;
            }
        }
        array_shift($_newExcel);

        //print_r($_newExcel);exit;
        $i = 0;
        $result = array();
        foreach ($_newExcel as $row) {

            if (!empty($row[0]) && !empty($row[1])&& !empty($row[2]) && $row[1] != $row[2]) {

                $result[] = $row;
                Customer::model()->updateByPk($row[0],array("customer_name" => $row[2]));
                AdminCompany::model()->updateAll(array("companyName" => $row[2]),array(
                    "condition" => "companyId = {$row[0]}"
                ));
                CurrentAccount::model()->updateAll(array("company_name" => $row[2]),array(
                    "condition" => "company_id = {$row[0]}"
                ));
                Employ::model()->updateAll(array("e_company" => $row[2]),array(
                    "condition" => "e_company_id = {$row[0]}"
                ));
                SalaryTime::model()->updateAll(array("companyName" => $row[2]),array(
                    "condition" => "companyId = {$row[0]}"
                ));
                SalarytimeOther::model()->updateAll(array("companyName" => $row[2]),array(
                    "condition" => "companyId = {$row[0]}"
                ));
                /*$i++;
                if ($i>4){

                    print_r($row);exit;
                }*/

            }
            //echo 111;exit;
        }
        error_log(print_r($result,true)."\n",3,"/tmp/error.log");
    }

    public function actionUpdateOldUserCompanyId () {
        $userList = OldUser::model()->findAll();
        foreach ($userList as $user) {
            $employ = Employ::model()->findByAttributes(array("e_num"=>$user->e_num));

            $result = OldUser::model()->updateByPk($user->id,array("company_id"=>$employ->e_company_id));
            //print_r($userModel);
            //print_r($employ->e_company_id);

        }
    }
}