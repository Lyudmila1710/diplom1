<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
$this->registerCssFile('@web/css/login.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js');
$this->registerJsFile('@web/js/hash_log.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
$this->registerJsFile('@web/js/cake.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
       
<div class="site-login">
     
    <div class="login-container">
<?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
    <div class="alert alert-<?= $type ?> alert-dismissible fade show flash-message" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
    </div>
<?php endforeach; ?>
        <h1 class="title"><?= Html::encode($this->title) ?></h1>

        <div class="cupcake-image">
            <img src="/images/login_cap_.png" alt="Cupcake" />
        </div>

        <div class="form-container">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                    'inputOptions' => ['class' => 'form-input'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Логин']) ?>
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
                <?= Html::submitButton('Войти', ['class' => 'btn-login', 'name' => 'login-button']) ?>
            </div>

            <div class="form-options">
            <?= Html::a('Забыли пароль?', ['user/request-password-reset']) ?>
                <p>Нет аккаунта? <?= Html::a('Зарегистрироваться', ['user/create']) ?></p>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

