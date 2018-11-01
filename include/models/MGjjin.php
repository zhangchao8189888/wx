<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-4-9
 * Time: 下午4:02
 */
class MGjjin extends EMongoDocument
{
    public $_id;
    public $e_company_id;//单位id
    public $belong_company_name;//单位名称
    public $e_name;//员工姓名
    public $e_num;//身份证号
    public $e_type;//户口性质
    public $e_gjjin_base;//公积金基数
    public $e_address;//户口地址
    public $e_memo;//备注
    public $is_new_gjjin;//是否新参
    public $add_status;//增员状态字段
    public $sub_status;//减员状态字段
    public $date;//操作日期

    /**
     * Returns the static model of the specified AR class.
     * @return MEmploy the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * returns the primary key field for this model
     */
    public function primaryKey()
    {
        return '_id';
    }

    /**
     * @return string the associated collection name
     */
    public function getCollectionName()
    {
        return 'Gjjin';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('e_gjjin_base, e_num', 'required'),
            array('e_company_id, belong_company_name, e_name, e_type, e_address, e_memo, is_new_gjjin, add_status, sub_status,date', 'checkNull'),
            array('e_num', 'e_num_check_unique'),
        );
    }
    // 身份证号唯一
    public function e_num_check_unique() {
        if ($this->e_num == 'null' || empty($this->e_num)) {
            $this->addError('message',$this->e_num.'不能为空');
            return;
        }
        $mod = $this->getScenario();
        if ($mod == 'update') {
            $res = $this->findByAttributes(array("e_num"=>$this->e_num));
            if (!empty($res) && $res->_id->{'$id'} !== $this->_id->{'$id'}) {
                if (!empty($res)) {
                    $this->addError('message',$this->e_num.'已经存在了');
                }
            }
        } elseif ($mod == 'insert') {
            $res = $this->findAllByAttributes(array("e_num"=>$this->e_num));
            if (!empty($res)) {
                $this->addError('message',$this->e_num.'已经存在了');
            }
        }
    }
    public function checkNull() {
        $this->e_company_id = $this->e_company_id=='null' ? '' : $this->e_company_id;
        $this->belong_company_name = $this->belong_company_name=='null' ? '' : $this->belong_company_name;
        $this->e_name = $this->e_name=='null' ? '' : $this->e_name;
        $this->e_type = $this->e_type=='null' ? '' : $this->e_type;
        $this->e_address = $this->e_address=='null' ? '' : $this->e_address;
        $this->e_memo = $this->e_memo=='null' ? '' : $this->e_memo;
        $this->is_new_gjjin = $this->is_new_gjjin=='null' ? '' : $this->is_new_gjjin;
        $this->add_status = $this->add_status=='null' ? '' : $this->add_status;
        $this->sub_status = $this->sub_status=='null' ? '' : $this->sub_status;
        $this->date = $this->date=='null' ? '' : $this->date;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array();
    }
}