<?php

namespace app\models;

use yii\base\NotSupportedException;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property integer $id_user
 * @property string $user_name
 * @property string $password_hash
 * @property string $email
 */

class User extends ActiveRecord  implements IdentityInterface
{
    public $confirmPassword;

    public static function tableName()
    {
        return 'Users';
    }

    public function rules()
    {
        return [
            [['user_name', 'email', 'password_hash', 'confirmPassword'], 'required'],
            [['user_name', 'password_hash', 'email', 'confirmPassword'], 'trim'],
            ['user_name', 'string', 'min' => 4, 'max' => 12],
            ['password_hash', 'string', 'min' => 6, 'max' => 14],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'Этот адрес почты уже используется'],
            ['user_name', 'unique', 'message' => 'Это имя уже используется'],
            ['confirmPassword', 'comparePasswords'],
//            ['confirmPassword', 'compare',
//              'compareValue' => 'password_hash', 'message' => 'Пароли не совпадают']
//              @todo Разобраться, почему не хочет работать валидатор
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_name' => 'Имя пользователя',
            'password_hash' => 'Пароль',
            'email' => 'Почта',
            'confirmPassword' => 'Подтверждение пароля'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (is_numeric($id)) {
            return static::findOne($id);
        }
        return static::findByUsername($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['user_name' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
            return true;
        }
        return false;
    }

    public function comparePasswords($attribute, $params)
    {
        if ($this->password_hash != $this->confirmPassword) {
            $this->addError($attribute, 'Пароли не совпадают');
            return false;
        }
        return true;
    }
}
