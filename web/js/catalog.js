document.addEventListener('DOMContentLoaded', function () {
    
    const searchInput = document.getElementById('product-search');
    const searchClear = document.querySelector('.search-clear');
    const searchForm = document.querySelector('.search-form');
    const productGrid = document.querySelector('.product-grid');
    let searchTimeout;

    initEventListeners();
    animateCards();
    initClickableCards();
    initCategoryDropdown();
    initSortDropdown();

    function initEventListeners() {
        searchInput.addEventListener('input', handleSearchInput);
        searchClear.addEventListener('click', clearSearch);
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            updateSearch();
        });

    }

    function handleSearchInput() {
        searchClear.style.display = this.value ? 'block' : 'none';
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateSearch, 1000);
    }

    function clearSearch() {
        searchInput.value = '';
        searchClear.style.display = 'none';
        const url = new URL(window.location.href);
        url.searchParams.delete('ProductSearch[name]');
        window.history.pushState({}, '', url);
        updateSearch();
    }

    function updateSearch() {
        const searchTerm = searchInput.value;
        const categoryValue = document.getElementById('category-filter-value').value;
        const url = new URL(window.location.href);
        showLoadingIndicator('Идет поиск...');

        if (searchTerm) {
            url.searchParams.set('ProductSearch[name]', searchTerm);
        } else {
            url.searchParams.delete('ProductSearch[name]');
        }

        if (categoryValue) {
            url.searchParams.set('ProductSearch[type_id]', categoryValue);
        } else {
            url.searchParams.delete('ProductSearch[type_id]');
        }

        const currentSort = url.searchParams.get('sort');
        const currentDirection = url.searchParams.get('direction');
        if (currentSort && currentDirection) {
            url.searchParams.set('sort', currentSort);
            url.searchParams.set('direction', currentDirection);
        }

        window.history.pushState({}, '', url);
        fetchData(url);
    }

    function fetchData(url) {
        productGrid.querySelectorAll('.product-card').forEach(card => {
            card.style.transition = 'opacity 0.3s ease';
            card.style.opacity = '0';
        });

        showLoadingIndicator('Идет поиск...');

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Ошибка сети');
                return res.text();
            })
            .then(html => {
                showSearchResults(html);
            })
            .catch(() => {
                showError('Произошла ошибка при загрузке данных');
            });
    }

   function showSearchResults(html) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const newContent = doc.querySelector('.product-grid').innerHTML;

    // Сохраняем текущее состояние избранного
    const currentFavorites = {};
    document.querySelectorAll('.favorite-heart.active').forEach(heart => {
        const productId = heart.getAttribute('data-product-id');
        if (productId) currentFavorites[productId] = true;
    });

    productGrid.innerHTML = newContent;
    
    // Восстанавливаем состояние избранного
    document.querySelectorAll('.favorite-heart').forEach(heart => {
        const productId = heart.getAttribute('data-product-id');
        if (productId && currentFavorites[productId]) {
            heart.classList.add('active');
        }
    });
    
    animateCards();
    initClickableCards();
}

    function showLoadingIndicator(message) {
        productGrid.innerHTML = `<div class="loading-indicator">${message}</div>`;
    }

    function showError(message) {
        productGrid.innerHTML = `<div class="error-message">${message}</div>`;
    }

    function animateCards() {
        document.querySelectorAll('.product-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    function initClickableCards() {
    document.querySelectorAll('.clickable-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.quantity-control') || 
                e.target.closest('.btn') || 
                e.target.closest('.favorite-heart')) {
                return;
            }
            const url = card.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    });
}

   

   function initSortDropdown() {
    const dropdown = document.querySelector('.sort-dropdown');
    const dropbtn = document.querySelector('.sort-dropbtn');
    const dropdownContent = dropdown.querySelector('.sort-dropdown-content');
    const options = dropdown.querySelectorAll('.sort-option');

    // Проверяем текущие параметры сортировки из URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort');

    // Устанавливаем активную опцию сортировки
    if (currentSort) {
        const selectedOption = document.querySelector(`.sort-option[data-sort="${currentSort}"]`);
        if (selectedOption) {
            options.forEach(opt => opt.classList.remove('active'));
            selectedOption.classList.add('active');
            dropbtn.textContent = selectedOption.textContent.trim();
        }
    }

    // Обработчики событий для dropdown
    dropdown.addEventListener('mouseenter', () => dropdownContent.style.display = 'block');
    dropdown.addEventListener('mouseleave', () => dropdownContent.style.display = 'none');
    dropbtn.addEventListener('click', e => {
        e.stopPropagation();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', () => dropdownContent.style.display = 'none');
    dropdownContent.addEventListener('click', e => e.stopPropagation());

    // Обработчики для опций сортировки
    options.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const sort = this.getAttribute('data-sort');
            
            // Обновляем активную опцию
            options.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            dropbtn.textContent = this.textContent.trim();
            
            // Создаем URL с новыми параметрами
            const url = new URL(window.location.href);
            
            // Устанавливаем параметр сортировки
            if (sort) {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Сохраняем параметры поиска и категории, если они есть
            const searchValue = document.getElementById('product-search').value;
            if (searchValue) {
                url.searchParams.set('ProductSearch[name]', searchValue);
            }
            
            const categoryValue = document.getElementById('category-filter-value').value;
            if (categoryValue) {
                url.searchParams.set('ProductSearch[type_id]', categoryValue);
            }
            
            // Показываем индикатор загрузки
            showLoadingIndicator('Применяем сортировку...');
            
            // Переходим по новому URL
            window.location.href = url.toString();
        });
    });
}

    function initCategoryDropdown() {
        const dropdown = document.querySelector('.category-dropdown');
        const dropbtn = document.querySelector('.category-dropbtn');
        const dropdownContent = dropdown.querySelector('.category-dropdown-content');
        const options = document.querySelectorAll('.category-option');
        const hiddenInput = document.getElementById('category-filter-value');
        const currentCategory = new URL(window.location.href).searchParams.get('ProductSearch[type_id]');

        if (currentCategory) {
            const selected = document.querySelector(`.category-option[data-value="${currentCategory}"]`);
            if (selected) {
                selected.classList.add('active');
                dropbtn.innerHTML = ` ${selected.textContent} `;
            }
        }

        dropdown.addEventListener('mouseenter', () => dropdownContent.style.display = 'block');
        dropdown.addEventListener('mouseleave', () => dropdownContent.style.display = 'none');
        dropbtn.addEventListener('click', e => {
            e.stopPropagation();
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', () => dropdownContent.style.display = 'none');
        dropdownContent.addEventListener('click', e => e.stopPropagation());

        options.forEach(option => {
            option.addEventListener('click', function (e) {
                e.stopPropagation();
                options.forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                dropbtn.innerHTML = ` ${this.textContent}`;
                hiddenInput.value = this.getAttribute('data-value');
                dropdownContent.style.display = 'none';
                updateSearch();
            });
        });
    }
});

// jQuery часть
$(document).ready(function () {
    $(document).on('click', '.add-to-cart', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const productId = $(this).data('product-id');
        $.post('/cart/create', {
            product_id: productId,
            _csrf: yii.getCsrfToken()
        }, function (response) {
            if (response.success) {
                $(`.add-to-cart[data-product-id="${productId}"]`).replaceWith(`
                    <div class="quantity-control" data-product-id="${productId}">
                        <button class="quantity-btn minus" type="button">-</button>
                        <span class="quantity">${response.count}</span>
                        <button class="quantity-btn plus" type="button">+</button>
                    </div>
                `);
            } else {
                alert(response.error || 'Произошла ошибка');
            }
        }).fail(() => alert('Ошибка соединения с сервером'));
    });

    $(document).on('click', '.quantity-btn.plus, .quantity-btn.minus', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const control = $(this).closest('.quantity-control');
        const productId = control.data('product-id');
        const change = $(this).hasClass('plus') ? 1 : -1;

        $.post('/cart/update?id=' + productId, {
            change: change,
            _csrf: yii.getCsrfToken()
        }, function (response) {
            if (response.success) {
                if (response.count === undefined) {
                    control.replaceWith(`
                        <button class="btn btn-cart add-to-cart" data-product-id="${productId}" type="button">
                            В корзину
                        </button>
                    `);
                } else {
                    control.find('.quantity').text(response.count);
                }
            } else {
                alert(response.error || 'Произошла ошибка');
            }
        }).fail(() => alert('Ошибка соединения с сервером'));
    });

    $(document).on('click', '.clickable-card', function (e) {
        if ($(e.target).closest('.quantity-control, .add-to-cart, .btn-details').length === 0) {
            window.location = $(this).data('url');
        }
    });
});
// Обработчик для избранного
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
            } else {
                heart.removeClass('active');
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