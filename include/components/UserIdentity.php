<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        $userFromDB = OAUser::model()->find('name=?', array(strtolower($this->username)));

        if (!isset($this->username) || null === $userFromDB) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!isset($this->password) || null === $userFromDB) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif ($userFromDB->password === md5($this->password)) {
            $this->username = $userFromDB->username;
            $this->_id = $userFromDB->id;

            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    //必须返回id，不能返回usrName
    public function getId() {
        return $this->_id;
    }
}