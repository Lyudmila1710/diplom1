<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $username
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string $admin
 *  @property string $password_reset_token || null
 *
 * @property Cart[] $carts
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{public $password_repeat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'username', 'phone', 'email', 'password'], 'required'],
            [['admin'], 'string'],
            [['name', 'surname', 'username', 'email', 'password'], 'string', 'max' => 80],
            [['phone'], 'string', 'max' => 20],
            [['username'], 'unique'],
             [[ 'password_repeat'], 'required','on' => [ 'reset']],
            ['email', 'email','on' => ['register', 'update','reset']],
           ['username', 'match', 'pattern' => '/^[a-zA-Z\_\-\*\!\d]{4,}$/', 'message'=>'Можно использовать только латиницу, цифры, _-*! и минимум 4 символа ','on' => [ 'register','update']],
          [['name','surname'], 'match', 'pattern' => '/^[a-zA-ZА-ЯЁа-яё]{2,}$/u', 'message'=>'Можно использовать только латиницу,кириллицу и минимум 2 символа ','on' => ['register', 'update']],
          ['password', 'match', 
          'pattern' => '/^(?=.*\d)(?=.*[a-zа-яё])(?=.*[A-ZА-ЯЁ])(?=.*[!\-+=*.,])[a-zA-Zа-яёА-ЯЁ\d!\-+=*.,]{7,}$/u',
          'message' => 'Требуется: 1 цифра, 1 заглавная и 1 строчная буква, 1 спецсимвол (!-+=*.,), минимум 7 символов',  'on' => ['register', 'reset']
      ],
      ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message'=>'Пароли должны совпадать',  'on' => ['reset']],
        ['phone', 'match', 'pattern' => '/^\+7\(\d{3}\)\-\d{3}\-\d{2}\-\d{2}$/', 'message'=>'Номер телефона в формате +7(473)374-34-76 ',  'on' => ['register', 'update']],
    ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'username' => 'Логин',
            'phone' => 'Телефон',
            'email' => 'Почта',
            'password' => 'Пароль',
            'admin' => 'Админ',
            'password_repeat'=>'Повтор пароля',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['user_id' => 'id']);
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
       
    }

    public function validateAuthKey($authKey)
    {
        
    }

    public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if ($this->isNewRecord || $this->isAttributeChanged('password')) {
            // Если это SHA-256 хеш 
            if (preg_match('/^[a-f0-9]{64}$/i', $this->password)) {
                // Дополнительно хешируем через bcrypt
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            } else {
                // Хешируем обычный пароль: SHA-256 -> bcrypt
                $sha256 = hash('sha256', $this->password);
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($sha256);
            }
        }
    }
    return true;
}

    public function validatePassword($password)
    {

            if (preg_match('/^[a-f0-9]{64}$/i', $password)) {
                return Yii::$app->getSecurity()->validatePassword($password, $this->password);
            }
            // Если ввод - обычный пароль, сначала хешируем в SHA-256
            $hashedInput = hash('sha256', $password);
            return Yii::$app->getSecurity()->validatePassword($hashedInput, $this->password); 
        
        return $this->password === $password;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function isAdmin()
    {
        return boolval(Yii::$app->user->identity->admin==='Админ');
    }
    public function getFavorites()
{
    return $this->hasMany(Favorite::class, ['user_id' => 'id']);
}

//забыл пароль


public static function findByResetToken($token)
{
    if (!static::isResetTokenValid($token)) {
        return null;
    }

    return static::findOne(['password_reset_token' => $token]);
}

public static function isResetTokenValid($token)
{
    if (empty($token)) {
        return false;
    }

    $timestamp = (int) substr($token, strrpos($token, '_') + 1);
    $expire = 3600; // 1 час
    return $timestamp + $expire >= time();
}

public function resetPassword($password)
{
    $this->password = $password; // Автоматически хешируется в beforeSave
    $this->password_reset_token = null;
    return $this->save();
}
public function sendPasswordResetEmail()
{
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl([
        'user/reset-password', 
        'token' => $this->password_reset_token
    ]);
    
    $subject = 'Восстановление пароля на сайте Sirius';
    $message = "<html>
    <head>
    <meta charset='UTF-8'>
        <title>Sirius</title>
        <style type='text/css'> 
        *{ 
       background-color:rgb(97, 37, 2);
       padding-left: 2%; 
       } 
        </style>    
    </head>
    <body>
    <div>
    <h1>Восстановление пароля на сайте кондитерской Sirius</h1>
    <div>Для сброса пароля перейдите по ссылке:
    <a href='$resetLink'>Сбросить пароль</a></div>
    <div>Если вы не запрашивали сброс пароля, проигнорируйте это письмо.</div>
    <div>С уважением, администрация</div>
    </div>
    </body>
    </html>";
    
    $headers = "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: Sirius <infoSirius@mail.ru>\r\n";
    
    return mail($this->email, $subject, $message, $headers);
}
    public function generatePasswordResetToken()
{
    $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
}
}