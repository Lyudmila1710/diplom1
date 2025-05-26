<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/account.css');
$this->registerJsFile('@web/js/account.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="user-account-container">
    <div class="account-header">
        <h1 class="account-title"><?= Html::encode($this->title) ?></h1>
        <div class="header-divider"></div>
    </div>

    <div class="account-content">
        <div class="account-sidebar">
            <div class="user-welcome">
                <h3 class="user-name"><?= Html::encode($user->name . ' ' . $user->surname) ?></h3>
                <p class="user-email"><?= Html::encode($user->email) ?></p>
            </div>
            
            <nav class="account-menu">
                <ul>
                    <li><a href="#profile" class="active" ><i class="fas fa-user"></i> Профиль</a></li>
                    <li><a href="#favorite"><i class="fas fa-heart"></i> Избранное</a></li>
                </ul>
            </nav>
        </div>

        <div class="account-main">
             <div class="profile-section active" id="profile">
        <h2 class="section-title"><i class="fas fa-user"></i> Личные данные</h2>
        
        <?php $form = ActiveForm::begin([
            'id' => 'account-form',
            'options' => ['class' => 'account-form'],
        ]); ?>

        <div class="form-row">
            <?= $form->field($model, 'name', [
                'options' => ['class' => 'form-group col-md-6']
            ])->textInput(['class' => 'form-control']) ?>
            
            <?= $form->field($model, 'surname', [
                'options' => ['class' => 'form-group col-md-6']
            ])->textInput(['class' => 'form-control']) ?>
        </div>

        <div class="form-row">
            <?= $form->field($model, 'email', [
                'options' => ['class' => 'form-group col-md-6']
            ])->textInput(['class' => 'form-control']) ?>
            
            <?= $form->field($model, 'phone', [
                'options' => ['class' => 'form-group col-md-6']
            ])->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '+7(999)-999-99-99',
                'options' => ['class' => 'form-input form-control', 'placeholder' => 'Телефон'],
            ]) ?>
        </div>
        <div class="form-actions">
            <?= Html::submitButton('Сохранить изменения', [
                'class' => 'btn btn-save',
                'name' => 'account-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

  <div class="favorites-section" id="favorite">
    <h2 class="section-title"><i class="fas fa-heart"></i> Избранное</h2>
    <div class="favorites-container">
        <?php if (!empty($favorites)): ?>
            <div class="favorites-slider">
                <div class="favorites-grid">
                    <?php foreach ($favorites as $favorite): ?>
                        <a href="<?= Yii::$app->urlManager->createUrl(['product/view', 'id' => $favorite->product->id]) ?>" class="favorite-product-link">
                            <div class="favorite-product-card" data-product-id="<?= $favorite->product->id ?>">
                                <div class="favorite-product-image">
                                    <?= Html::img('@web' . $favorite->product->photo, [
                                        'class' => 'favorite-product-img', 
                                        'alt' => $favorite->product->name
                                    ]) ?>
                                    <button class="favorite-heart active" data-product-id="<?= $favorite->product->id ?>">
                                        <svg class="heart-icon" viewBox="0 0 24 24">
                                            <path class="heart-stroke" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            <path class="heart-fill" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="favorite-product-content">
                                    <h3 class="favorite-product-name"><?= Html::encode($favorite->product->name) ?></h3>
                                    <div class="favorite-product-price"><?= number_format($favorite->product->cost, 0, '', ' ') ?> ₽</div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="slider-controls">
                    <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-favorites">
                <div class="empty-heart">
                    <i class="far fa-heart"></i>
                </div>
                <h4>У вас пока нет избранных товаров</h4>
                <p>Добавляйте товары в избранное, чтобы не потерять их</p>
                <a href="/product/catalog" class="btn btn-cat">Перейти в каталог</a>
            </div>
        <?php endif; ?>
    </div>
</div>
        </div>
    </div>
</div>
<!-- Модальное окно при попытке покинуть страницу -->
<div class="modal fade" id="unsavedChangesModal" tabindex="-1" aria-labelledby="unsavedChangesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="unsavedChangesLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Несохранённые изменения</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        У вас есть несохранённые изменения. Вы уверены, что хотите покинуть страницу?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="
            background-color: #d8c9b5;
            color: #5a3a2a;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Остаться</button>
        <button type="button" class="btn" id="leavePageConfirm" style="
            background-color: #8c4a29;
            color: #fffaf0;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Покинуть</button>
      </div>
    </div>
  </div>
</div>