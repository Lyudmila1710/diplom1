<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/login.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerJsFile('@web/js/cake.js', ['depends' => [\yii\web\JqueryAsset::class]]); 
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js');
$this->registerJsFile('@web/js/hash.js'); 
// ?>

<div class="site-login">
    <div class="login-container">
        <h1 class="title"><?= Html::encode($this->title) ?></h1>

        <div class="cupcake-image">
            <img src="/images/reg_cake_.png" alt="Cupcake" />
        </div>

        <div class="form-container">
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                    'inputOptions' => ['class' => 'form-input'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Имя']) ?>
            <?= $form->field($model, 'surname')->textInput(['maxlength' => true, 'placeholder' => 'Фамилия']) ?>
            <?= $form->field($model, 'username',['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'placeholder' => 'Логин']) ?>
            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '+7(999)-999-99-99',
                'options' => ['class' => 'form-input', 'placeholder' => 'Телефон'],
            ]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Email']) ?>

            <div class="password-field-wrapper">
    <?= $form->field($model, 'password', [
        'template' => '{input}<span class="toggle-password"><i class="fas fa-eye"></i></span>{error}',
        'options' => ['class' => 'form-group has-feedback']
    ])->passwordInput([
        'placeholder' => 'Пароль',
        'id' => 'password-fields',
        'class' => 'form-input with-eye'
    ])->label(false) ?>
    
</div>  

            <div class="form-group">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn-login']) ?>
            </div>

            <div class="form-options">
                <p>Уже есть аккаунт? <?= Html::a('Войти', ['site/login']) ?></p>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>