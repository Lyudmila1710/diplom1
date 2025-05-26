<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Cart;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS и JS для анимаций
$this->registerCssFile('@web/css/view_product.css');
$this->registerJsFile('@web/js/view_product.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="sirius-product-container">
   
   <div class="product-header">
    <div class="back-button-container">
        <?php if (Yii::$app->session->get('fromUpdate') !== true): ?>
            <?= Html::button('Назад', [
                'class' => 'btn btn-back',
                'onclick' => 'window.history.back();',
                'data-aos' => 'zoom-in',
                'data-aos-delay' => '100'
            ]) ?>
        <?php endif; ?>
    </div>
    
    <div class="title-wrapper" style="position: relative; display: inline-block; margin: 0 auto;">
        <h1 class="product-title"><?= Html::encode($model->name) ?></h1>
        <?php if(!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
            <button class="favorite-heart large-heart <?= $model->isFavorite(Yii::$app->user->id) ? 'active' : '' ?>" 
                    data-product-id="<?= $model->id ?>"
                    style="position: absolute; right: -60px; top: 50%; transform: translateY(-50%);">
                <i class="fas fa-heart"></i>
            </button>
        <?php endif; ?>
    </div>
    <div class="product-divider"></div>
</div>
    

    <div class="product-content">
        <div class="product-gallery">
            <div class="main-image-container">
                <?= Html::img(Yii::getAlias('@web') . $model->photo, [
                    'class' => 'main-product-image',
                    'alt' => $model->name,
                    'data-aos' => 'fade-right'
                ]) ?>
            </div>
        </div>

        <div class="product-details" data-aos="fade-left">
            <div class="product-description">
                <h3 class="detail-title">Описание</h3>
                <p class="detail-content"><?= nl2br(Html::encode($model->description)) ?></p>
            </div>

            <div class="product-info-grid">
                <div class="info-item">
                    <span class="info-label">Категория:</span>
                    <span class="info-value"><?= $model->type->name ?? 'Не указана' ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Цена:</span>
                    <span class="info-value price"><?= number_format($model->cost, 2, '.', ' ') ?> ₽</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Вес:</span>
                    <span class="info-value"><?= $model->weight ?> кг</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Наличие:</span>
                    <span class="info-value availability <?= mb_strtolower($model->available) === 'доступен' ? 'in-stock' : 'out-of-stock' ?>">
                        <?= mb_strtolower($model->available) === 'доступен' ? 'В наличии' : 'Нет в наличии' ?>
                    </span>
                </div>
            </div>

            <?php if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
                <div class="user-actions">
                    <?php 
                    $cartItem = Cart::findOne([
                        'user_id' => Yii::$app->user->id, 
                        'product_id' => $model->id, 
                        'order_id' => null
                    ]);
                    
                    if ($cartItem): ?>
                        <div class="quantity-control" data-product-id="<?= $model->id ?>">
                            <button class="quantity-btn minus" type="button">–</button>
                            <span class="quantity"><?= $cartItem->count ?></span>
                            <button class="quantity-btn plus" type="button">+</button>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-add-to-cart add-to-cart" data-product-id="<?= $model->id ?>" type="button">
                            В корзину
                        </button>
                    <?php endif; ?>
                </div>
            <?php elseif (Yii::$app->user->isGuest): ?>
                <div class="guest-actions">
                    <?= Html::a('Войти, чтобы добавить в корзину', ['site/login'], [
                        'class' => 'btn btn-login-to-add'
                    ]) ?>
                </div>
            <?php endif; ?>

          <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
    <div class="product-actions">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], [
            'class' => 'btn btn-edit ed',
            'data-aos' => 'zoom-in',
            'data-aos-delay' => '200'
        ]) ?>
        <?php if ($model->available !== 'Закрыт'): ?>
            <button type="button"
                class="btn btn-delete del delete-btn"
                data-url="<?= \yii\helpers\Url::to(['product/soft-delete', 'id' => $model->id]) ?>"
                data-bs-toggle="modal"
                data-bs-target="#confirmDeleteModal"
                data-aos="zoom-in"
                data-aos-delay="400">
                Нет в наличии
            </button>
        <?php else: ?>
            <?= Html::a('В наличии', ['product/soft-add', 'id' => $model->id], [
                'class' => 'btn btn-add del add-btn',
                'data-aos' => 'zoom-in',
                'data-aos-delay' => '400'
            ]) ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
        </div>
    </div>
</div>

<?php
// После того как страница загружена, сбрасываем флаг, чтобы не скрывать кнопку "Назад" на других страницах
Yii::$app->session->remove('fromUpdate');
?>

<?php
$this->registerJs("
    // Функция для отображения flash-сообщения
    function showFlashMessage(message, type = 'success') {
        // Создаем контейнер для flash-сообщений, если его еще нет
        if ($('.flash-message-container').length === 0) {
            $('body').append('<div class=\"flash-message-container\"></div>');
        }
        
        const flashHtml = `
            <div class=\"alert alert-\${type} alert-dismissible fade show flash-message\" role=\"alert\">
                \${message}
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Закрыть\"></button>
            </div>
        `;
        
        $('.flash-message-container').append(flashHtml);
        
        // Автоматическое скрытие через 5 секунд
        setTimeout(() => {
            $('.flash-message').first().fadeOut(500, function() {
                $(this).remove();
                // Удаляем контейнер, если больше нет сообщений
                if ($('.flash-message').length === 0) {
                    $('.flash-message-container').remove();
                }
            });
        }, 5000);
    }

    // Обработка кнопки Нет в наличии
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        $('#confirmDeleteModal').data('url', url).modal('show');
    });

    // Обработка кнопки В наличии
    $(document).on('click', '.add-btn', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const btn = $(this);
        
        $.post(url, {_csrf: yii.getCsrfToken()}, function(response) {
            if (response.success) {
                showFlashMessage(response.message);
                
                // Обновляем статус наличия и цвет
                const availability = $('.availability');
                availability.removeClass('out-of-stock').addClass('in-stock').text(response.newStatus);
                
                // Обновляем кнопку
                btn.removeClass('add-btn').addClass(response.newButtonClass)
                   .removeClass('btn-add').addClass('btn-delete')
                   .text(response.newButtonText)
                   .attr('href', '#')
                   .data('url', response.newButtonUrl);
                
                // Если это была ссылка, делаем её кнопкой
                if (btn.is('a')) {
                    const newBtn = $('<button>')
                        .addClass('btn btn-delete del ' + response.newButtonClass)
                        .text(response.newButtonText)
                        .data('url', response.newButtonUrl);
                    
                    btn.replaceWith(newBtn);
                }
            } else {
                alert('Ошибка: ' + (response.error || 'неизвестная'));
            }
        });
    });

    $('#confirmDelete').on('click', function() {
        const url = $('#confirmDeleteModal').data('url');
        $.post(url, {_csrf: yii.getCsrfToken()}, function(response) {
            if (response.success) {
                $('#confirmDeleteModal').modal('hide');
                showFlashMessage(response.message);
                
                // Обновляем статус наличия и цвет
                const availability = $('.availability');
                availability.removeClass('in-stock').addClass('out-of-stock').text(response.newStatus);
                
                // Обновляем кнопку
                const btn = $('.delete-btn');
                btn.removeClass('delete-btn').addClass(response.newButtonClass)
                   .removeClass('btn-delete').addClass('btn-add')
                   .text(response.newButtonText)
                   .attr('href', response.newButtonUrl)
                   .removeData('url');
                
                // Если это была кнопка, делаем её ссылкой
                if (btn.is('button')) {
                    const newBtn = $('<a>')
                        .addClass('btn btn-add del ' + response.newButtonClass)
                        .text(response.newButtonText)
                        .attr('href', response.newButtonUrl);
                    
                    btn.replaceWith(newBtn);
                }
            } else {
                alert('Ошибка: ' + (response.error || 'неизвестная'));
            }
        });
    });
    // Обработка кнопок корзины
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        $.post('/cart/create', {
            product_id: productId,
            _csrf: yii.getCsrfToken()
        }, function(response) {
            if (response.success) {
                $('.add-to-cart[data-product-id=\"' + productId + '\"]').replaceWith(
                    '<div class=\"quantity-control\" data-product-id=\"' + productId + '\">' +
                        '<button class=\"quantity-btn minus\" type=\"button\">-</button>' +
                        '<span class=\"quantity\">' + response.count + '</span>' +
                        '<button class=\"quantity-btn plus\" type=\"button\">+</button>' +
                    '</div>'
                );
            } else {
                alert(response.error || 'Произошла ошибка');
            }
        }).fail(() => alert('Ошибка соединения с сервером'));
    });

    $(document).on('click', '.quantity-btn.plus, .quantity-btn.minus', function(e) {
        e.preventDefault();
        const control = $(this).closest('.quantity-control');
        const productId = control.data('product-id');
        const change = $(this).hasClass('plus') ? 1 : -1;

        $.post('/cart/update?id=' + productId, {
            change: change,
            _csrf: yii.getCsrfToken()
        }, function(response) {
            if (response.success) {
                if (response.count === undefined) {
                    control.replaceWith(
                        '<button class=\"btn btn-add-to-cart add-to-cart\" data-product-id=\"' + productId + '\" type=\"button\">' +
                            'В корзину' +
                        '</button>'
                    );
                } else {
                    control.find('.quantity').text(response.count);
                }
            } else {
                alert(response.error || 'Произошла ошибка');
            }
        }).fail(() => alert('Ошибка соединения с сервером'));
    });
");
?>
<!-- Bootstrap 5 Модальное окно -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="confirmDeleteLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Подтверждение удаления</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        Вы уверены, что хотите убрать наличие данного товара?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="
            background-color: #d8c9b5;
            color: #5a3a2a;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Отмена</button>
        <button type="button" class="btn" id="confirmDelete" style="
            background-color: #8c4a29;
            color: #fffaf0;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Убрать</button>
      </div>
    </div>
  </div>
</div>