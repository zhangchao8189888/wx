<?php

/**
 * This is the model class for table "{{total}}".
 *
 * The followings are the available columns in table '{{total}}':
 * @property integer $id
 * @property integer $salaryTime_id
 * @property string $sum_per_yingfaheji
 * @property string $sum_per_shiye
 * @property string $sum_per_yiliao
 * @property string $sum_per_yanglao
 * @property string $sum_per_gongjijin
 * @property string $sum_per_daikoushui
 * @property string $sum_per_koukuangheji
 * @property string $sum_per_shifaheji
 * @property string $sum_com_shiye
 * @property string $sum_com_yiliao
 * @property string $sum_com_yanglao
 * @property string $sum_com_gongshang
 * @property double $sum_com_shengyu
 * @property double $sum_com_gongjijin
 * @property double $sum_com_heji
 * @property double $sum_laowufei
 * @property double $sum_canbaojin
 * @property double $sum_danganfei
 * @property double $sum_paysum_zhongqi
 */
class Total extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{total}}';
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
			array('sum_com_shengyu, sum_com_gongjijin, sum_com_heji, sum_laowufei, sum_canbaojin, sum_danganfei, sum_paysum_zhongqi', 'numerical'),
			array('sum_per_yingfaheji, sum_per_shiye, sum_per_yiliao, sum_per_yanglao, sum_per_gongjijin, sum_per_daikoushui, sum_per_koukuangheji, sum_per_shifaheji, sum_com_shiye, sum_com_yiliao, sum_com_yanglao, sum_com_gongshang', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, salaryTime_id, sum_per_yingfaheji, sum_per_shiye, sum_per_yiliao, sum_per_yanglao, sum_per_gongjijin, sum_per_daikoushui, sum_per_koukuangheji, sum_per_shifaheji, sum_com_shiye, sum_com_yiliao, sum_com_yanglao, sum_com_gongshang, sum_com_shengyu, sum_com_gongjijin, sum_com_heji, sum_laowufei, sum_canbaojin, sum_danganfei, sum_paysum_zhongqi', 'safe', 'on'=>'search'),
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
			'sum_per_yingfaheji' => 'Sum Per Yingfaheji',
			'sum_per_shiye' => 'Sum Per Shiye',
			'sum_per_yiliao' => 'Sum Per Yiliao',
			'sum_per_yanglao' => 'Sum Per Yanglao',
			'sum_per_gongjijin' => 'Sum Per Gongjijin',
			'sum_per_daikoushui' => 'Sum Per Daikoushui',
			'sum_per_koukuangheji' => 'Sum Per Koukuangheji',
			'sum_per_shifaheji' => 'Sum Per Shifaheji',
			'sum_com_shiye' => 'Sum Com Shiye',
			'sum_com_yiliao' => 'Sum Com Yiliao',
			'sum_com_yanglao' => 'Sum Com Yanglao',
			'sum_com_gongshang' => 'Sum Com Gongshang',
			'sum_com_shengyu' => 'Sum Com Shengyu',
			'sum_com_gongjijin' => 'Sum Com Gongjijin',
			'sum_com_heji' => 'Sum Com Heji',
			'sum_laowufei' => 'Sum Laowufei',
			'sum_canbaojin' => 'Sum Canbaojin',
			'sum_danganfei' => 'Sum Danganfei',
			'sum_paysum_zhongqi' => 'Sum Paysum Zhongqi',
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
		$criteria->compare('sum_per_yingfaheji',$this->sum_per_yingfaheji,true);
		$criteria->compare('sum_per_shiye',$this->sum_per_shiye,true);
		$criteria->compare('sum_per_yiliao',$this->sum_per_yiliao,true);
		$criteria->compare('sum_per_yanglao',$this->sum_per_yanglao,true);
		$criteria->compare('sum_per_gongjijin',$this->sum_per_gongjijin,true);
		$criteria->compare('sum_per_daikoushui',$this->sum_per_daikoushui,true);
		$criteria->compare('sum_per_koukuangheji',$this->sum_per_koukuangheji,true);
		$criteria->compare('sum_per_shifaheji',$this->sum_per_shifaheji,true);
		$criteria->compare('sum_com_shiye',$this->sum_com_shiye,true);
		$criteria->compare('sum_com_yiliao',$this->sum_com_yiliao,true);
		$criteria->compare('sum_com_yanglao',$this->sum_com_yanglao,true);
		$criteria->compare('sum_com_gongshang',$this->sum_com_gongshang,true);
		$criteria->compare('sum_com_shengyu',$this->sum_com_shengyu);
		$criteria->compare('sum_com_gongjijin',$this->sum_com_gongjijin);
		$criteria->compare('sum_com_heji',$this->sum_com_heji);
		$criteria->compare('sum_laowufei',$this->sum_laowufei);
		$criteria->compare('sum_canbaojin',$this->sum_canbaojin);
		$criteria->compare('sum_danganfei',$this->sum_danganfei);
		$criteria->compare('sum_paysum_zhongqi',$this->sum_paysum_zhongqi);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Total the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
