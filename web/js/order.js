document.addEventListener('DOMContentLoaded', function() {
    // Инициализация dropdown статусов
    initStatusDropdown();
    // Инициализация dropdown сортировки
    initSortDropdown();
    // Инициализация кликабельных карточек
    initClickableCards();

    function initStatusDropdown() {
        const dropdown = document.querySelector('.status-dropdown');
        const dropbtn = document.querySelector('.status-dropbtn');
        const dropdownContent = document.querySelector('.status-dropdown-content');
        const options = document.querySelectorAll('.status-option');
        const hiddenInput = document.getElementById('status-filter-value');
        const currentStatus = new URL(window.location.href).searchParams.get('OrderSearch[status]');
        const filterForm = document.querySelector('.filter-form');

        // Обновляем текст кнопки
        function updateButtonText(text) {
            dropbtn.innerHTML = text ? `${text}` : `Все статусы `;
        }

        if (currentStatus) {
            const selected = document.querySelector(`.status-option[data-value="${currentStatus}"]`);
            if (selected) {
                options.forEach(opt => opt.classList.remove('active'));
                selected.classList.add('active');
                updateButtonText(selected.textContent);
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
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                options.forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                hiddenInput.value = this.getAttribute('data-value');
                updateButtonText(this.getAttribute('data-value') ? this.textContent : null);
                dropdownContent.style.display = 'none';
                
                // Показываем индикатор загрузки
                showLoadingIndicator('Загрузка заказов...');
                
                // Отправляем форму
                filterForm.submit();
            });
        });
    }

    function initSortDropdown() {
    const dropdown = document.querySelector('.sort-dropdown');
    const dropbtn = document.querySelector('.sort-dropbtn');
    const dropdownContent = dropdown.querySelector('.sort-dropdown-content');
    const options = dropdown.querySelectorAll('.sort-option');
    const filterForm = document.querySelector('.filter-form');

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
            
            // Обновляем URL с новым параметром сортировки
            const url = new URL(window.location.href);
            
            // Устанавливаем параметр сортировки
            if (sort) {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Сохраняем параметр статуса, если он есть
            const statusValue = document.getElementById('status-filter-value').value;
            if (statusValue) {
                url.searchParams.set('OrderSearch[status]', statusValue);
            } else {
                url.searchParams.delete('OrderSearch[status]');
            }
            
            // Показываем индикатор загрузки
            showLoadingIndicator('Применяем сортировку...');
            
            // Переходим по новому URL
            window.location.href = url.toString();
        });
    });
}

    function showLoadingIndicator(message) {
        const orderCards = document.querySelector('.order-cards');
        if (orderCards) {
            orderCards.innerHTML = `<div class="loading-indicator">${message}</div>`;
        }
    }

    function initClickableCards() {
        document.querySelectorAll('.order-card').forEach(card => {
            const detailsBtn = card.querySelector('.btn-details');
            if (detailsBtn) {
                card.addEventListener('click', function(e) {
                    if (!detailsBtn.contains(e.target)) {
                        window.location = detailsBtn.getAttribute('href');
                    }
                });
            }
        });
    }
});
