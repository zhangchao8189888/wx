<?php

/**
 * This is the model class for table "{{salary_time}}".
 *
 * The followings are the available columns in table '{{salary_time}}':
 * @property integer $id
 * @property integer $companyId
 * @property string $companyName
 * @property integer $department_id
 * @property string $salaryTime
 * @property string $op_salaryTime
 * @property integer $op_id
 * @property integer $salary_status
 * @property double $salary_leijiyue
 * @property string $mark
 */
class SalaryTime extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{salary_time}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('companyId, department_id, salaryTime, op_salaryTime', 'required'),
			array('companyId, department_id, op_id, salary_status', 'numerical', 'integerOnly'=>true),
			array('salary_leijiyue', 'numerical'),
			array('companyName', 'length', 'max'=>100),
			array('mark', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, companyId, companyName, department_id, salaryTime, op_salaryTime, op_id, salary_status, salary_leijiyue, mark', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', '', 'on'=>'t.companyId=customer.id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'companyId' => 'Company',
			'companyName' => 'Company Name',
			'department_id' => 'Department',
			'salaryTime' => 'Salary Time',
			'op_salaryTime' => 'Op Salary Time',
			'op_id' => 'Op',
			'salary_status' => 'Salary Status',
			'salary_leijiyue' => 'Salary Leijiyue',
			'mark' => 'Mark',
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
		$criteria->compare('companyId',$this->companyId);
		$criteria->compare('companyName',$this->companyName,true);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('salaryTime',$this->salaryTime,true);
		$criteria->compare('op_salaryTime',$this->op_salaryTime,true);
		$criteria->compare('op_id',$this->op_id);
		$criteria->compare('salary_status',$this->salary_status);
		$criteria->compare('salary_leijiyue',$this->salary_leijiyue);
		$criteria->compare('mark',$this->mark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalaryTime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
