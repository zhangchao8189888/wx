<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-4-9
 * Time: 下午4:02
 */
class MSocialException extends EMongoDocument
{
    public $_id;
    public $company_name;
    public $belong_company_name;
    public $section;
    public $e_name;
    public $e_num;
    public $date_month;// 社保增员月份
    public $check_man;
    public $exception_note;
    public $type;
    public $date;//创建日期

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
        return 'SocialException';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('e_num ', 'required'),
            array('belong_company_name,e_name,date', 'checkNull'),
            array('e_num', 'check_unique'),
        );
    }

    public function check_unique() {

        if ($this->e_num == 'null' || empty($this->e_num)) {
            $this->addError('message',$this->e_num.'不能为空');
            return;
        }
        $mod = $this->getScenario();
        if ($mod == 'update') {
            $res = $this->findByAttributes(array("e_num"=>$this->e_num, "type"=>$this->type));
            if (!empty($res) && $res->_id->{'$id'} !== $this->_id->{'$id'}) {
                if (!empty($res)) {
                    $this->addError('message',$this->e_num.'已经存在了');
                }
            }
        } elseif ($mod == 'insert') {
            $res = $this->findAllByAttributes(array("e_num"=>$this->e_num, "type"=>$this->type));
            if (!empty($res)) {
                $this->addError('message',$this->e_num.'已经存在了');
            }
        }
    }

    public function checkNull() {
        $this->belong_company_name = $this->belong_company_name=='null' ? '' : $this->belong_company_name;
        $this->e_name = $this->e_name=='null' ? '' : $this->e_name;
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