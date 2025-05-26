<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/type_create.css');
?>

<div class="sirius-container">
    <div class="sirius-header">
        <h1>Sirius</h1>
        <h2>Восстановление пароля</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'class' => 'sirius-form',
        'id' => 'password-reset-form'
    ]); ?>

    <div class="sirius-divider"><span>Введите ваш email</span></div>

    <div class="form-group">
        <input type="email" name="email" class="form-input" required placeholder="Ваш email">
    </div>

    <div class="form-group">
        <?= Html::submitButton('Отправить ссылку', [
            'class' => 'btn-login',
            'id' => 'submit-button'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>