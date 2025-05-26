<?php
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Детали заказа #' . $model->id;
$this->registerCssFile('@web/css/order_view.css');
$rejection = \app\models\Rejection::findOne(['order_id' => $model->id]);

// Доступные статусы для смены
$statuses = [
    'В процессе' => 'В процессе',
    'Доставлен' => 'Доставлен',
    'Подтверждён' => 'Подтверждён',
    'Отменён' => 'Отменён'
];
?>

<div class="order-details-container">

   <div class="order-header-container">
   <div class="back">
    <?= Html::a(' Назад', ['order/index'], ['class' => 'btn back-button']) ?>
</div>
    <div class="d-flex align-items-center">
        <h1 class="order-header">Детали заказа #<?= Html::encode($model->id) ?></h1>
        <div class="order-status-badge ms-3"><?= Html::encode($model->status) ?></div>
    </div>
    <?php if (Yii::$app->user->identity->isAdmin() && !in_array($model->status, ['Отменён', 'Доставлен'])): ?>
        <button class="btn change-status-btn" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
            Сменить статус
        </button>
    <?php endif; ?>
</div>

    <div class="order-card">
        <div class="order-info-section">
            <h3 class="section-title">Информация о заказе</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Телефон получателя:</span>
                    <span class="info-value"><?= Html::encode($model->phone) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Адрес доставки:</span>
                    <span class="info-value"><?= Html::encode($model->address) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Дата доставки:</span>
                    <span class="info-value"><?= Yii::$app->formatter->asDatetime($model->date_delivery, 'php:d.m.Y H:i') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Способ оплаты:</span>
                    <span class="info-value"><?= Html::encode($model->payment) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Дата создания:</span>
                    <span class="info-value"><?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i') ?></span>
                </div>
                 <div class="info-item">
                    <span class="info-label">Статус:</span>
                    <span class="info-value"><?= Html::encode($model->status) ?></span>
                </div>
                 <?php if ($model->comments): ?>
                <div class="info-item">
                    <span class="info-label">Комментарий:</span>
                    <span class="info-value"><?= Html::encode($model->comments) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($rejection): ?>
<div class="info-item">
    <span class="info-label">Причина отмены:</span>
    <span class="info-value"><?= Html::encode($rejection->reason) ?></span>
</div>
<?php endif; ?>
            </div>
        </div>

        <div class="order-products-section">
            <h3 class="section-title">Состав заказа</h3>
           <div class="products-list">
    <?php foreach ($model->carts as $cartItem): ?>
        <?php $product = $cartItem->product; ?>
        <a href="<?= Url::to(['product/view', 'id' => $product->id]) ?>" class="product-card-link">
            <div class="product-card">
                <div class="product-image-container">
                    <?php if ($product->photo): ?>
                        <?= Html::img('@web' . $product->photo, [
                            'class' => 'product-image',
                            'alt' => $product->name
                        ]) ?>
                    <?php else: ?>
                        <div class="product-image-placeholder">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-details">
                    <h4 class="product-name"><?= Html::encode($product->name) ?></h4>
                    <div class="product-quantity"><?= $cartItem->count ?> шт. × <?= $product->cost ?> ₽</div>
                </div>
                
                <div class="product-price">
                    <?= $cartItem->count * $product->cost ?> ₽
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

            <div class="order-summary">
                <div class="summary-line">
                    <span>Сумма заказа:</span>
                    <span><?= $model->totalSum ?> ₽</span>
                </div>
                <div class="summary-line">
                    <span>Доставка:</span>
                    <span>Бесплатно</span>
                </div>
                <div class="summary-total">
                    <span>Итого к оплате:</span>
                    <span class="total-price"><?= $model->totalSum ?> ₽</span>
                </div>
            </div>
           <?php if ($model->status == 'Новый' && !Yii::$app->user->identity->isAdmin()): ?>
    <div class="order-cancel">
        <button class="btn cancel-order-btn" data-url="<?= Url::to(['order/cancel', 'id' => $model->id]) ?>">
            Отменить заказ
        </button>
    </div>
<?php endif; ?>
        </div>
    </div>

    <div class="order-actions">
        <?= Html::a('<i class="fas fa-arrow-left"></i> Вернуться к списку заказов', ['order/index'], ['class' => 'btn continue-shopping']) ?>
        <?= Html::a('Продолжить покупки <i class="fas fa-chevron-right"></i>', ['product/catalog'], ['class' => 'btn place-new-order']) ?>
    </div>
</div>
<?php
$this->registerJs("
$(document).on('change', '#statusSelect', function() {
    if ($(this).val() === 'Отменён') {
        $('#reasonContainer').show();
    } else {
        $('#reasonContainer').hide();
    }
});

$(document).on('click', '#confirmStatusChange', function() {
    const url = $(this).data('url');
    const newStatus = $('#statusSelect').val();
    const reason = $('#cancelReason').val();
    
    if (newStatus === 'Отменён' && !reason) {
        alert('Пожалуйста, укажите причину отмены');
        return;
    }
    
    $.post(url, {
        _csrf: yii.getCsrfToken(),
        status: newStatus,
        reason: reason
    }, function(response) {
        if (response.success) {
            location.reload();
        } else {
            alert('Ошибка при изменении статуса: ' + response.message);
        }
    }).fail(function() {
        alert('Произошла ошибка при отправке запроса');
    });
});

$(document).on('click', '.cancel-order-btn', function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    $('#confirmCancelModal').data('url', url).modal('show');
});

$('#confirmCancel').on('click', function() {
    const url = $('#confirmCancelModal').data('url');
    $.post(url, {_csrf: yii.getCsrfToken()}, function() {
        location.reload();
    });
});
");
?>
 <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="changeStatusLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Смена статуса заказа</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        <div class="form-group">
            <label for="statusSelect">Выберите новый статус:</label>
            <select class="form-control" id="statusSelect" style="margin-top: 10px;">
                <?php foreach ($statuses as $value => $label): ?>
                    <?php if ($value !== $model->status): ?>
                        <option value="<?= $value ?>"><?= $label ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mt-3" id="reasonContainer" style="display: none;">
            <label for="cancelReason">Причина отмены:</label>
            <textarea class="form-control" id="cancelReason" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="
            background-color: #d8c9b5;
            color: #5a3a2a;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Отмена</button>
        <button type="button" class="btn" id="confirmStatusChange" data-url="<?= Url::to(['order/change-status', 'id' => $model->id]) ?>" style="
            background-color: #8c4a29;
            color: #fffaf0;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Сохранить</button>
      </div>
    </div>
  </div>
</div>
<!-- Модальное окно подтверждения отмены -->
<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="confirmCancelLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Подтверждение отмены</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        Вы уверены, что хотите отменить этот заказ?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="
            background-color: #d8c9b5;
            color: #5a3a2a;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Отмена</button>
        <button type="button" class="btn" id="confirmCancel" style="
            background-color: #8c4a29;
            color: #fffaf0;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Отменить</button>
      </div>
    </div>
  </div>
</div>