<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property string $e_num
 * @property string $name
 * @property string $password
 * @property string $last_login_time
 * @property integer $user_type
 * @property string $create_time
 * @property string $memo
 * @property integer $del_flag
 */
class OldUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, company_id, user_type, del_flag', 'numerical', 'integerOnly'=>true),
			array('e_num, name', 'length', 'max'=>50),
			array('password', 'length', 'max'=>20),
			array('memo', 'length', 'max'=>200),
			array('last_login_time, create_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, company_id, e_num, name, password, last_login_time, user_type, create_time, memo, del_flag', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'company_id' => 'Company',
			'e_num' => 'E Num',
			'name' => 'Name',
			'password' => 'Password',
			'last_login_time' => 'Last Login Time',
			'user_type' => 'User Type',
			'create_time' => 'Create Time',
			'memo' => 'Memo',
			'del_flag' => 'Del Flag',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('e_num',$this->e_num,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('user_type',$this->user_type);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('del_flag',$this->del_flag);

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
	 * @return OldUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}