<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-4-3
 * Time: 下午1:51
 */
class MEmployConstruct extends EMongoDocument
{
    public $e_company_id;
    public $head_row = array();
    public $e_num_position;
    public $e_hetong_num_position;
    public $e_name_position;
    public $e_type_position;
    public $head_hash;

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
        return 'EmployConstruct';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('e_company_id ', 'required'),
            array('head_row', 'checkNull'),
            array('e_company_id', 'check_unique'),
        );
    }

    public function check_unique() {
        $res = $this->findAllByAttributes(array("e_company_id"=>$this->e_company_id));
        if (!empty($res)) {
            $this->addError('message','已经存在了');
        }
    }
    public function checkNull() {
        foreach ($this->head_row as &$val) {
            if ($val == 'null') {
                $val = '';
            }
        }
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array();
    }
}