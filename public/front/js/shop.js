document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || form.dataset.skipLock === '1') {
        return;
    }
    if (form.dataset.submitted === '1') {
        event.preventDefault();
        return;
    }
    form.dataset.submitted = '1';
    const button = event.submitter || form.querySelector('[type="submit"]');
    if (button) {
        button.disabled = true;
    }
});

window.addEventListener('pageshow', function () {
    document.querySelectorAll('form[data-submitted]').forEach(function (form) {
        delete form.dataset.submitted;
        form.querySelectorAll('[type="submit"]').forEach(function (button) {
            button.disabled = false;
        });
    });
});

document.querySelectorAll('[data-gallery]').forEach(function (button) {
    button.addEventListener('click', function () {
        const photo = document.getElementById('product-photo');
        if (!photo) return;
        photo.src = button.dataset.src;
        document.querySelectorAll('[data-gallery]').forEach(function (item) {
            item.classList.remove('is-selected');
        });
        button.classList.add('is-selected');
    });
});

document.querySelectorAll('#color-options button').forEach(function (button) {
    button.addEventListener('click', function () {
        const id = document.getElementById('color-id');
        const label = document.getElementById('stock-label');
        const qty = document.getElementById('quantity');
        document.querySelectorAll('#color-options button').forEach(function (item) {
            item.classList.remove('is-selected');
            item.setAttribute('aria-pressed', 'false');
        });
        button.classList.add('is-selected');
        button.setAttribute('aria-pressed', 'true');
        if (id) id.value = button.dataset.color;
        const stock = Number(button.dataset.stock || 0);
        if (label) {
            label.textContent = stock > 0 ? stock + ' in stock' : 'Out of stock';
            label.classList.toggle('text-green-700', stock > 0);
            label.classList.toggle('text-red-700', stock <= 0);
        }
        if (qty) qty.max = String(Math.max(1, Math.min(10, stock)));
    });
});
