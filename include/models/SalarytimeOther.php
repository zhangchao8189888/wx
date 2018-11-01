<?php

/**
 * This is the model class for table "{{salarytime_other}}".
 *
 * The followings are the available columns in table '{{salarytime_other}}':
 * @property integer $id
 * @property string $salaryTime
 * @property string $op_salaryTime
 * @property integer $companyId
 * @property string $companyName
 * @property integer $salaryType
 * @property integer $op_id
 * @property string $mark
 * @property integer $salary_status
 * @property string $add_time
 * @property string $year
 */
class SalarytimeOther extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{salarytime_other}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salaryTime, op_salaryTime, add_time', 'required'),
			array('companyId, salaryType, op_id, salary_status', 'numerical', 'integerOnly'=>true),
			array('companyName', 'length', 'max'=>255),
			array('mark', 'length', 'max'=>50),
			array('year', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, salaryTime, op_salaryTime, companyId, companyName, salaryType, op_id, mark, salary_status, add_time, year', 'safe', 'on'=>'search'),
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
			'salaryTime' => 'Salary Time',
			'op_salaryTime' => 'Op Salary Time',
			'companyId' => 'Company',
			'companyName' => 'Company Name',
			'salaryType' => 'Salary Type',
			'op_id' => 'Op',
			'mark' => 'Mark',
			'salary_status' => 'Salary Status',
			'add_time' => 'Add Time',
			'year' => 'Year',
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
		$criteria->compare('salaryTime',$this->salaryTime,true);
		$criteria->compare('op_salaryTime',$this->op_salaryTime,true);
		$criteria->compare('companyId',$this->companyId);
		$criteria->compare('companyName',$this->companyName,true);
		$criteria->compare('salaryType',$this->salaryType);
		$criteria->compare('op_id',$this->op_id);
		$criteria->compare('mark',$this->mark,true);
		$criteria->compare('salary_status',$this->salary_status);
		$criteria->compare('add_time',$this->add_time,true);
		$criteria->compare('year',$this->year,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalarytimeOther the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
