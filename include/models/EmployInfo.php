<?php

/**
 * This is the model class for table "{{employ_info}}".
 *
 * The followings are the available columns in table '{{employ_info}}':
 * @property integer $id
 * @property integer $employ_id
 * @property string $e_num
 * @property string $employ_name
 * @property integer $company_id
 * @property string $company_name
 * @property string $contract_no
 * @property string $department_name
 * @property integer $sex
 * @property string $birthday
 * @property integer $age
 * @property string $nation
 * @property string $native_place
 * @property string $political
 * @property string $education
 * @property string $tel_no
 * @property string $per_address
 * @property string $live_address
 * @property string $urgency_tel_no
 * @property string $urgency_per_name
 * @property string $work_start_time
 * @property string $work_end_time
 * @property string $current_contract_times
 * @property string $edu_school
 * @property string $edu_profession
 * @property string $email
 * @property string $in_charge_name
 * @property string $liangxiren_name
 * @property string $department_tel
 * @property string $use_worker_type
 * @property integer $is_new_social
 * @property integer $is_new_gongjijin
 * @property string $social_add_time
 * @property string $social_sub_time
 * @property string $gongjijin_add_time
 * @property string $gongjijin_sub_time
 * @property string $comment
 * @property integer $op_user_id
 * @property string $op_user_name
 * @property string $c_time
 * @property string $u_time
 * @property string $original_shebaojishu
 * @property string $original_gongjijinjishu
 * @property string $e_type_name
 * @property string $bank_name
 * @property string $bank_no
 */
class EmployInfo extends CActiveRecord
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
			//array('work_start_time, work_end_time, current_contract_times, email, social_add_time, social_sub_time, gongjijin_add_time, gongjijin_sub_time, c_time, u_time, e_type_name, bank_name, bank_no', 'required'),
			array('employ_id, company_id, sex, age, is_new_social, is_new_gongjijin, op_user_id', 'numerical', 'integerOnly'=>true),
			array('e_num, company_name, department_name, current_contract_times', 'length', 'max'=>50),
			array('employ_name, urgency_per_name, in_charge_name, liangxiren_name', 'length', 'max'=>10),
			array('contract_no, tel_no, urgency_tel_no, edu_school, edu_profession, department_tel, bank_name', 'length', 'max'=>30),
			array('nation, native_place, political, education, use_worker_type, op_user_name, e_type_name', 'length', 'max'=>20),
			array('per_address, live_address, email', 'length', 'max'=>100),
			array('comment', 'length', 'max'=>1000),
			array('original_shebaojishu, original_gongjijinjishu', 'length', 'max'=>11),
			array('bank_no', 'length', 'max'=>80),
			array('birthday', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employ_id, e_num, employ_name, company_id, company_name, contract_no, department_name, sex, birthday, age, nation, native_place, political, education, tel_no, per_address, live_address, urgency_tel_no, urgency_per_name, work_start_time, work_end_time, current_contract_times, edu_school, edu_profession, email, in_charge_name, liangxiren_name, department_tel, use_worker_type, is_new_social, is_new_gongjijin, social_add_time, social_sub_time, gongjijin_add_time, gongjijin_sub_time, comment, op_user_id, op_user_name, c_time, u_time, original_shebaojishu, original_gongjijinjishu, e_type_name, bank_name, bank_no', 'safe', 'on'=>'search'),
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
			'e_num' => 'E Num',
			'employ_name' => 'Employ Name',
			'company_id' => 'Company',
			'company_name' => 'Company Name',
			'contract_no' => 'Contract No',
			'department_name' => 'Department Name',
			'sex' => 'Sex',
			'birthday' => 'Birthday',
			'age' => 'Age',
			'nation' => 'Nation',
			'native_place' => 'Native Place',
			'political' => 'Political',
			'education' => 'Education',
			'tel_no' => 'Tel No',
			'per_address' => 'Per Address',
			'live_address' => 'Live Address',
			'urgency_tel_no' => 'Urgency Tel No',
			'urgency_per_name' => 'Urgency Per Name',
			'work_start_time' => 'Work Start Time',
			'work_end_time' => 'Work End Time',
			'current_contract_times' => 'Current Contract Times',
			'edu_school' => 'Edu School',
			'edu_profession' => 'Edu Profession',
			'email' => 'Email',
			'in_charge_name' => 'In Charge Name',
			'liangxiren_name' => 'Liangxiren Name',
			'department_tel' => 'Department Tel',
			'use_worker_type' => 'Use Worker Type',
			'is_new_social' => 'Is New Social',
			'is_new_gongjijin' => 'Is New Gongjijin',
			'social_add_time' => 'Social Add Time',
			'social_sub_time' => 'Social Sub Time',
			'gongjijin_add_time' => 'Gongjijin Add Time',
			'gongjijin_sub_time' => 'Gongjijin Sub Time',
			'comment' => 'Comment',
			'op_user_id' => 'Op User',
			'op_user_name' => 'Op User Name',
			'c_time' => 'C Time',
			'u_time' => 'U Time',
			'original_shebaojishu' => 'Original Shebaojishu',
			'original_gongjijinjishu' => 'Original Gongjijinjishu',
			'e_type_name' => 'E Type Name',
			'bank_name' => 'Bank Name',
			'bank_no' => 'Bank No',
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
		$criteria->compare('e_num',$this->e_num,true);
		$criteria->compare('employ_name',$this->employ_name,true);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('contract_no',$this->contract_no,true);
		$criteria->compare('department_name',$this->department_name,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('age',$this->age);
		$criteria->compare('nation',$this->nation,true);
		$criteria->compare('native_place',$this->native_place,true);
		$criteria->compare('political',$this->political,true);
		$criteria->compare('education',$this->education,true);
		$criteria->compare('tel_no',$this->tel_no,true);
		$criteria->compare('per_address',$this->per_address,true);
		$criteria->compare('live_address',$this->live_address,true);
		$criteria->compare('urgency_tel_no',$this->urgency_tel_no,true);
		$criteria->compare('urgency_per_name',$this->urgency_per_name,true);
		$criteria->compare('work_start_time',$this->work_start_time,true);
		$criteria->compare('work_end_time',$this->work_end_time,true);
		$criteria->compare('current_contract_times',$this->current_contract_times,true);
		$criteria->compare('edu_school',$this->edu_school,true);
		$criteria->compare('edu_profession',$this->edu_profession,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('in_charge_name',$this->in_charge_name,true);
		$criteria->compare('liangxiren_name',$this->liangxiren_name,true);
		$criteria->compare('department_tel',$this->department_tel,true);
		$criteria->compare('use_worker_type',$this->use_worker_type,true);
		$criteria->compare('is_new_social',$this->is_new_social);
		$criteria->compare('is_new_gongjijin',$this->is_new_gongjijin);
		$criteria->compare('social_add_time',$this->social_add_time,true);
		$criteria->compare('social_sub_time',$this->social_sub_time,true);
		$criteria->compare('gongjijin_add_time',$this->gongjijin_add_time,true);
		$criteria->compare('gongjijin_sub_time',$this->gongjijin_sub_time,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('op_user_id',$this->op_user_id);
		$criteria->compare('op_user_name',$this->op_user_name,true);
		$criteria->compare('c_time',$this->c_time,true);
		$criteria->compare('u_time',$this->u_time,true);
		$criteria->compare('original_shebaojishu',$this->original_shebaojishu,true);
		$criteria->compare('original_gongjijinjishu',$this->original_gongjijinjishu,true);
		$criteria->compare('e_type_name',$this->e_type_name,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_no',$this->bank_no,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
