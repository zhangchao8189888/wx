<?php

/**
 * This is the model class for table "{{nian_total}}".
 *
 * The followings are the available columns in table '{{nian_total}}':
 * @property integer $id
 * @property integer $salaryTime_id
 * @property string $sum_nianzhongjiang
 * @property string $sum_daikoushui
 * @property string $sum_yingfaheji
 * @property string $sum_shifajika
 * @property string $sum_jiaozhongqi
 */
class NianTotal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{nian_total}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salaryTime_id', 'required'),
			array('salaryTime_id', 'numerical', 'integerOnly'=>true),
			array('sum_nianzhongjiang, sum_daikoushui, sum_yingfaheji, sum_shifajika, sum_jiaozhongqi', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, salaryTime_id, sum_nianzhongjiang, sum_daikoushui, sum_yingfaheji, sum_shifajika, sum_jiaozhongqi', 'safe', 'on'=>'search'),
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
			'salaryTime_id' => 'Salary Time',
			'sum_nianzhongjiang' => 'Sum Nianzhongjiang',
			'sum_daikoushui' => 'Sum Daikoushui',
			'sum_yingfaheji' => 'Sum Yingfaheji',
			'sum_shifajika' => 'Sum Shifajika',
			'sum_jiaozhongqi' => 'Sum Jiaozhongqi',
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
		$criteria->compare('salaryTime_id',$this->salaryTime_id);
		$criteria->compare('sum_nianzhongjiang',$this->sum_nianzhongjiang,true);
		$criteria->compare('sum_daikoushui',$this->sum_daikoushui,true);
		$criteria->compare('sum_yingfaheji',$this->sum_yingfaheji,true);
		$criteria->compare('sum_shifajika',$this->sum_shifajika,true);
		$criteria->compare('sum_jiaozhongqi',$this->sum_jiaozhongqi,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NianTotal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
