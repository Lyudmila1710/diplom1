<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $phone
 * @property string $address
 * @property string $date_delivery
 * @property string|null $comments
 * @property string $status
 * @property string $payment
 * @property string $created_at
 *
 * @property Cart[] $carts
 * @property Rejection[] $rejections
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'address', 'date_delivery'], 'required'],
            [['date_delivery'], 'safe'],
            [['comments', 'status', 'payment'], 'string'],
            [['phone'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 80],
             ['comments', 'match', 'pattern' => '/^[a-zA-ZА-ЯЁа-яё\s\d\.,!?-]{2,}$/u', 'message' => 'Можно использовать только латиницу, кириллицу, цифры, пробел, запятую, точку, восклицательный знак, дефис и минимум 2 символа.'],
           ['phone', 'match', 'pattern' => '/^\+7\(\d{3}\)\-\d{3}\-\d{2}\-\d{2}$/', 'message'=>'Номер телефона в формате +7(473)374-34-76 '],
            ['address', 'match', 'pattern' => '/^[a-zA-ZА-ЯЁа-яё\s\d\.,-]{2,}$/u', 'message' => 'Можно использовать только латиницу, кириллицу, цифры, пробел, запятую, точку, дефис и минимум 2 символа.'],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон получателя',
            'address' => 'Адресс получателя',
            'date_delivery' => 'Дата доставки',
            'comments' => 'Комментарий к заказу',
            'status' => 'Статус заказа',
            'payment' => 'Оплата',
            'created_at' => 'Создано',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Rejections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRejections()
    {
        return $this->hasMany(Rejection::class, ['order_id' => 'id']);
    }
    public function getItemsCount()
{
    return Cart::find()
        ->where(['order_id' => $this->id])
        ->count();
}

public function getTotalSum()
{
    $items = Cart::find()
        ->where(['order_id' => $this->id])
        ->with('product') 
        ->all();

    $total = 0;
    foreach ($items as $item) {
        $total += $item->product->cost * $item->count;
    }

    return $total;
}
}
