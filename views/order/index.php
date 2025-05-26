<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

if (Yii::$app->user->identity->isAdmin()) {
    $this->title = 'Заказы';
} else {
    $this->title = 'Мои заказы';
}
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/order_index.css');
$this->registerJsFile('@web/js/order.js', [
    'depends' => [\yii\web\JqueryAsset::class, \yii\bootstrap5\BootstrapAsset::class]
]);
?>

<div class="order-page">
    <h1 class="order-title"><?= Html::encode($this->title) ?></h1>
     <div class="header-divider"></div>
     <div class="filter-sort-container">
        <!-- Фильтр по статусам -->
        <div class="status-dropdown">
            <button class="status-dropbtn">
                <?= empty($searchModel->status) ? 'Все статусы' : Html::encode($searchModel->status) ?>
            </button>
            <div class="status-dropdown-content">
                <div class="status-option <?= empty($searchModel->status) ? 'active' : '' ?>" data-value="">Все статусы</div>
                <div class="status-option <?= $searchModel->status == 'Новый' ? 'active' : '' ?>" data-value="Новый">Новый</div>
                <div class="status-option <?= $searchModel->status == 'В процессе' ? 'active' : '' ?>" data-value="В процессе">В процессе</div>
                <div class="status-option <?= $searchModel->status == 'Подтвержден' ? 'active' : '' ?>" data-value="Подтвержден">Подтвержден</div>
                <div class="status-option <?= $searchModel->status == 'Доставлен' ? 'active' : '' ?>" data-value="Доставлен">Доставлен</div>
                <div class="status-option <?= $searchModel->status == 'Отменён' ? 'active' : '' ?>" data-value="Отменён">Отменён</div>
            </div>
            
            <!-- Скрытое поле формы для отправки значения -->
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['order/index'],
                'options' => ['class' => 'filter-form']
            ]); ?>
            <?= $form->field($searchModel, 'status')->hiddenInput(['id' => 'status-filter-value'])->label(false) ?>
            <?php ActiveForm::end(); ?>
        </div>
        
        <!-- Сортировка -->
        <div class="sort-dropdown">
            <button class="sort-dropbtn">
            <?php
            $sort = Yii::$app->request->get('sort');
            $sortLabels = [
                '' => 'По умолчанию',
                '+id' => '№ заказа (по возрастанию)',
                '-id' => '№ заказа (по убыванию)',
                'date_delivery' => 'Дата доставки (по возрастанию)',
                '-date_delivery' => 'Дата доставки (по убыванию)'
            ];
            echo $sortLabels[$sort] ?? 'По умолчанию';
            ?>
        </button>
            <div class="sort-dropdown-content">
            <div class="sort-option <?= empty($sort) ? 'active' : '' ?>" data-sort="">По умолчанию</div>
            <div class="sort-option <?= $sort === '+id' ? 'active' : '' ?>" data-sort="+id">№ заказа (по возрастанию)</div>
            <div class="sort-option <?= $sort === '-id' ? 'active' : '' ?>" data-sort="-id">№ заказа (по убыванию)</div>
            <div class="sort-option <?= $sort === 'date_delivery' ? 'active' : '' ?>" data-sort="date_delivery">Дата доставки (по возрастанию)</div>
            <div class="sort-option <?= $sort === '-date_delivery' ? 'active' : '' ?>" data-sort="-date_delivery">Дата доставки (по убыванию)</div>
        </div>
        </div>
    </div>
   

    <?php if (Yii::$app->session->hasFlash('orderSuccess')): ?>
        <?php
        $this->registerJs("$(document).ready(function() {
            $('#order-success-modal').modal('show');
        });");
        ?>
    <?php endif; ?>

    <div class="order-cards">
       <?php foreach ($dataProvider->models as $order): ?>
            <div class="order-card">
            <?php
// Преобразуем статус к нижнему регистру и заменяем пробелы на дефисы
$statusClass = mb_strtolower(str_replace(' ', '-', $order->status));
?>
    <div class="order-status status-<?= $statusClass ?>">
    <?= Html::encode($order->status) ?>
</div>

    <div class="order-header">
        <div class="order-id">Заказ №<?= $order->id ?></div>
    </div>

    <div class="order-body">
                    <p><strong>Дата доставки:</strong> <?= Html::encode($order->date_delivery) ?></p>
                    <p><strong>Телефон:</strong> <?= Html::encode($order->phone) ?></p>
                    <p><strong>Адрес:</strong> <?= Html::encode($order->address) ?></p>
                    <?php if (!empty($order->comments)): ?>
                        <p><strong>Комментарий:</strong> <span class="order-comment"><?= Html::encode($order->comments) ?></span></p>
                    <?php endif; ?>
                    <p><strong>Позиций в заказе:</strong> <?= $order->itemsCount ?></p>
<p><strong>Общая стоимость:</strong> <?= number_format($order->totalSum, 2, ',', ' ') ?> руб.</p>
                </div>

                <div class="order-footer">
        <div class="order-footer-left">
            <?= Yii::$app->formatter->asDate($order->created_at, 'php:d.m.Y') ?>
        </div>
        <div class="order-footer-right">
            <?= Html::a('Подробнее', ['view', 'id' => $order->id], ['class' => 'btn-details']) ?>
        </div>
    </div>
            </div>
        <?php endforeach; ?>
    </div>

  <div class="pagination-container">
    <?= \yii\bootstrap5\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'pagination'],
        'linkOptions' => ['class' => 'page-link'],
        'prevPageLabel' => 'Предыдущая',
        'nextPageLabel' => 'Следующая',
    ]) ?>
</div>
</div>

<!-- Модальное окно -->
<div class="modal fade" id="order-success-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-thank">
            <div class="modal-header">
                <h5 class="modal-title">Спасибо за заказ!</h5>
            </div>
            <div class="modal-body text-center">
                <div class="check-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4>Пусть каждая сладость от Sirius зажжёт в душе маленькую звёздочку тепла и радости!</h4>
                <p>Мы уже начали его обработку</p>
                <img src="/images/thank.png" alt="спасибо за заказ" class="thank-image"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-orders" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>