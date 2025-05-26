<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\User;

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/type_create.css');
?>

<div class="sirius-container">
    <div class="sirius-header">
        <h1>Sirius</h1>
        <h2>Создание нового пароля</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'class' => 'sirius-form',
        'id' => 'password-change-form'
    ]); ?>

    <div class="sirius-divider"><span>Введите новый пароль</span></div>

    <?= $form->field($model, 'password')->passwordInput([
        'class' => 'form-input',
        'id' => 'new-password',
        'placeholder' => 'Новый пароль'
    ])->label(false) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput([
        'class' => 'form-input',
        'placeholder' => 'Повторите пароль'
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить пароль', [
            'class' => 'btn-login',
            'id' => 'submit-button'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
$(document).on('submit', 'form', function(e) {
    e.preventDefault();
    var password = $('#new-password').val();
    var hashedPassword = CryptoJS.SHA256(password).toString();
    $('#new-password').val(hashedPassword);
    this.submit();
});
</script>