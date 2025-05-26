<?php

use app\models\Type;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->registerCssFile('@web/css/type_index.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-index">

    <h1 class="type-title"><?= Html::encode($this->title) ?></h1>

            <div class="header-divider"></div>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-su']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view'],
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

        
            'name',
            [
                'class' => ActionColumn::className(), 'template'=>'{delete}',  'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash-alt"></i>', 'javascript:void(0);', [
                            'class' => 'delete-btn',
                            'data-url' => $url,
                            'title' => 'Удалить',
                            'style' => 'color: #8c4a29;'
                        ]);
                    },
                ],
                'urlCreator' => function ($action, Type $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'pager'=> ['class'=>'\yii\bootstrap5\LinkPager'],
    ]); ?>


</div>

<?php
$this->registerJs("
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        $('#confirmDeleteModal').data('url', url).modal('show');
    });

    $('#confirmDelete').on('click', function() {
        const url = $('#confirmDeleteModal').data('url');
        $.post(url, {_csrf: yii.getCsrfToken()}, function() {
            location.reload();
        });
    });
");
?>

<!-- Bootstrap 5 Модальное окно -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff8f0; border-radius: 12px; border: 2px solid #d8c9b5;">
      <div class="modal-header" style="border-bottom: none;">
        <h5 class="modal-title" id="confirmDeleteLabel" style="color: #8c4a29; font-family: 'Cormorant Infant', serif;">Подтверждение удаления</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body" style="font-family: 'EB Garamond', serif; color: #341C0E;">
        Вы уверены, что хотите удалить эту категорию?
      </div>
      <div class="modal-footer" style="border-top: none;">
        <button type="button" class="btn" data-bs-dismiss="modal" style="
            background-color: #d8c9b5;
            color: #5a3a2a;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Отмена</button>
        <button type="button" class="btn" id="confirmDelete" style="
            background-color: #8c4a29;
            color: #fffaf0;
            font-family: 'EB Garamond', serif;
            border-radius: 6px;
            padding: 6px 16px;
        ">Удалить</button>
      </div>
    </div>
  </div>
</div>
