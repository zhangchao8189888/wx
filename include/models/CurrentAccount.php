<?php

/**
 * This is the model class for table "{{current_account}}".
 *
 * The followings are the available columns in table '{{current_account}}':
 * @property integer $id
 * @property integer $company_id
 * @property string $company_name
 * @property string $op_date
 * @property string $account_val
 * @property string $pay_val
 * @property string $remian_val
 * @property integer $source_id
 * @property string $source_type
 * @property integer $deal_type
 * @property string $memo
 * @property string $c_time
 * @property string $u_time
 */
class CurrentAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{current_account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('op_date, c_time, u_time', 'required'),
			array('company_id, source_id, deal_type', 'numerical', 'integerOnly'=>true),
			array('company_name', 'length', 'max'=>50),
			array('account_val, pay_val, remian_val', 'length', 'max'=>10),
			array('source_type', 'length', 'max'=>20),
			array('memo', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, company_name, op_date, account_val, pay_val, remian_val, source_id, source_type, deal_type, memo, c_time, u_time', 'safe', 'on'=>'search'),
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
			'company_name' => 'Company Name',
			'op_date' => 'Op Date',
			'account_val' => 'Account Val',
			'pay_val' => 'Pay Val',
			'remian_val' => 'Remian Val',
			'source_id' => 'Source',
			'source_type' => 'Source Type',
			'deal_type' => 'Deal Type',
			'memo' => 'Memo',
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
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('op_date',$this->op_date,true);
		$criteria->compare('account_val',$this->account_val,true);
		$criteria->compare('pay_val',$this->pay_val,true);
		$criteria->compare('remian_val',$this->remian_val,true);
		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('source_type',$this->source_type,true);
		$criteria->compare('deal_type',$this->deal_type);
		$criteria->compare('memo',$this->memo,true);
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
	 * @return CurrentAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
