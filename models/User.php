<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
  //это лишнее так ка поля получаем из БД
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    public static function tableName()
    {
      return 'user';
    }

// вызывалось как self:: т к статика
// будем менять т к сделаем обращение уже в БД. А не из кода данные будем брать

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
     //ищем пользователя по имени
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // return $this->password === $password;
        //хешируем пароль
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function generateAuthKey()
    {
      $this->auth_key = \Yii::$app->security->generateRandomString();
    }
}
