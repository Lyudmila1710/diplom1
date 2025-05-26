<?php
use app\models\Cart;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/cart.css');
$this->registerJsFile('@web/js/cart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title"><?= Html::encode($this->title) ?></h1>
        <div class="header-divider"></div>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Ваша корзина пуста</h2>
            <p>Перейдите в каталог, чтобы добавить товары в корзину</p>
            <a href="<?= Url::to(['/product/catalog']) ?>" class="btn btn-cat">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <?php $totalPrice = 0; ?>
            <?php foreach ($cartItems as $item): ?>
                <?php 
                    $product = $item->product;
                    $itemPrice = $product->cost * $item->count;
                    $totalPrice += $itemPrice;
                ?>
                <div class="cart-item" data-product-id="<?= $product->id ?>">
                    <div class="item-image">
                        <?= Html::img(Yii::getAlias('@web') . $product->photo, ['alt' => $product->name]) ?>
                    </div>
                    <div class="item-details">
                        <h3 class="item-name"><?= Html::encode($product->name) ?></h3>
                        <p class="item-price" data-price="<?= $product->cost ?>">
                            <?= $product->cost ?> ₽ × <span class="item-count"><?= $item->count ?></span> = 
                            <span class="item-total"><?= $itemPrice ?></span> ₽
                        </p>
                        <div class="item-actions">
                            <div class="quantity-control">
                               <button type="button" class="quantity-btn minus" data-product-id="<?= $product->id ?>">-</button>
<span class="quantity"><?= $item->count ?></span>
<button type="button" class="quantity-btn plus" data-product-id="<?= $product->id ?>">+</button>
                            </div>
                            
                           <div class="action-buttons">
        <?php if(!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()): ?>
            <button class="favorite-heart cart-heart <?= $product->isFavorite(Yii::$app->user->id) ? 'active' : '' ?>" 
                    data-product-id="<?= $product->id ?>">
                <i class="fas fa-heart"></i>
            </button>
        <?php endif; ?>
        
        <button class="btn-delete" 
            data-product-id="<?= $product->id ?>" 
            data-url="<?= Url::to(['/cart/delete', 'id' => $product->id]) ?>">
            <i class="fas fa-trash"></i>
        </button>
    </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Итого:</span>
                <span class="total-price"><?= $totalPrice ?> ₽</span>
            </div>
            <div class="cart-actions">
                <a href="<?= Url::to(['/product/catalog']) ?>" class="btn btn-continue">Продолжить покупки</a>
                <?= Html::a('Оформить заказ', ['/order/create'], ['class' => 'btn btn-checkout']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<!-- Модалка подтверждения удаления -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="deleteConfirmLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Удалить товар</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        Вы уверены, что хотите удалить этот товар из корзины?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #d8c9b5; color: #5a3a2a;">Отмена</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="background-color: #8c4a29; color: #fffaf0;">Удалить</button>
      </div>
    </div>
  </div>
</div>