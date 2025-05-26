let isChanged = false;
let pendingNavigationHref = null;

// Следим за изменениями формы
const form = document.getElementById('account-form');
if (form) {
    form.addEventListener('input', () => {
        isChanged = true;
    });
}

// Сохраняем изменения — сбрасываем флаг
const saveButton = document.querySelector('button[name="account-button"]');
if (saveButton) {
    saveButton.addEventListener('click', () => {
        isChanged = false;
    });
}



// Клики по ссылкам
document.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Игнорируем ссылки с классом .no-check или якорные
        if (this.classList.contains('no-check') || this.getAttribute('href').startsWith('#')) {
            return;
        }

        if (isChanged) {
            e.preventDefault();
            pendingNavigationHref = this.href;

            const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
            modal.show();
        }
    });
});

// Кнопка в модалке "Покинуть"
const confirmLeaveBtn = document.getElementById('leavePageConfirm');
if (confirmLeaveBtn) {
    confirmLeaveBtn.addEventListener('click', () => {
        if (pendingNavigationHref) {
            window.location.href = pendingNavigationHref;
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    const appState = {
        isChanged: false,
        pendingNavigationHref: null
    };

    const elements = {
        form: document.getElementById('account-form'),
        saveButton: document.querySelector('button[name="account-button"]'),
        confirmLeaveBtn: document.getElementById('leavePageConfirm'),
        menuLinks: document.querySelectorAll('.account-menu a'),
        slider: document.querySelector('.favorites-grid'),
        prevBtn: document.querySelector('.slider-prev'),
        nextBtn: document.querySelector('.slider-next'),
        profileSection: document.getElementById('profile'),
        favoriteSection: document.getElementById('favorite'),
        favoritesContainer: document.querySelector('.favorites-container')
    };

    elements.profileSection.classList.add('active');
    document.querySelector('.account-menu a[href="#profile"]').classList.add('active');

    if (elements.form) {
        elements.form.addEventListener('input', () => appState.isChanged = true);
    }

    if (elements.saveButton) {
        elements.saveButton.addEventListener('click', () => appState.isChanged = false);
    }

    elements.menuLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href.startsWith('#')) {
                if (appState.isChanged) {
                    e.preventDefault();
                    appState.pendingNavigationHref = this.href;
                    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
                    modal.show();
                }
                return;
            }

            e.preventDefault();
            const targetId = href.substring(1);
            if (this.classList.contains('active')) return;

            elements.menuLinks.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            switchSections(targetId);

            if (targetId === 'favorite') {
                setTimeout(() => {
                    initSlider();
                }, 10);
            }
        });
    });

    function switchSections(targetId) {
        const current = document.querySelector('.account-main > div.active');
        const target = document.getElementById(targetId);

        if (!current || !target) return;

        current.style.opacity = '0';
        current.style.transform = 'translateY(10px)';

        setTimeout(() => {
            current.classList.remove('active');
            target.classList.add('active');
            setTimeout(() => {
                target.style.opacity = '1';
                target.style.transform = 'translateY(0)';
            }, 10);
        }, 300);
    }

    if (elements.confirmLeaveBtn) {
        elements.confirmLeaveBtn.addEventListener('click', () => {
            if (appState.pendingNavigationHref) {
                window.location.href = appState.pendingNavigationHref;
            }
        });
    }

    function initSlider() {
        if (!elements.slider || !elements.prevBtn || !elements.nextBtn) return;

        const productCards = document.querySelectorAll('.favorite-product-card');
        
        // Всегда включаем кнопки, если есть товары
        if (productCards.length > 0) {
            elements.prevBtn.disabled = false;
            elements.nextBtn.disabled = false;
            elements.prevBtn.style.opacity = '1';
            elements.nextBtn.style.opacity = '1';
            
            // Для 3+ товаров делаем кнопки всегда активными
            if (productCards.length >= 3) {
                elements.prevBtn.disabled = false;
                elements.nextBtn.disabled = false;
                elements.prevBtn.style.opacity = '1';
                elements.nextBtn.style.opacity = '1';
            }
        }

        const card = document.querySelector('.favorite-product-card');
        const cardWidth = card ? card.offsetWidth : 280;
        const gap = 25;
        const scrollAmount = cardWidth + gap;

        elements.prevBtn.addEventListener('click', () => {
            elements.slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        elements.nextBtn.addEventListener('click', () => {
            elements.slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        function updateSliderButtons() {
            if (productCards.length < 3) {
                const tolerance = 5;
                const showPrev = elements.slider.scrollLeft > tolerance;
                const showNext = elements.slider.scrollLeft < (elements.slider.scrollWidth - elements.slider.clientWidth - tolerance);

                elements.prevBtn.style.opacity = showPrev ? '1' : '0.3';
                elements.prevBtn.disabled = !showPrev;

                elements.nextBtn.style.opacity = showNext ? '1' : '0.3';
                elements.nextBtn.disabled = !showNext;
            }
        }

        elements.slider.addEventListener('scroll', updateSliderButtons);
        window.addEventListener('resize', updateSliderButtons);

        // Инициализация состояния кнопок
        updateSliderButtons();
    }

    function showEmptyFavorites() {
        const emptyHTML = `
            <div class="empty-favorites" style="opacity: 0; transform: translateY(20px);">
                <div class="empty-heart"><i class="far fa-heart"></i></div>
                <h4>У вас пока нет избранных товаров</h4>
                <p>Добавляйте товары в избранное, чтобы не потерять их</p>
                <a href="/product/catalog" class="btn btn-primary">Перейти в каталог</a>
            </div>
        `;
        elements.favoritesContainer.innerHTML = emptyHTML;
        const emptyDiv = elements.favoritesContainer.querySelector('.empty-favorites');
        setTimeout(() => {
            emptyDiv.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            emptyDiv.style.opacity = '1';
            emptyDiv.style.transform = 'translateY(0)';
        }, 10);
    }

    // Обработчик сердечек
    $(document).on('click', '.favorite-heart', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const heart = $(this);
        const productId = heart.data('product-id');
        const card = heart.closest('.favorite-product-card');

        if (heart.hasClass('disabled')) return;
        heart.addClass('disabled');

        $.post('/favorite/toggle', {
            product_id: productId,
            _csrf: $('meta[name="csrf-token"]').attr('content')
        }, function (response) {
            if (response.success) {
                if (response.status === 'added') {
                    heart.addClass('active');
                } else {
                    if (card.length) {
                        card.css({
                            transition: 'all 0.3s ease',
                            transform: 'scale(0.9)',
                            opacity: '0'
                        });

                        setTimeout(() => {
                            card.remove();

                            if ($('.favorite-product-card').length === 0) {
                                showEmptyFavorites();
                            } else {
                                initSlider();
                            }
                        }, 300);
                    } else {
                        heart.removeClass('active');
                    }
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

    // Первая инициализация
    if (elements.favoriteSection.classList.contains('active')) {
        initSlider();
    }
});