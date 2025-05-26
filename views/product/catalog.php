<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Cart;

/** @var yii\web\View $this */
/** @var app\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Каталог Sirius';
$this->registerCssFile('@web/css/catalog.css');
$this->registerJsFile('@web/js/catalog.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="sirius-catalog">
    <!-- Верхняя строка с заголовком и поиском -->
    <div class="top-header-row">
        <!-- Пустой блок для балансировки -->
        <div class="header-balancer"></div>
        
        <!-- Основной заголовок по центру -->
        <div class="catalog-header">
            <h1 class="catalog-title"><?= Html::encode($this->title) ?></h1>
            <div class="header-divider"></div>
            <p class="catalog-subtitle">Изысканные кондитерские изделия ручной работы</p>
        </div>
        
        <!-- Поиск справа -->
        <div class="search-container">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['product/catalog'],
                'options' => ['class' => 'search-form']
            ]); ?>
            
           <div class="search-input-container">
    
    <?= $form->field($searchModel, 'name', [
        'options' => ['class' => 'search-group']
    ])->textInput([
        'placeholder' => 'Поиск...',
        'class' => 'search-input',
        'autocomplete' => 'off',
        'id' => 'product-search',
        'value' => Yii::$app->request->get('ProductSearch')['name'] ?? ''
    ])->label(false) ?>

    <button type="button" class="search-clear" style="<?= empty(Yii::$app->request->get('ProductSearch')['name']) ? 'display:none;' : '' ?>">
       
    </button>

    <button type="submit" class="search-button">
        <i class="fas fa-search"></i>
    </button>
</div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<div class="filter-sort-container">
    <!-- Фильтр по категориям -->
    <div class="category-dropdown">
        <button class="category-dropbtn">
            
           Все категории
            
        </button>
        <div class="category-dropdown-content">
            <div class="category-option" data-value="">Все категории</div>
            <?php foreach (\app\models\Type::find()->all() as $type): ?>
                <div class="category-option" data-value="<?= $type->id ?>">
                    <?= Html::encode($type->name) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Скрытое поле формы для отправки значения -->
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['product/catalog'],
            'options' => ['class' => 'filter-form']
        ]); ?>
        <?= $form->field($searchModel, 'type_id')->hiddenInput(['id' => 'category-filter-value'])->label(false) ?>
        <?php ActiveForm::end(); ?>
    </div>
    
    <!-- Сортировка (оставляем как есть) -->
<div class="sort-dropdown">
    <button class="sort-dropbtn">
        <?php
        $sort = Yii::$app->request->get('sort');
        $sortLabels = [
            '' => 'По умолчанию',
            'cost' => 'Цена по возрастанию',
            '-cost' => 'Цена по убыванию',
            'name' => 'Название (А-Я)',
            '-name' => 'Название (Я-А)',
            '-created_at' => 'Сначала новые',
            'created_at' => 'Сначала старые'
        ];
        echo $sortLabels[$sort] ?? 'По умолчанию';
        ?>
    </button>
    <div class="sort-dropdown-content">
        <div class="sort-option <?= empty($sort) ? 'active' : '' ?>" data-sort="">По умолчанию</div>
        <div class="sort-option <?= $sort === 'cost' ? 'active' : '' ?>" data-sort="cost">Цена по возрастанию</div>
        <div class="sort-option <?= $sort === '-cost' ? 'active' : '' ?>" data-sort="-cost">Цена по убыванию</div>
        <div class="sort-option <?= $sort === 'name' ? 'active' : '' ?>" data-sort="name">Название (А-Я)</div>
        <div class="sort-option <?= $sort === '-name' ? 'active' : '' ?>" data-sort="-name">Название (Я-А)</div>
        <div class="sort-option <?= $sort === '-created_at' ? 'active' : '' ?>" data-sort="-created_at">Сначала новые</div>
        <div class="sort-option <?= $sort === 'created_at' ? 'active' : '' ?>" data-sort="created_at">Сначала старые</div>
    </div>
</div>
</div>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
        <div class="admin-create-button">
            <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-gold']) ?>
        </div>
    <?php endif; ?>

    <div class="product-grid">
        <?php if (count($dataProvider->getModels()) > 0): ?>
            <?php foreach ($dataProvider->getModels() as $product): ?>
                <?php if ($product->available): ?>
           <div class="product-card clickable-card" data-url="<?= Url::to(['view', 'id' => $product->id]) ?>">
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
        <div class="availability-badge <?= $product->available === 'Доступен' ? 'in-stock' : 'out-stock' ?>">
            <?= $product->available === 'Доступен' ? 'В наличии' : 'Нет в наличии' ?>
        </div>
    <?php endif; ?>
    
   <?php if(!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
    <button class="favorite-heart <?= $product->isFavorite(Yii::$app->user->id) ? 'active' : '' ?>" 
            data-product-id="<?= $product->id ?>">
        <i class="fas fa-heart"></i>
    </button>
<?php endif; ?>
     <div class="product-image">
                        <?= Html::img($product->photo, [
                            'alt' => Html::encode($product->name),
                            'class' => 'product-img'
                        ]) ?>                  
                    </div>
                    
                    <div class="product-content">
                        <div class="product-type"><?= $product->type->name ?? '' ?></div>
                        <h3 class="product-name"><?= Html::encode($product->name) ?></h3>
                        
                        <div class="product-meta">
                            <span class="product-weight"><?= $product->weight ?> кг</span>
                            <span class="product-price"><?= number_format($product->cost, 0, '', ' ') ?> ₽</span>
                        </div>
                    </div>
                    
        <div class="product-actions">
    <?= Html::a('Подробнее', ['view', 'id' => $product->id], [
        'class' => 'btn btn-details'
    ]) ?>
    <?php if(!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()){ 
        $cartItem = Cart::findOne([
            'user_id' => Yii::$app->user->id, 
            'product_id' => $product->id, 
            'order_id' => null
        ]);
        if ($cartItem): ?>
            <div class="quantity-control" data-product-id="<?= $product->id ?>">
                <button class="quantity-btn minus" type="button">-</button>
                <span class="quantity"><?= $cartItem->count ?></span>
                <button class="quantity-btn plus" type="button">+</button>
            </div>
        <?php else: ?>
            <button class="btn btn-cart add-to-cart" data-product-id="<?= $product->id ?>" type="button">
                В корзину
            </button>
        <?php endif; 
    } ?>
</div>
</div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products-message">
                <p>Товары не найдены. Попробуйте изменить параметры поиска.</p>
            </div>
        <?php endif; ?>
    </div>
<div class="pagination-container">
    <?= \yii\bootstrap5\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'pagination'],
        'linkOptions' => ['class' => 'page-link'], // Применяем ваш класс для ссылок
        'prevPageLabel' => 'Предыдущая',
        'nextPageLabel' => 'Следующая',
    ]) ?>
</div>
</div>