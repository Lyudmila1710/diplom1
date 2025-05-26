document.addEventListener('DOMContentLoaded', function() {
    // Инициализация анимаций при скролле
    const animatedElements = document.querySelectorAll('[data-aos]');
    
    function checkAnimation() {
        animatedElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                element.classList.add('aos-animate');
            }
        });
    }
    
    // Проверяем при загрузке
    checkAnimation();
    
    // И при скролле
    window.addEventListener('scroll', checkAnimation);
    
    // Анимация цены
    const priceElement = document.querySelector('.price');
    if (priceElement) {
        let price = parseFloat(priceElement.textContent.replace(/[^\d.]/g, ''));
        let current = 0;
        const duration = 1500;
        const startTime = Date.now();
        
        function animatePrice() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            current = progress * price;
            
            priceElement.textContent = current.toLocaleString('ru-RU', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' ₽';
            
            if (progress < 1) {
                requestAnimationFrame(animatePrice);
            }
        }
        
        priceElement.textContent = '0.00 ₽';
        setTimeout(animatePrice, 500);
    }
    
    // Параллакс эффект для изображения
    const mainImage = document.querySelector('.main-product-image');
    if (mainImage) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.pageYOffset;
            mainImage.style.transform = `translateY(${scrollPosition * 0.1}px)`;
        });
    }
});
function initFlashMessages() {
    // Создаем контейнер для сообщений, если его еще нет
    if ($('.flash-message-container').length === 0) {
        $('body').append('<div class="flash-message-container"></div>');
    }
    
    // Показываем все flash-сообщения
    $('.alert').each(function() {
        const $message = $(this);
        const duration = $message.data('duration') || 5000; // 5 секунд по умолчанию
        
        // Добавляем сообщение в контейнер
        $('.flash-message-container').append($message);
        
        // Автоматическое скрытие через указанное время
        setTimeout(() => {
            $message.addClass('fade-out');
            setTimeout(() => $message.remove(), 500);
        }, duration);
    });
    
    // Обработка кнопки закрытия
    $(document).on('click', '.alert .btn-close', function() {
        const $message = $(this).closest('.alert');
        $message.addClass('fade-out');
        setTimeout(() => $message.remove(), 500);
    });
}

// Инициализируем при загрузке страницы
$(document).ready(initFlashMessages);
$(document).on('click', '.favorite-heart', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const heart = $(this);
    const productId = heart.data('product-id');
    
    if (heart.hasClass('disabled')) return;
    
    heart.addClass('disabled');
    
    $.post('/favorite/toggle', {
        product_id: productId,
        _csrf: yii.getCsrfToken()
    }, function(response) {
        if (response.success) {
            if (response.status === 'added') {
                heart.addClass('active');
                // Анимация при добавлении в избранное
                heart.css('transform', 'translateY(-50%) scale(1.2)');
                setTimeout(() => {
                    heart.css('transform', 'translateY(-50%) scale(1)');
                }, 300);
            } else {
                heart.removeClass('active');
                // Анимация при удалении из избранного
                heart.css('transform', 'translateY(-50%) scale(0.8)');
                setTimeout(() => {
                    heart.css('transform', 'translateY(-50%) scale(1)');
                }, 300);
            }
        } else {
            if (response.error === 'Необходимо авторизоваться') {
                window.location.href = '/site/login';
            } else {
                alert('Произошла ошибка: ' + response.error);
            }
        }
    }).fail(() => {
        alert('Ошибка соединения с сервером');
    }).always(() => {
        heart.removeClass('disabled');
    });
});
