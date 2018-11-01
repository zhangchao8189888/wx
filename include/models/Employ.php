<?php

/**
 * This is the model class for table "{{employ}}".
 *
 * The followings are the available columns in table '{{employ}}':
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
class Employ extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{employ}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('e_company, e_num,  update_time', 'required'),
			array('e_company_id, e_type, e_status, e_hetongnian, e_teshu_state, department_id, e_sort', 'numerical', 'integerOnly'=>true),
			array('e_name', 'length', 'max'=>20),
			array('e_company, e_num, bank_num, e_type_name', 'length', 'max'=>40),
			array('bank_name', 'length', 'max'=>50),
			array('shebaojishu, gongjijinjishu, laowufei, canbaojin, danganfei', 'length', 'max'=>10),
			array('memo', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, e_company_id, e_name, e_company, e_num, bank_name, bank_num, e_type, e_type_name, shebaojishu, gongjijinjishu, laowufei, canbaojin, danganfei, memo, e_status, e_hetongnian, e_hetong_date, e_teshu_state, department_id, e_sort, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
        return array(
            'employ_info' =>array(self::HAS_ONE, 'EmployInfo', 'employ_id'),
            'salary' => array(self::HAS_MANY, 'Salary', '', 'on'=>'t.e_num=salary.employid'),
        );
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

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('e_company_id',$this->e_company_id);
		$criteria->compare('e_name',$this->e_name,true);
		$criteria->compare('e_company',$this->e_company,true);
		$criteria->compare('e_num',$this->e_num,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_num',$this->bank_num,true);
		$criteria->compare('e_type',$this->e_type);
		$criteria->compare('e_type_name',$this->e_type_name,true);
		$criteria->compare('shebaojishu',$this->shebaojishu,true);
		$criteria->compare('gongjijinjishu',$this->gongjijinjishu,true);
		$criteria->compare('laowufei',$this->laowufei,true);
		$criteria->compare('canbaojin',$this->canbaojin,true);
		$criteria->compare('danganfei',$this->danganfei,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('e_status',$this->e_status);
		$criteria->compare('e_hetongnian',$this->e_hetongnian);
		$criteria->compare('e_hetong_date',$this->e_hetong_date,true);
		$criteria->compare('e_teshu_state',$this->e_teshu_state);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('e_sort',$this->e_sort);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Employ the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
