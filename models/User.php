<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property string $confirm_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property string $ava
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_WAIT = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_ADMIN = 3;

    public $statuses = [
        self::STATUS_BLOCKED => 'Blocked',
        self::STATUS_WAIT => 'Wait',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_ADMIN => 'Admin',
    ];

    const SCENARIO_USER_CREATE = 'userCreate';
    const SCENARIO_USER_UPDATE = 'userUpdate';

    public $passwordNew;
    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','email'], 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            [['username','email'], 'unique'],
            [['username','email','name'], 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys($this->statuses)],
            [['passwordNew', 'passwordRepeat'], 'required', 'on' => self::SCENARIO_USER_CREATE],
            ['passwordNew', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'passwordNew'],
        ];
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }


    //// Confirm ////
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->confirm_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne(['confirm_token' => $token]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($confirm_token)
    {
        return static::findOne(['confirm_token' => $confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Removes passwordReset/email confirmation token
     */
    public function removeConfirmToken()
    {
        $this->confirm_token = null;
    }
    //// Confirm ////


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    //////// IdentityInterface ////////
    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    //////// IdentityInterface ////////


    /**
     * @return string ava src
     */
    public function getAva()
    {
        $path = Yii::getAlias('@webroot/ava/') . $this->username . '.jpg';
        $src = file_exists($path)
            ? $this->username
            : 'anon';
        return "/ava/$src.jpg";
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLinks()
	{
		return $this->hasMany(Link::className(), ['user_id' => 'id'])->inverseOf('user');
	}

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Из метода signUp убрали generateAuthKey, и добавили в beforeSave(if isInsert) что бы админ мог и сам регать юзеров
     * @inheritdoc
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }
}
