<?php

/**
 * This is the model class for table "{{customer}}".
 *
 * The followings are the available columns in table '{{customer}}':
 * @property integer $id
 * @property string $customer_name
 * @property string $customer_address
 * @property string $service_fee
 * @property string $canbaojin
 * @property string $date_rang_json
 * @property string $customer_principal
 * @property string $customer_principal_level
 * @property string $customer_principal_phone
 * @property string $account_val
 * @property integer $salary_send
 * @property integer $op_id
 * @property string $remark
 */
class Customer extends CActiveRecord
{
    const SALARY_SEND_TYPE_UNSET = 0;
    const SALARY_SEND_TYPE_CURRENT_SEND = 1;
    const SALARY_SEND_TYPE_NEXT_SEND = 2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{customer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('op_id', 'required'),
			array('salary_send, op_id', 'numerical', 'integerOnly'=>true),
			array('customer_name', 'length', 'max'=>50),
			array('customer_address', 'length', 'max'=>200),
			array('service_fee, canbaojin, account_val', 'length', 'max'=>10),
			array('date_rang_json', 'length', 'max'=>800),
			array('customer_principal, customer_principal_level', 'length', 'max'=>20),
			array('customer_principal_phone', 'length', 'max'=>30),
			array('remark', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_name, customer_address, service_fee, canbaojin, date_rang_json, customer_principal, customer_principal_level, customer_principal_phone, account_val, salary_send, op_id, remark', 'safe', 'on'=>'search'),
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
			'customer_name' => 'Customer Name',
			'customer_address' => 'Customer Address',
			'service_fee' => 'Service Fee',
			'canbaojin' => 'Canbaojin',
			'date_rang_json' => 'Date Rang Json',
			'customer_principal' => 'Customer Principal',
			'customer_principal_level' => 'Customer Principal Level',
			'customer_principal_phone' => 'Customer Principal Phone',
			'account_val' => 'Account Val',
			'salary_send' => 'Salary Send',
			'op_id' => 'Op',
			'remark' => 'Remark',
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
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_address',$this->customer_address,true);
		$criteria->compare('service_fee',$this->service_fee,true);
		$criteria->compare('canbaojin',$this->canbaojin,true);
		$criteria->compare('date_rang_json',$this->date_rang_json,true);
		$criteria->compare('customer_principal',$this->customer_principal,true);
		$criteria->compare('customer_principal_level',$this->customer_principal_level,true);
		$criteria->compare('customer_principal_phone',$this->customer_principal_phone,true);
		$criteria->compare('account_val',$this->account_val,true);
		$criteria->compare('salary_send',$this->salary_send);
		$criteria->compare('op_id',$this->op_id);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
