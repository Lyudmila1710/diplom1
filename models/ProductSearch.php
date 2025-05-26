<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;
use yii;
/**
 * ProductSearch represents the model behind the search form of `app\models\Product`.
 */
class ProductSearch extends Product
{ public $price_min;
    public $price_max;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'cost'], 'integer'],
            [['name', 'description', 'photo', 'created_at', 'available'], 'safe'],
            [['weight', 'cost', 'price_min', 'price_max'], 'number'],
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
    $query = Product::find()->joinWith('type');

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => 8,
        ],
        'sort' => [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
            'attributes' => [
                'name',
                'cost',
                'created_at',
                'type.name' => [
                    'asc' => ['type.name' => SORT_ASC],
                    'desc' => ['type.name' => SORT_DESC],
                ]
            ]
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

    // Остальной код метода search
    $this->load($params);
    
    if (!$this->validate()) {
        return $dataProvider;
    }

    // Фильтрация по имени продукта и категории
    if (!empty($this->name)) {
        $query->andFilterWhere([
            'or',
            ['like', 'product.name', $this->name], // Фильтрация по полю product.name
            ['like', 'type.name', $this->name] // Фильтрация по полю type.name
        ]);
    }

    // Фильтрация по типу
    if (!empty($this->type_id)) {
        $query->andFilterWhere(['product.type_id' => $this->type_id]);
    }

    // Фильтрация по остальным полям
    $query->andFilterWhere([
        'id' => $this->id,
        'type_id' => $this->type_id,
        'cost' => $this->cost,
        'weight' => $this->weight,
        'created_at' => $this->created_at,
    ]);

    // Фильтрация по цене (диапазон)
    if ($this->price_min !== null) {
        $query->andFilterWhere(['>=', 'cost', $this->price_min]);
    }
    if ($this->price_max !== null) {
        $query->andFilterWhere(['<=', 'cost', $this->price_max]);
    }

    // Фильтрация по остальным полям (описание, фото, доступность)
    $query->andFilterWhere(['like', 'description', $this->description])
          ->andFilterWhere(['like', 'photo', $this->photo])
          ->andFilterWhere(['like', 'available', $this->available]);

    return $dataProvider;
}
}
