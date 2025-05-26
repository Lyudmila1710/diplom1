<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property int $type_id
 * @property string $description
 * @property string $photo
 * @property int $cost
 * @property float $weight
 * @property string $created_at
 * @property string $available
 *
 * @property Cart[] $carts
 * @property Type $type
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_id', 'description','cost', 'weight'], 'required'],
            [['type_id', 'cost'], 'integer'],
            [['description', 'available'], 'string'],
            [['weight'], 'number', 'min' => 0.001, 'max' => 999.99, 'tooSmall' => 'Вес должен быть больше 0.001', 'tooBig' => 'Вес не должен превышать 999.99', 'message' => 'Вес должен быть числом. А также через точку (если число нецелое), например: 2.5'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 80],
            [['photo'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::class, 'targetAttribute' => ['type_id' => 'id']],
            ['name', 'match', 'pattern' => '/^[a-zA-ZА-ЯЁа-яё\s\d\.,!?-]{2,}$/u', 'message' => 'Можно использовать только латиницу, кириллицу, цифры, пробел, запятую, точку, восклицательный знак и минимум 2 символа.'],
            ['description', 'match', 'pattern' => '/^[a-zA-ZА-ЯЁа-яё\s\d\.,!?-]{2,}$/u', 'message' => 'Можно использовать только латиницу, кириллицу, цифры, пробел, запятую, точку, восклицательный знак, дефис и минимум 2 символа.'],
            [['photo'], 'file', 
            'extensions' => ['png', 'jpg', 'jpeg', 'gif'], // Указываем разрешенные расширения
            'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'], // Проверка MIME типа
            'skipOnEmpty' => false, 
            'message' => 'Загрузите изображение в формате PNG, JPG, JPEG или GIF',
            'tooBig' => 'Файл слишком большой. Максимальный размер: 5MB.',
            'maxSize' => 5 * 1024 * 1024, 
            'on' => 'create',// Максимальный размер файла 5MB
        ],
     [['photo'], 'file', 
            'extensions' => ['png', 'jpg', 'jpeg', 'gif'], // Указываем разрешенные расширения
            'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'], // Проверка MIME типа
            'skipOnEmpty' => true, 
            'message' => 'Загрузите изображение в формате PNG, JPG, JPEG или GIF',
            'tooBig' => 'Файл слишком большой. Максимальный размер: 5MB.',
            'maxSize' => 5 * 1024 * 1024, 
            'on' => 'update',// Максимальный размер файла 5MB
        ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type_id' => 'Категория',
            'description' => 'Описание',
            'photo' => 'Фотография',
            'cost' => 'Цена(руб.)',
            'weight' => 'Вес(кг)',
            'created_at' => 'Дата создания',
            'available' => 'Наличие',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['id' => 'type_id']);
    }
    public function isFavorite($userId)
{
    if (Yii::$app->user->isGuest) {
        return false;
    }
    
    return Favorite::find()
        ->where(['user_id' => $userId, 'product_id' => $this->id])
        ->exists();
}
}
