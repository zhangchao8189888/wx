<?php

/**
 * This is the MongoDB Document model class based on table "{{employ}}".
 */
class MEmploy extends EMongoDocument
{
	public $_id;
	public $e_company_id;
	public $e_num;
	public $e_name;
	public $e_type;
	public $e_hetong_num;
	public $e_num_position;
	public $emp_info_row = array();

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
		return 'Employ';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('e_company_id, e_num', 'required'),
            array('emp_info_row', 'checkNull'),
            array('e_num', 'check_unique',),
            array('e_type', 'check_type'),
		);
	}
    public function checkNull() {
        foreach ($this->emp_info_row as &$val) {
            if ($val == 'null') {
                $val = '';
            }
        }
    }
    public function check_type () {
        $employ_type = (array)FConfig::item("config.employ_type");
        $this->e_type = trim($this->e_type);
        if (!in_array($this->e_type,$employ_type)) {
            $str = implode("," , $employ_type);
            $this->addError('message',$this->e_type."类别不存在，应该在$str 中选择");
            return;
        }
    }
    public function check_unique() {
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
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'e_company_id' => 'E Company',
			'e_name' => 'E Name',
			'e_company' => 'E Company',
			'e_num' => 'E Num',
			'bank_name' => 'Bank Name',
			'bank_num' => 'Bank Num',
			'e_type' => 'E Type',
			'e_type_name' => 'E Type Name',
			'shebaojishu' => 'Shebaojishu',
			'gongjijinjishu' => 'Gongjijinjishu',
			'laowufei' => 'Laowufei',
			'canbaojin' => 'Canbaojin',
			'danganfei' => 'Danganfei',
			'memo' => 'Memo',
			'e_status' => 'E Status',
			'e_hetongnian' => 'E Hetongnian',
			'e_hetong_date' => 'E Hetong Date',
			'e_teshu_state' => 'E Teshu State',
			'department_id' => 'Department',
			'e_sort' => 'E Sort',
			'update_time' => 'Update Time',
		);
	}
}