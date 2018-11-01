<?php

/**
 * This is the model class for table "{{salary_send}}".
 *
 * The followings are the available columns in table '{{salary_send}}':
 * @property integer $id
 * @property integer $company_id
 * @property integer $salary_send_type
 * @property integer $admin_id
 * @property string $c_time
 * @property string $u_time
 */
class SalarySend extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{salary_send}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('c_time, u_time', 'required'),
			array('company_id, salary_send_type, admin_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, salary_send_type, admin_id, c_time, u_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_id' => 'Company',
			'salary_send_type' => 'Salary Send Type',
			'admin_id' => 'Admin',
			'c_time' => 'C Time',
			'u_time' => 'U Time',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('salary_send_type',$this->salary_send_type);
		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('c_time',$this->c_time,true);
		$criteria->compare('u_time',$this->u_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalarySend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
