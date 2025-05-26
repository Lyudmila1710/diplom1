<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Type $model */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
