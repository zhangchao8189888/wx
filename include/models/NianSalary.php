<?php

/**
 * This is the model class for table "{{nian_salary}}".
 *
 * The followings are the available columns in table '{{nian_salary}}':
 * @property integer $id
 * @property string $employid
 * @property integer $salaryTimeId
 * @property string $nianzhongjiang
 * @property string $nian_daikoushui
 * @property string $yingfaheji
 * @property string $shifajinka
 * @property string $jiaozhongqi
 */
class NianSalary extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{nian_salary}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employid, salaryTimeId', 'required'),
			array('salaryTimeId', 'numerical', 'integerOnly'=>true),
			array('employid', 'length', 'max'=>100),
			array('nianzhongjiang, nian_daikoushui, yingfaheji, shifajinka, jiaozhongqi', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employid, salaryTimeId, nianzhongjiang, nian_daikoushui, yingfaheji, shifajinka, jiaozhongqi', 'safe', 'on'=>'search'),
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
            'employ' => array(self::BELONGS_TO, 'Employ',array('employid'=>'e_num')),
            'salaryTime' => array(self::BELONGS_TO, 'SalaryTimeOther', 'salaryTimeId', 'order'=>'salaryTime desc'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employid' => 'Employid',
			'salaryTimeId' => 'Salary Time',
			'nianzhongjiang' => 'Nianzhongjiang',
			'nian_daikoushui' => 'Nian Daikoushui',
			'yingfaheji' => 'Yingfaheji',
			'shifajinka' => 'Shifajinka',
			'jiaozhongqi' => 'Jiaozhongqi',
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
		$criteria->compare('employid',$this->employid,true);
		$criteria->compare('salaryTimeId',$this->salaryTimeId);
		$criteria->compare('nianzhongjiang',$this->nianzhongjiang,true);
		$criteria->compare('nian_daikoushui',$this->nian_daikoushui,true);
		$criteria->compare('yingfaheji',$this->yingfaheji,true);
		$criteria->compare('shifajinka',$this->shifajinka,true);
		$criteria->compare('jiaozhongqi',$this->jiaozhongqi,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NianSalary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
