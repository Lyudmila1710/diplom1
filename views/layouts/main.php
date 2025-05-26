<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/images/logo.png')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Infant:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<?php $this->beginPage() ?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo.png',['class'=>'logo log','alt'=>'logo']).Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark menu fixed-top']
    ]);

$leftItems = []; 
$rightItems = [];
$items=[];
    if(Yii::$app->user->isGuest){
        $leftItems = [ 
            ['label' => 'Главная', 'url' => Yii::$app->homeUrl . '#home', 'options' => ['id' => 'nav-home']],
            ['label' => 'Новинки', 'url' => Yii::$app->homeUrl . '#new', 'options' => ['id' => 'nav-new']],
            ['label' => 'О нас', 'url' => Yii::$app->homeUrl . '#why', 'options' => ['id' => 'nav-why']],
            ['label' => 'Награды', 'url' => Yii::$app->homeUrl . '#awards', 'options' => ['id' => 'nav-awards']],
            ['label' => 'История', 'url' => Yii::$app->homeUrl . '#history', 'options' => ['id' => 'nav-history']],
            ['label' => 'Контакты', 'url' => Yii::$app->homeUrl . '#contact', 'options' => ['id' => 'nav-contact']],
        ];
    $rightItems =    [ ['label' => 'Каталог', 'url' => ['/product/catalog']],
    ['label' => 'Вход', 'url' => ['/site/login']],
         ['label' => 'Регистрация', 'url' => ['/user/create']],
    ];} else {
        if (Yii::$app->user->identity->isAdmin()){
            $rightItems = [ ['label' => 'Каталог', 'url' => ['/product/catalog']],
             ['label' => 'Заказы', 'url' => ['/order/index']],
             ['label' => 'Категории', 'url' => ['/type/index']],
            '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выход',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'];
        } else{  $rightItems = [ ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Каталог', 'url' => ['/product/catalog']],
           ['label' => 'Корзина', 'url' => ['/cart/index']],
            ['label' => 'Заказы', 'url' => ['/order/index']],
             ['label' => 'Личный кабинет', 'url' => ['/user/account', 'id' => Yii::$app->user->id]],
               '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выход',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'];}}
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav me-auto'],
                        'items' => $leftItems,
                    ]);
                    
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav ms-auto '],
                        'items' => $rightItems,
                    ]);
   

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container-fluid px-0">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 menu">
    <div class="container">
        <div class="row ">
            <div class="col-md-6 text-center text-md-start">&copy; Sirius <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
