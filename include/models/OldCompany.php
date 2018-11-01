<?php

/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property string $id
 * @property string $company_name
 * @property string $company_address
 * @property string $pact_start_date
 * @property string $pact_over_date
 * @property integer $service_fee_state
 * @property integer $service_fee_value
 * @property integer $can_bao_state
 * @property integer $can_bao_value
 * @property string $companyEmail
 * @property string $remarks
 * @property integer $geshui_dateType
 * @property integer $company_level
 * @property string $account_value
 */
class OldCompany extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{company}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_name', 'required'),
			array('service_fee_state, service_fee_value, can_bao_state, can_bao_value, geshui_dateType, company_level', 'numerical', 'integerOnly'=>true),
			array('company_name, companyEmail', 'length', 'max'=>100),
			array('company_address, remarks', 'length', 'max'=>500),
			array('account_value', 'length', 'max'=>10),
			array('pact_start_date, pact_over_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_name, company_address, pact_start_date, pact_over_date, service_fee_state, service_fee_value, can_bao_state, can_bao_value, companyEmail, remarks, geshui_dateType, company_level, account_value', 'safe', 'on'=>'search'),
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
			'company_name' => 'Company Name',
			'company_address' => 'Company Address',
			'pact_start_date' => 'Pact Start Date',
			'pact_over_date' => 'Pact Over Date',
			'service_fee_state' => 'Service Fee State',
			'service_fee_value' => 'Service Fee Value',
			'can_bao_state' => 'Can Bao State',
			'can_bao_value' => 'Can Bao Value',
			'companyEmail' => 'Company Email',
			'remarks' => 'Remarks',
			'geshui_dateType' => 'Geshui Date Type',
			'company_level' => 'Company Level',
			'account_value' => 'Account Value',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_address',$this->company_address,true);
		$criteria->compare('pact_start_date',$this->pact_start_date,true);
		$criteria->compare('pact_over_date',$this->pact_over_date,true);
		$criteria->compare('service_fee_state',$this->service_fee_state);
		$criteria->compare('service_fee_value',$this->service_fee_value);
		$criteria->compare('can_bao_state',$this->can_bao_state);
		$criteria->compare('can_bao_value',$this->can_bao_value);
		$criteria->compare('companyEmail',$this->companyEmail,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('geshui_dateType',$this->geshui_dateType);
		$criteria->compare('company_level',$this->company_level);
		$criteria->compare('account_value',$this->account_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_zhongqiOA;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OldCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
