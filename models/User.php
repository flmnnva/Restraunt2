<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $password
 * @property int $role_id
 *
 * @property Bookings[] $bookings
 * @property Roles $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{   public function __toString()
{
    return $this->login;
}
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'password', 'role_id'], 'required'],
            [['role_id'], 'integer'],
            [['name', 'surname', 'email', 'password'], 'string', 'max' => 255],
            ['email','email'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'password' => 'Password',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::class, ['id' => 'role_id']);
    }
    public static function login($login, $password){
        $user=static::find()->where(['email'=>$login])->one();
        if($user && $user ->validatePassword($password)){
            return $user;
        }
        return null;
    }
    public static function getInstance()
    {
        return Yii::$app->user->identity;
    }
   public function validatePassword($password)
   {
       return $this->password === $password;
   }
       /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return  static::find()->where(['id'=>$id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($login)
    {
        return self::findOne(['email' => $login]);
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
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }
    public function isAdmin()
    {
        return $this->role_id= Role::ADMIN_ROLE_ID;
    }
}
