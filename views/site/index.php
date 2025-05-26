<?php
use yii\bootstrap5\Carousel;
use yii\helpers\Html;
/** @var yii\web\View $this */

$this->title = 'Sirius';
$this->registerCssFile('@web/css/cake.css');
$this->registerJsFile('@web/js/cake.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<div id="home" class="hero-section">
    <div class="hero-text animate__animated animate__fadeInDown">
        <h1>Кондитерская Sirius</h1>
        <p>Путь к сладкому начинается с Сириуса</p>
    </div>
    <div class="hero-image animate__animated animate__fadeInTopRight ">
        <img src="/images/cake6.png" alt="Chocolate Cake">
    </div>
</div>

<div id="new" class="new-arrivals section">
    <h2>Новинки</h2>
    <div class="product-list">
        <?php
        use app\models\Product;
        
        // Получаем 4 последних доступных продукта
        $products = Product::find()
            ->where(['available' => 'Доступен'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(4)
            ->all();
        
        $animationDelay = 0;
        foreach ($products as $product) {
            echo Html::a(
                '<div class="product animate__animated animate__fadeInUp" style="animation-delay: '.$animationDelay.'s;">
                    <img src="'.$product->photo.'" alt="'.$product->name.'">
                    <h4>'.$product->name.'</h4>
                </div>',
                ['product/view', 'id' => $product->id],
                ['class' => 'product-link']
            );
            $animationDelay += 0.2;
        }
        
        // Если продуктов нет, выводим заглушку
        if (empty($products)) {
            echo '<p>Новых продуктов пока нет</p>';
        }
        ?>
    </div>
</div>

<div id="why" class="why-choose-us section">
    <h2>Почему именно мы?</h2>
    <p>Мы стремимся предоставлять кондитерские изделия высочайшего качества.</p>
    <div class="features">
        <div class="feature animate__animated animate__fadeInUp">
           <img src="/images/coca-leaves.png" alt="растение" class="ico"/>
        <h3>Качественные ингредиенты</h3>
            <p>Используем только самые качественные ингридиенты, чтобы обеспечить наилучшее качество</p>
        </div>
        <div class="feature animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <img src="/images/heart.png" alt="сердце" class="ico"/>
        <h3>Сделано с любовью</h3>
            <p>Наши десерты изготавливаются вручную со страстью к выпечке и кулинарии</p>
        </div>
        <div class="feature animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
        <img src="/images/strawberry.png" alt="клубника" class="ico"/>
        <h3>Разнообразие вкусов</h3>
            <p>Мы предлагаем широкий ассортимент вкусов, чтобы удовлетворить каждого клиента.</p>
        </div>
        <div class="feature animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
        <img src="/images/order-fulfillment.png" alt="шестеренка" class="ico"/>
        <h3>Индивидуальные заказы</h3>
            <p>Мы создаем кондитерские изделия с учетом ваших пожеланий. </p>
        </div>
    </div>
</div>
<div id="awards" class="awards section">
  <div class="awards-content">
    <img src="/images/kybok_.png" alt="Сертификаты и награды" />
    <div class="awards-text animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
      <h2>Сертификаты и награды</h2>
      <p>Мы гордимся тем, что наша работа признана профессиональным сообществом:</p>
      <ul class="award-list">
        <li>Золотая медаль на конкурсе "Лучший десерт года 2024"</li>
        <li>Сертификат качества от Ассоциации пекарей России</li>
        <li>Победитель в номинации "Лучший свадебный торт" — 2025</li>
      </ul>
    </div>
  </div>
</div>


<div id="history" class="history">
  <div class="history-content">
    <div class="history-text">
      <img src="/images/history.png" alt="иконка адреса" class="icon" class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s;" />
      <h2>Наша История</h2>
      <div class="history-summary animate__animated animate__fadeInUp" style="animation-delay: 0.3s;" >
        <p>Всё началось в 2010 году, на кухне многодетной мамы по имени Елена...</p>
      </div>
      <div class="history-full-text animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
        <p>Однажды за семейным ужином дети сказали:  
        «Мама, ты печёшь лучше всех — почему бы тебе не открыть свою кондитерскую?». И эта фраза изменила всё.</p> <p>Сначала были заказы от соседей и ярмарки, затем — уютная пекарня, которая получила название Sirius — в честь самой яркой звезды на небе и детской мечты сиять в любимом деле.</p>
        <p>Sirius быстро стал местом, куда приходят за вкусом домашнего уюта.</p>
        <p>Сегодня Sirius — это современная кондитерская мастерская, но в каждом нашем изделии до сих пор живёт тот первый семейный совет — и мамино сердце, полное любви.</p>
        <p>А ведь всё началось дома, на обычной кухне.</p>
        </div>
      <button class="toggle-btn">Показать больше</button>
    </div>
    <img src="/images/povar.png" alt="История пекарни" style="animation-delay: 0.4s;"  />
  </div>
</div>


<div id="contact" class="contact section">
  <h2>Свяжитесь с нами</h2>
  <div class="contact-info">
      
  <p>
    <img src="/images/pin.png" alt="иконка адреса" class="icon" />
    <strong>Адрес:</strong> г. Санкт-Петербург, ул. Сладкая, 15
  </p>
  <p>
    <img src="/images/phone.png" alt="иконка телефона" class="icon" />
    <strong>Телефон:</strong> +7 (952) 875-45-67
  </p>
  <p>
    <img src="/images/envelope.png" alt="иконка почты" class="icon" />
    <strong>Email:</strong> infoSirius@mail.ru
  </p>
  <p>
    <img src="/images/wall-clock.png" alt="иконка часов" class="icon" />
    <strong>Часы работы:</strong> Пн–Сб 10:00–20:00
  </p>
  </div>
</div>
  