<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = $this->title;


// Подключаем CSS файл
$this->registerCssFile('@web/css/type_create.css', [
    'depends' => [yii\bootstrap5\BootstrapAsset::class]
]);
$this->registerCssFile('@web/css/login.css');
?>

<div class="sirius-container">
    <div class="sirius-header">
        <h1>Sirius</h1>
        <h2>Создание новой категории</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'class' => 'sirius-form',
        'id' => 'category-form'  // Добавляем ID форме
    ]); ?>

    <div class="sirius-divider"><span>Форма для заполнения</span></div>

    <?= $form->field($model, 'name')->textInput([
    'class' => 'form-input', // заменили form-control
    'placeholder' => 'Название категории'
])->label(false) ?>

<div class="form-group">
    <?= Html::submitButton('Создать категорию', [
        'class' => 'btn-login', // заменили btn
        'id' => 'submit-button'
    ]) ?>
</div>

    <?php ActiveForm::end(); ?>
</div>