<?php

/**
 * This is the model class for table "{{bank_deal}}".
 *
 * The followings are the available columns in table '{{bank_deal}}':
 * @property integer $id
 * @property string $deal_date
 * @property string $deal_val
 * @property integer $deal_type
 * @property string $deal_mark
 * @property string $deal_name
 * @property string $deal_company_name
 * @property integer $deal_company_id
 * @property string $add_time
 * @property string $update_time
 */
class BankDeal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bank_deal}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('add_time, update_time', 'required'),
			array('deal_type, deal_company_id', 'numerical', 'integerOnly'=>true),
			array('deal_val', 'length', 'max'=>10),
			array('deal_mark', 'length', 'max'=>80),
			array('deal_name, deal_company_name', 'length', 'max'=>50),
			array('deal_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, deal_date, deal_val, deal_type, deal_mark, deal_name, deal_company_name, deal_company_id, add_time, update_time', 'safe', 'on'=>'search'),
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
			'deal_date' => 'Deal Date',
			'deal_val' => 'Deal Val',
			'deal_type' => 'Deal Type',
			'deal_mark' => 'Deal Mark',
			'deal_name' => 'Deal Name',
			'deal_company_name' => 'Deal Company Name',
			'deal_company_id' => 'Deal Company',
			'add_time' => 'Add Time',
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
		$criteria->compare('deal_date',$this->deal_date,true);
		$criteria->compare('deal_val',$this->deal_val,true);
		$criteria->compare('deal_type',$this->deal_type);
		$criteria->compare('deal_mark',$this->deal_mark,true);
		$criteria->compare('deal_name',$this->deal_name,true);
		$criteria->compare('deal_company_name',$this->deal_company_name,true);
		$criteria->compare('deal_company_id',$this->deal_company_id);
		$criteria->compare('add_time',$this->add_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BankDeal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
