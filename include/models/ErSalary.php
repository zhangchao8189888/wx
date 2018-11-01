<?php

/**
 * This is the model class for table "{{er_salary}}".
 *
 * The followings are the available columns in table '{{er_salary}}':
 * @property integer $id
 * @property string $employid
 * @property integer $salaryTimeId
 * @property string $dangyueyingfa
 * @property string $ercigongziheji
 * @property string $yingfaheji
 * @property string $yingkoushui
 * @property string $yikoushui
 * @property string $bukoushui
 * @property string $jinka
 * @property string $jiaozhongqi
 * @property string $add_json
 */
class ErSalary extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{er_salary}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('salaryTimeId', 'numerical', 'integerOnly'=>true),
			array('employid', 'length', 'max'=>100),
			array('dangyueyingfa, ercigongziheji, yingfaheji, yingkoushui, yikoushui, bukoushui, jinka, jiaozhongqi', 'length', 'max'=>10),
			array('add_json', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employid, salaryTimeId, dangyueyingfa, ercigongziheji, yingfaheji, yingkoushui, yikoushui, bukoushui, jinka, jiaozhongqi, add_json', 'safe', 'on'=>'search'),
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
			'dangyueyingfa' => 'Dangyueyingfa',
			'ercigongziheji' => 'Ercigongziheji',
			'yingfaheji' => 'Yingfaheji',
			'yingkoushui' => 'Yingkoushui',
			'yikoushui' => 'Yikoushui',
			'bukoushui' => 'Bukoushui',
			'jinka' => 'Jinka',
			'jiaozhongqi' => 'Jiaozhongqi',
			'add_json' => 'Add Json',
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
		$criteria->compare('dangyueyingfa',$this->dangyueyingfa,true);
		$criteria->compare('ercigongziheji',$this->ercigongziheji,true);
		$criteria->compare('yingfaheji',$this->yingfaheji,true);
		$criteria->compare('yingkoushui',$this->yingkoushui,true);
		$criteria->compare('yikoushui',$this->yikoushui,true);
		$criteria->compare('bukoushui',$this->bukoushui,true);
		$criteria->compare('jinka',$this->jinka,true);
		$criteria->compare('jiaozhongqi',$this->jiaozhongqi,true);
		$criteria->compare('add_json',$this->add_json,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ErSalary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
