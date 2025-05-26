<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
$this->registerCssFile('@web/css/order.css');
$this->registerJsFile('@web/js/order.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="sirius-container">
    <div class="sirius-header">
        <h1>Sirius</h1>
        <h2>Оформление заказа</h2>
    </div>
    
    <div class="product-form">
        <?php $form = ActiveForm::begin([
            'class' => 'sirius-form',
            'id' => 'order-form'
        ]); ?>

        <div class="sirius-divider"><span>Данные для доставки</span></div>
        
        <div class="form-field">
            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '+7(999)-999-99-99',
                'options' => [
                    'class' => 'form-input', 
                    'placeholder' => '+7(___)-___-__-__'
                ]
            ])->label('Телефон получателя', ['class' => 'field-label']) ?>
        </div>
        
        <div class="form-field">
            <?= $form->field($model, 'address')->textInput([
                'class' => 'form-input',
                'placeholder' => 'Введите адрес доставки'
            ])->label('Адрес доставки', ['class' => 'field-label']) ?>
        </div>
        
       <div class="form-field">
    <?= $form->field($model, 'date_delivery')->textInput([
        'type' => 'datetime-local',
        'class' => 'form-input',
        'min' => date('Y-m-d\TH:i', strtotime('+2 day')),
        'onkeydown' => 'return false',
    ])->label('Дата доставки', ['class' => 'field-label']) ?>
</div>
        
        <div class="form-field">
            <?= $form->field($model, 'payment')->dropDownList([
                'Банковской картой при получении' => 'Банковской картой при получении', 
                'Наличными при получении' => 'Наличными при получении',
            ], [
                'class' => 'form-input select-input',
            ])->label('Способ оплаты', ['class' => 'field-label']) ?>
        </div>
        
        <div class="form-field">
            <?= $form->field($model, 'comments')->textarea([
                'rows' => 4,
                'class' => 'form-input textarea-input', 
                'placeholder' => 'Комментарии к заказу (по желанию)'
            ])->label('Комментарии', ['class' => 'field-label']) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Подтвердить заказ', [
                'class' => 'btn-login', 
                'id' => 'submit-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
