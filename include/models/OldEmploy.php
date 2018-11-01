<?php

/**
 * This is the model class for table "{{employ}}".
 *
 * The followings are the available columns in table '{{employ}}':
 * @property integer $id
 * @property string $e_name
 * @property string $e_company
 * @property string $e_num
 * @property string $bank_name
 * @property string $bank_num
 * @property string $e_type
 * @property integer $shebaojishu
 * @property integer $gongjijinjishu
 * @property integer $laowufei
 * @property integer $canbaojin
 * @property integer $danganfei
 * @property string $memo
 * @property integer $e_state
 * @property integer $e_hetongnian
 * @property string $e_hetong_date
 * @property integer $e_teshu_state
 */
class OldEmploy extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{employ}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('e_company, e_num, bank_name, bank_num, e_type, e_hetong_date', 'required'),
			array('shebaojishu, gongjijinjishu, laowufei, canbaojin, danganfei, e_state, e_hetongnian, e_teshu_state', 'numerical', 'integerOnly'=>true),
			array('e_name', 'length', 'max'=>20),
			array('e_company, e_num, bank_num, e_type', 'length', 'max'=>40),
			array('bank_name', 'length', 'max'=>50),
			array('memo', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, e_name, e_company, e_num, bank_name, bank_num, e_type, shebaojishu, gongjijinjishu, laowufei, canbaojin, danganfei, memo, e_state, e_hetongnian, e_hetong_date, e_teshu_state', 'safe', 'on'=>'search'),
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
			'e_name' => 'E Name',
			'e_company' => 'E Company',
			'e_num' => 'E Num',
			'bank_name' => 'Bank Name',
			'bank_num' => 'Bank Num',
			'e_type' => 'E Type',
			'shebaojishu' => 'Shebaojishu',
			'gongjijinjishu' => 'Gongjijinjishu',
			'laowufei' => 'Laowufei',
			'canbaojin' => 'Canbaojin',
			'danganfei' => 'Danganfei',
			'memo' => 'Memo',
			'e_state' => 'E State',
			'e_hetongnian' => 'E Hetongnian',
			'e_hetong_date' => 'E Hetong Date',
			'e_teshu_state' => 'E Teshu State',
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
		$criteria->compare('e_name',$this->e_name,true);
		$criteria->compare('e_company',$this->e_company,true);
		$criteria->compare('e_num',$this->e_num,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_num',$this->bank_num,true);
		$criteria->compare('e_type',$this->e_type,true);
		$criteria->compare('shebaojishu',$this->shebaojishu);
		$criteria->compare('gongjijinjishu',$this->gongjijinjishu);
		$criteria->compare('laowufei',$this->laowufei);
		$criteria->compare('canbaojin',$this->canbaojin);
		$criteria->compare('danganfei',$this->danganfei);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('e_state',$this->e_state);
		$criteria->compare('e_hetongnian',$this->e_hetongnian);
		$criteria->compare('e_hetong_date',$this->e_hetong_date,true);
		$criteria->compare('e_teshu_state',$this->e_teshu_state);

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
	 * @return OldEmploy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
