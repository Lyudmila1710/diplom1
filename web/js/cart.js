$(document).ready(function () {
    let deleteBtn = null;

    // Обработка кнопок +/- изменения количества
    $(document).on('click', '.quantity-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const itemId = btn.data('product-id');
        const isPlus = btn.hasClass('plus');

        $.post('/cart/update?id=' + itemId, {
            change: isPlus ? 1 : -1,
            _csrf: yii.getCsrfToken()
        }, function (response) {
            if (response.success) {
                const item = btn.closest('.cart-item');
                const price = parseFloat(item.find('.item-price').data('price'));

                if (response.count === undefined) {
                    item.fadeOut(300, function () {
                        $(this).remove();
                        updateTotalPrice();
                        checkEmptyCart();
                    });
                } else {
                    item.find('.quantity').text(response.count);
                    item.find('.item-count').text(response.count);
                    const newTotal = (price * response.count).toFixed(2);
                    item.find('.item-total').text(newTotal);

                    updateTotalPrice();
                }
            } else {
                alert(response.error || 'Ошибка обновления');
            }
        }).fail(() => alert('Ошибка соединения'));
    });

    // Обновление общей суммы
    function updateTotalPrice() {
        let total = 0;
        $('.cart-item').each(function () {
            const itemTotal = parseFloat($(this).find('.item-total').text());
            total += itemTotal;
        });
        $('.total-price').text(total.toFixed(2) + ' ₽');
    }

    // Проверка на пустую корзину
    function checkEmptyCart() {
        if ($('.cart-item').length === 0) {
            $('.cart-items').html(`
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2>Ваша корзина пуста</h2>
                    <p>Перейдите в каталог, чтобы добавить товары в корзину</p>
                    <a href="/product/catalog" class="btn btn-cat">Перейти в каталог</a>
                </div>
            `);
            $('.cart-summary').hide();
        }
    }

    // Открытие модалки при нажатии на кнопку удаления
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        deleteBtn = $(this);

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    });

    // Подтверждение удаления товара
    $('#confirmDeleteBtn').on('click', function () {
        if (!deleteBtn) return;

        const productId = deleteBtn.data('product-id');
        const url = '/cart/delete?id=' + productId;

        $.post(url, {
            _csrf: yii.getCsrfToken()
        }, function (response) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));

            if (response.success) {
                deleteBtn.closest('.cart-item').fadeOut(300, function () {
                    $(this).remove();
                    updateTotalPrice();
                    checkEmptyCart();
                });
            } else {
                alert(response.error || 'Ошибка удаления товара');
            }

            modal.hide();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error:", textStatus, errorThrown);
            alert('Ошибка соединения с сервером');

            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            modal.hide();
        });
    });
});
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
                heart.css('transform', 'scale(1.2)');
                setTimeout(() => {
                    heart.css('transform', 'scale(1)');
                }, 300);
            } else {
                heart.removeClass('active');
                heart.css('transform', 'scale(0.8)');
                setTimeout(() => {
                    heart.css('transform', 'scale(1)');
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