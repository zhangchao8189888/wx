<?php

/**
 * This is the model class for table "{{er_total}}".
 *
 * The followings are the available columns in table '{{er_total}}':
 * @property integer $id
 * @property integer $salaryTime_id
 * @property double $sum_dangyueyingfa
 * @property double $sum_ercigongziheji
 * @property double $sum_yingfaheji
 * @property double $sum_shiye
 * @property double $sum_yiliao
 * @property double $sum_yanglao
 * @property double $sum_gongjijin
 * @property double $sum_yingkoushui
 * @property double $sum_yikoushui
 * @property double $sum_bukoushui
 * @property double $sum_jinka
 * @property double $sum_jiaozhongqi
 */
class ErTotal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{er_total}}';
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
			array('sum_dangyueyingfa, sum_ercigongziheji, sum_yingfaheji, sum_shiye, sum_yiliao, sum_yanglao, sum_gongjijin, sum_yingkoushui, sum_yikoushui, sum_bukoushui, sum_jinka, sum_jiaozhongqi', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, salaryTime_id, sum_dangyueyingfa, sum_ercigongziheji, sum_yingfaheji, sum_shiye, sum_yiliao, sum_yanglao, sum_gongjijin, sum_yingkoushui, sum_yikoushui, sum_bukoushui, sum_jinka, sum_jiaozhongqi', 'safe', 'on'=>'search'),
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
			'sum_dangyueyingfa' => 'Sum Dangyueyingfa',
			'sum_ercigongziheji' => 'Sum Ercigongziheji',
			'sum_yingfaheji' => 'Sum Yingfaheji',
			'sum_shiye' => 'Sum Shiye',
			'sum_yiliao' => 'Sum Yiliao',
			'sum_yanglao' => 'Sum Yanglao',
			'sum_gongjijin' => 'Sum Gongjijin',
			'sum_yingkoushui' => 'Sum Yingkoushui',
			'sum_yikoushui' => 'Sum Yikoushui',
			'sum_bukoushui' => 'Sum Bukoushui',
			'sum_jinka' => 'Sum Jinka',
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
		$criteria->compare('sum_dangyueyingfa',$this->sum_dangyueyingfa);
		$criteria->compare('sum_ercigongziheji',$this->sum_ercigongziheji);
		$criteria->compare('sum_yingfaheji',$this->sum_yingfaheji);
		$criteria->compare('sum_shiye',$this->sum_shiye);
		$criteria->compare('sum_yiliao',$this->sum_yiliao);
		$criteria->compare('sum_yanglao',$this->sum_yanglao);
		$criteria->compare('sum_gongjijin',$this->sum_gongjijin);
		$criteria->compare('sum_yingkoushui',$this->sum_yingkoushui);
		$criteria->compare('sum_yikoushui',$this->sum_yikoushui);
		$criteria->compare('sum_bukoushui',$this->sum_bukoushui);
		$criteria->compare('sum_jinka',$this->sum_jinka);
		$criteria->compare('sum_jiaozhongqi',$this->sum_jiaozhongqi);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ErTotal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
