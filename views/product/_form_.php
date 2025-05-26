<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
$type=[];
$types=\app\models\Type::find()->all();
foreach($types as $typ){
$type[$typ->id]=$typ->name;
}
$this->registerCssFile('@web/css/type_create.css', [
    'depends' => [yii\bootstrap5\BootstrapAsset::class]
]);
$this->registerCssFile('@web/css/login.css');
?>
<div class="sirius-container">
    <div class="sirius-header">
        <h1>Sirius</h1>
        <h2> Изменение продукта</h2>
    </div>
    <div class="product-form">
    <?php $form = ActiveForm::begin([
        'class' => 'sirius-form',
        'id' => 'category-form'  // Добавляем ID форме
    ]); ?>

    <div class="sirius-divider"><span>Форма для заполнения</span></div>
    <?= $form->field($model, 'name')->textInput([
    'class' => 'form-input', 
    'placeholder' => 'Название продукта'
])->label(
    'Название продукта', 
    ['class' => 'lab']
) ?>
<?= $form->field($model, 'type_id')->DropDownList($type, ['prompt' => 'Выберите категорию','class' => 'form-input'])->label(
    'Категория', 
    ['class' => 'lab']
) ?>
<?= $form->field($model, 'description')->textarea([
    'rows' => 6,
    'class' => 'form-input', 
    'placeholder' => 'Описание'
]) ->label(
    'Описание', 
    ['class' => 'lab']
) ?>
<?= $form->field($model, 'photo')->fileInput([
    'class' => 'form-input',
    'placeholder' => 'Фотография продукта'
])->label(
    'Если не хотите обновлять фотографию - оставьте поле пустым', 
    ['class' => 'lab']
) ?>

<?= $form->field($model, 'cost')->textInput([
    'class' => 'form-input', 
    'placeholder' => 'Цена(руб.)'
]) ->label(
    'Цена(руб.)', 
    ['class' => 'lab']
) ?>
<?= $form->field($model, 'weight')->textInput([
    'class' => 'form-input', 
    'placeholder' => 'Вес(кг)'
])->label(
    'Вес(кг)', 
    ['class' => 'lab']
) ?>
<div class="form-group">
    <?= Html::submitButton('Изменить продукт', [
        'class' => 'btn-login', 
        'id' => 'submit-button'
    ]) ?>
</div>

    <?php ActiveForm::end(); ?>
</div>


