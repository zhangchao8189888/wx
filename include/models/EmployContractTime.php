<?php

/**
 * This is the model class for table "{{employ_contract_time}}".
 *
 * The followings are the available columns in table '{{employ_contract_time}}':
 * @property integer $id
 * @property string $contract_name
 * @property string $contract_start_time
 * @property string $contract_end_time
 * @property integer $contract_index
 * @property integer $company_id
 * @property integer $employ_id
 * @property integer $op_id
 * @property string $e_num
 * @property string $c_time
 * @property string $u_time
 */
class EmployContractTime extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{employ_contract_time}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contract_start_time, contract_end_time, contract_index, company_id, employ_id, e_num, c_time, u_time', 'required'),
			array('id, contract_index, company_id, employ_id, op_id', 'numerical', 'integerOnly'=>true),
			array('contract_name', 'length', 'max'=>255),
			array('e_num', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, contract_name, contract_start_time, contract_end_time, contract_index, company_id, employ_id, op_id, e_num, c_time, u_time', 'safe', 'on'=>'search'),
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
			'contract_name' => 'Contract Name',
			'contract_start_time' => 'Contract Start Time',
			'contract_end_time' => 'Contract End Time',
			'contract_index' => 'Contract Index',
			'company_id' => 'Company',
			'employ_id' => 'Employ',
			'op_id' => 'Op',
			'e_num' => 'E Num',
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
		$criteria->compare('contract_name',$this->contract_name,true);
		$criteria->compare('contract_start_time',$this->contract_start_time,true);
		$criteria->compare('contract_end_time',$this->contract_end_time,true);
		$criteria->compare('contract_index',$this->contract_index);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('employ_id',$this->employ_id);
		$criteria->compare('op_id',$this->op_id);
		$criteria->compare('e_num',$this->e_num,true);
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
	 * @return EmployContractTime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
