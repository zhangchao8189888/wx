<?php

/**
 * This is the model class for table "{{salary}}".
 *
 * The followings are the available columns in table '{{salary}}':
 * @property integer $id
 * @property string $employid
 * @property integer $salaryTimeId
 * @property string $per_yingfaheji
 * @property string $per_shiye
 * @property string $per_yiliao
 * @property string $per_yanglao
 * @property string $per_gongjijin
 * @property string $per_daikoushui
 * @property string $per_koukuangheji
 * @property string $per_shifaheji
 * @property string $com_shiye
 * @property string $com_yiliao
 * @property string $com_yanglao
 * @property string $com_gongshang
 * @property string $com_shengyu
 * @property string $com_gongjijin
 * @property string $com_heji
 * @property string $laowufei
 * @property string $canbaojin
 * @property string $danganfei
 * @property string $paysum_zhongqi
 * @property integer $salary_type
 * @property string $sal_add_json
 * @property string $sal_del_json
 * @property string $sal_free_json
 */
class Salary extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{salary}}';
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
			array('salaryTimeId, salary_type', 'numerical', 'integerOnly'=>true),
			array('employid', 'length', 'max'=>100),
			array('per_yingfaheji, per_shiye, per_yiliao, per_yanglao, per_gongjijin, per_daikoushui, per_koukuangheji, per_shifaheji, com_shiye, com_yiliao, com_yanglao, com_gongshang, com_shengyu, com_gongjijin, com_heji, laowufei, canbaojin, danganfei, paysum_zhongqi', 'length', 'max'=>10),
			array('sal_free_json', 'length', 'max'=>1000),
			array('sal_add_json, sal_del_json', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employid, salaryTimeId, per_yingfaheji, per_shiye, per_yiliao, per_yanglao, per_gongjijin, per_daikoushui, per_koukuangheji, per_shifaheji, com_shiye, com_yiliao, com_yanglao, com_gongshang, com_shengyu, com_gongjijin, com_heji, laowufei, canbaojin, danganfei, paysum_zhongqi, salary_type, sal_add_json, sal_del_json, sal_free_json', 'safe', 'on'=>'search'),
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
            'salaryTime' => array(self::BELONGS_TO, 'SalaryTime', 'salaryTimeId', 'order'=>'salaryTime desc'),
		);
	}
//
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employid' => 'Employid',
			'salaryTimeId' => 'Salary Time',
			'per_yingfaheji' => 'Per Yingfaheji',
			'per_shiye' => 'Per Shiye',
			'per_yiliao' => 'Per Yiliao',
			'per_yanglao' => 'Per Yanglao',
			'per_gongjijin' => 'Per Gongjijin',
			'per_daikoushui' => 'Per Daikoushui',
			'per_koukuangheji' => 'Per Koukuangheji',
			'per_shifaheji' => 'Per Shifaheji',
			'com_shiye' => 'Com Shiye',
			'com_yiliao' => 'Com Yiliao',
			'com_yanglao' => 'Com Yanglao',
			'com_gongshang' => 'Com Gongshang',
			'com_shengyu' => 'Com Shengyu',
			'com_gongjijin' => 'Com Gongjijin',
			'com_heji' => 'Com Heji',
			'laowufei' => 'Laowufei',
			'canbaojin' => 'Canbaojin',
			'danganfei' => 'Danganfei',
			'paysum_zhongqi' => 'Paysum Zhongqi',
			'salary_type' => 'Salary Type',
			'sal_add_json' => 'Sal Add Json',
			'sal_del_json' => 'Sal Del Json',
			'sal_free_json' => 'Sal Free Json',
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
		$criteria->compare('per_yingfaheji',$this->per_yingfaheji,true);
		$criteria->compare('per_shiye',$this->per_shiye,true);
		$criteria->compare('per_yiliao',$this->per_yiliao,true);
		$criteria->compare('per_yanglao',$this->per_yanglao,true);
		$criteria->compare('per_gongjijin',$this->per_gongjijin,true);
		$criteria->compare('per_daikoushui',$this->per_daikoushui,true);
		$criteria->compare('per_koukuangheji',$this->per_koukuangheji,true);
		$criteria->compare('per_shifaheji',$this->per_shifaheji,true);
		$criteria->compare('com_shiye',$this->com_shiye,true);
		$criteria->compare('com_yiliao',$this->com_yiliao,true);
		$criteria->compare('com_yanglao',$this->com_yanglao,true);
		$criteria->compare('com_gongshang',$this->com_gongshang,true);
		$criteria->compare('com_shengyu',$this->com_shengyu,true);
		$criteria->compare('com_gongjijin',$this->com_gongjijin,true);
		$criteria->compare('com_heji',$this->com_heji,true);
		$criteria->compare('laowufei',$this->laowufei,true);
		$criteria->compare('canbaojin',$this->canbaojin,true);
		$criteria->compare('danganfei',$this->danganfei,true);
		$criteria->compare('paysum_zhongqi',$this->paysum_zhongqi,true);
		$criteria->compare('salary_type',$this->salary_type);
		$criteria->compare('sal_add_json',$this->sal_add_json,true);
		$criteria->compare('sal_del_json',$this->sal_del_json,true);
		$criteria->compare('sal_free_json',$this->sal_free_json,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Salary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
