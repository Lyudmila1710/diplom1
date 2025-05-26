<?php

namespace app\models;
use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form of `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['phone', 'address', 'date_delivery', 'comments', 'status', 'payment'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
   public function search($params)
{
    $userId = \Yii::$app->user->id;
    $isAdmin = \Yii::$app->user->identity->isAdmin();

    if ($isAdmin) {
        // Админ видит все заказы
        $query = Order::find();
    } else {
        // Обычный пользователь — только свои заказы
        $orderIds = (new \yii\db\Query())
            ->select('order_id')
            ->from('cart')
            ->where(['user_id' => $userId])
            ->andWhere(['not', ['order_id' => null]])
            ->distinct()
            ->column();

        $query = Order::find()->where(['id' => $orderIds]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => ['id' => SORT_DESC],
       'attributes' => [
                    'id',
                    'date_delivery',
                    'status',
                    'created_at'
                ] ],
        'pagination' => [
            'pageSize' => 5,
        ],
    ]);
// Добавляем обработку параметров сортировки из URL
    $sort = Yii::$app->request->get('sort');
    $direction = Yii::$app->request->get('direction');
    
    if ($sort && $direction) {
        $dataProvider->sort->defaultOrder = [
            $sort => ($direction === 'asc') ? SORT_ASC : SORT_DESC
        ];
    }
    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere([
        'id' => $this->id,
        'date_delivery' => $this->date_delivery,
    ]);

    $query->andFilterWhere(['like', 'phone', $this->phone])
        ->andFilterWhere(['like', 'address', $this->address])
        ->andFilterWhere(['like', 'comments', $this->comments])
        ->andFilterWhere(['like', 'status', $this->status])
        ->andFilterWhere(['like', 'payment', $this->payment]);

    return $dataProvider;
}
}
