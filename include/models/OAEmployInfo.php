<?php

/**
 * This is the model class for table "{{employ_info}}".
 *
 * The followings are the available columns in table '{{employ_info}}':
 * @property integer $id
 * @property integer $employ_id
 * @property string $e_jiguan
 * @property string $e_bank_no
 * @property string $e_birthday
 * @property string $e_hukou
 * @property integer $e_is_marriage
 * @property string $e_county
 * @property integer $e_zhengzmianmao
 * @property integer $e_education_best
 * @property string $e_word_start_day
 * @property string $e_dangan_bianhao
 * @property string $e_yuangong_bianhao
 * @property string $e_shebao_no
 * @property integer $e_sex
 * @property string $e_gongzuodi
 * @property string $e_minzu
 * @property string $e_biye_xuexiao
 * @property string $add_time
 */
class OAEmployInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{employ_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, employ_id, e_birthday, e_is_marriage, e_zhengzmianmao, e_education_best, e_word_start_day, e_sex, add_time', 'required'),
			array('id, employ_id, e_is_marriage, e_zhengzmianmao, e_education_best, e_sex', 'numerical', 'integerOnly'=>true),
			array('e_jiguan, e_hukou', 'length', 'max'=>100),
			array('e_bank_no, e_biye_xuexiao', 'length', 'max'=>30),
			array('e_county, e_minzu', 'length', 'max'=>10),
			array('e_dangan_bianhao, e_yuangong_bianhao, e_shebao_no', 'length', 'max'=>20),
			array('e_gongzuodi', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employ_id, e_jiguan, e_bank_no, e_birthday, e_hukou, e_is_marriage, e_county, e_zhengzmianmao, e_education_best, e_word_start_day, e_dangan_bianhao, e_yuangong_bianhao, e_shebao_no, e_sex, e_gongzuodi, e_minzu, e_biye_xuexiao, add_time', 'safe', 'on'=>'search'),
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
			'employ_id' => 'Employ',
			'e_jiguan' => 'E Jiguan',
			'e_bank_no' => 'E Bank No',
			'e_birthday' => 'E Birthday',
			'e_hukou' => 'E Hukou',
			'e_is_marriage' => 'E Is Marriage',
			'e_county' => 'E County',
			'e_zhengzmianmao' => 'E Zhengzmianmao',
			'e_education_best' => 'E Education Best',
			'e_word_start_day' => 'E Word Start Day',
			'e_dangan_bianhao' => 'E Dangan Bianhao',
			'e_yuangong_bianhao' => 'E Yuangong Bianhao',
			'e_shebao_no' => 'E Shebao No',
			'e_sex' => 'E Sex',
			'e_gongzuodi' => 'E Gongzuodi',
			'e_minzu' => 'E Minzu',
			'e_biye_xuexiao' => 'E Biye Xuexiao',
			'add_time' => 'Add Time',
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
		$criteria->compare('employ_id',$this->employ_id);
		$criteria->compare('e_jiguan',$this->e_jiguan,true);
		$criteria->compare('e_bank_no',$this->e_bank_no,true);
		$criteria->compare('e_birthday',$this->e_birthday,true);
		$criteria->compare('e_hukou',$this->e_hukou,true);
		$criteria->compare('e_is_marriage',$this->e_is_marriage);
		$criteria->compare('e_county',$this->e_county,true);
		$criteria->compare('e_zhengzmianmao',$this->e_zhengzmianmao);
		$criteria->compare('e_education_best',$this->e_education_best);
		$criteria->compare('e_word_start_day',$this->e_word_start_day,true);
		$criteria->compare('e_dangan_bianhao',$this->e_dangan_bianhao,true);
		$criteria->compare('e_yuangong_bianhao',$this->e_yuangong_bianhao,true);
		$criteria->compare('e_shebao_no',$this->e_shebao_no,true);
		$criteria->compare('e_sex',$this->e_sex);
		$criteria->compare('e_gongzuodi',$this->e_gongzuodi,true);
		$criteria->compare('e_minzu',$this->e_minzu,true);
		$criteria->compare('e_biye_xuexiao',$this->e_biye_xuexiao,true);
		$criteria->compare('add_time',$this->add_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OAEmployInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
