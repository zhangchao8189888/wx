<?php
/**
 * Created by PhpStorm.
 * User: zhangchao-rj
 * Date: 2018/3/17
 * Time: 下午1:52
 */

class UpdateCompanyAccountCommand  extends CConsoleCommand
{

    /*public function run($args)
    {
        echo '测试command';
    }
    php yiic.php help rundata
    */
    public function actionDeductionGeshuiData()
    {
        echo "run DeductionGeshuiData ----".date("Y-m-d h:i:s",time())."----\n";
        $salary_date = date("Y-m-01",time());
        $lastMonth = date("Y-m-01",strtotime("$salary_date -1 month"));
        //Total::model()->
        $salTimeList = SalaryTime::model()->findAll(array(
            "condition" => "salaryTime = '{$lastMonth}' and salary_status = 2",
        ));
        $type = FConfig::item('config.salary_type');
        $transaction = Yii::app()->db->beginTransaction();
        foreach ($salTimeList as $salaryTimeModel) {
            $model = array();

            $salaryTotalModel = Total::model()->find("salaryTime_id = {$salaryTimeModel->id}");
            $customerModel = Customer::model()->findByPk($salaryTimeModel->companyId);
            $model['company_id'] = $salaryTimeModel->companyId;
            $model['company_name'] = $salaryTimeModel->companyName;
            $model['source_id'] = $salaryTimeModel->id;
            $model['model_class'] = "SalaryTime";
            $model['account_val'] = $customerModel->account_val;
            $model['geshui'] = -floatval($salaryTotalModel->sum_per_daikoushui);
            $model['text'] = $salaryTimeModel->companyName . date("Y-m", strtotime($salaryTimeModel->salaryTime));
            $this->addGeshuiAccountItem($model);
        }

        $erSalaryList = SalarytimeOther::model()->findAllByAttributes(array(

            "salaryTime" => "$lastMonth",
        ));
        if (!empty($erSalaryList)) {
            foreach ($erSalaryList as $erSalary) {
                if ($erSalary->salaryType == $type['SALARY_ER']) {
                    $erTotal = ErTotal::model()->find("salaryTime_id = {$erSalary->id}");

                    $model['geshui'] = -floatval($erTotal->sum_bukoushui);
                    $model['text'] = $erSalary->companyName . date("Y-m", strtotime($erSalary->salaryTime))."二次工资";
                } elseif ($erSalary->salaryType == $type['SALARY_NIAN']) {
                    $erTotal = NianTotal::model()->find("salaryTime_id = {$erSalary->id}");

                    $model['geshui'] = -floatval($erTotal->sum_daikoushui);
                    $model['text'] = $erSalary->companyName . date("Y-m", strtotime($erSalary->salaryTime))."年终奖";

                }
                $customerModel = Customer::model()->findByPk($erSalary->companyId);
                $model['company_id'] = $erSalary->companyId;
                $model['company_name'] = $erSalary->companyName;
                $model['source_id'] = $erSalary->id;
                $model['model_class'] = "SalaryTimeOther";
                $model['account_val'] = $customerModel->account_val;
                $this->addGeshuiAccountItem($model);
            }
        }
        $transaction->commit();
        //echo "111111111.\n";
        echo "end DeductionGeshuiData ----".date("Y-m-d h:i:s",time())."----\n";

    }

    public function actionDeductionShebaoData()
    {
        echo "run DeductionShebaoData ----".date("Y-m-d h:i:s",time())."----\n";
        $salary_date = date("Y-m-01",time());
        //$salary_date = '2018-07-01';
        $lastMonth = date("Y-m-01",strtotime("$salary_date -1 month"));//2018-07-01
        $salTimeList = SalaryTime::model()->findAll(array(
            "condition" => "salaryTime = '{$lastMonth}' and salary_status = 2",
        ));
        $transaction = Yii::app()->db->beginTransaction();
        foreach ($salTimeList as $salaryTimeModel) {
            $model = array();

            $salaryTotalModel = Total::model()->find("salaryTime_id = {$salaryTimeModel->id}");
            $customerModel = Customer::model()->findByPk($salaryTimeModel->companyId);
            $model['company_id'] = $salaryTimeModel->companyId;
            $model['company_name'] = $salaryTimeModel->companyName;
            $model['source_id'] = $salaryTimeModel->id;
            $model['model_class'] = "SalaryTime";
            $model['account_val'] = $customerModel->account_val;
            $model['shebao'] = -floatval($salaryTotalModel->sum_per_yiliao
                +$salaryTotalModel->sum_per_yanglao+$salaryTotalModel->sum_per_shiye
                +$salaryTotalModel->sum_com_shiye
                +$salaryTotalModel->sum_com_yiliao+$salaryTotalModel->sum_com_yanglao
                +$salaryTotalModel->sum_com_gongshang+$salaryTotalModel->sum_com_shengyu
            );
            $model['gongjijin'] = -floatval($salaryTotalModel->sum_per_gongjijin
                +$salaryTotalModel->sum_com_gongjijin
            );
            $model['text'] = $salaryTimeModel->companyName . date("Y-m", strtotime($salaryTimeModel->salaryTime));
            $this->addShebaoAccountItem($model);
            $customerModel_1 = Customer::model()->findByPk($salaryTimeModel->companyId);
            $model['account_val'] = $customerModel_1->account_val;
            $this->addGongjijinAccountItem($model);
        }
        $transaction->commit();

        echo "end DeductionShebaoData ----".date("Y-m-d h:i:s",time())."----\n";
    }
    private function addGeshuiAccountItem($model)
    {
        $res = CurrentAccount::model()->findByAttributes(array(
            "deal_type"=>5,
            "source_id"=>$model['source_id'],
        ));
        if (!empty($res)) {
            return;
        }
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['geshui'];
        $currentAccountModel->deal_type = 5;//个税
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['geshui']);
        $currentAccountModel->memo = $model['text'] . "代扣税扣减";
        $currentAccountModel->op_date = date("Y-m-d H:i:s", time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s", time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s", time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'], $accountVal), true);
        return $accountVal;
    }

    private function addGongjijinAccountItem($model)
    {
        $res = CurrentAccount::model()->findByAttributes(array(
            "deal_type"=>6,
            "source_id"=>$model['source_id'],
        ));
        if (!empty($res)) {
            return;
        }
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['gongjijin'];
        $currentAccountModel->deal_type = 6;//个税
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['gongjijin']);
        $currentAccountModel->memo = $model['text'] . "公积金扣减";
        $currentAccountModel->op_date = date("Y-m-d H:i:s", time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s", time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s", time());
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'], $accountVal), true);
        return $accountVal;
    }

    private function addShebaoAccountItem($model)
    {
        $res = CurrentAccount::model()->findByAttributes(array(
            "deal_type"=>4,
            "source_id"=>$model['source_id'],
        ));
        if (!empty($res)) {
            return;
        }
        $currentAccountModel = new CurrentAccount();
        $currentAccountModel->company_id = $model['company_id'];
        $currentAccountModel->company_name = $model['company_name'];
        $currentAccountModel->source_id = $model['source_id'];
        $currentAccountModel->source_type = $model['model_class'];
        $currentAccountModel->account_val = $model['account_val'];
        $currentAccountModel->pay_val = $model['shebao'];
        $currentAccountModel->deal_type = 4;//社保
        $currentAccountModel->remian_val = $accountVal = floatval($model['account_val']) + floatval($model['shebao']);
        $currentAccountModel->memo = $model['text'] . "社保扣减";
        $currentAccountModel->op_date = date("Y-m-d H:i:s", time());
        $currentAccountModel->c_time = date("Y-m-d H:i:s", time());
        $currentAccountModel->u_time = date("Y-m-d H:i:s", time());
        //print_r($currentAccountModel);
        $result = $currentAccountModel->save();
        if ($result) print_r($this->updateCustomerAccountValById($model['company_id'], $accountVal), true);
        return $accountVal;
    }
    protected function updateCustomerAccountValById($id,$accountVal) {
        return Customer::model()->updateByPk($id,array(
            'account_val' => $accountVal
        ));
    }
}