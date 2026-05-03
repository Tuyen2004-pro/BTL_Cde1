let cart = [];

// ===== LOAD JSON TỪ HTML =====
function loadInitialCartFromHTML() {
    let el = document.getElementById("initial-cart-data");

    if (!el) return;

    try {
        let data = JSON.parse(el.textContent);
        cart = data || [];
        updatePreview();
    } catch (e) {
        console.error("JSON parse lỗi:", e);
        cart = [];
    }
}

// ===== FILTER =====
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("filter-btn")) {
        let id = e.target.dataset.id;

        document.querySelectorAll(".product-item").forEach(p => {
            p.style.display =
                (id === "all" || p.dataset.category == id) ? "block" : "none";
        });
    }
});

// ===== ADD TO CART =====
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-to-cart")) {

        let btn = e.target;
        let modal = btn.closest(".modal");

        let sizeSelect = modal.querySelector(".size");
        let sizeOption = sizeSelect.selectedOptions[0];

        let item = {
            product_id: btn.dataset.id,
            name: btn.dataset.name,
            base_price: parseInt(btn.dataset.price),
            size_id: sizeOption.value,
            size: sizeOption.text,
            extra: parseInt(sizeOption.dataset.price || 0),
            sugar: modal.querySelector(".sugar").value,
            ice: modal.querySelector(".ice").value,
            qty: parseInt(modal.querySelector(".qty").value)
        };

        cart.push(item);
        updatePreview();

        bootstrap.Modal.getInstance(modal).hide();
    }
});

// ===== REMOVE =====
function removeItem(index) {
    cart.splice(index, 1);
    updatePreview();
}

// ===== UPDATE UI =====
function updatePreview() {

    let preview = document.getElementById('order-preview');
    let totalEl = document.getElementById('total-price');
    let itemsContainer = document.getElementById('items-container');

    if (!preview) return;

    let html = '';
    let inputs = '';
    let total = 0;

    // reset badge
    document.querySelectorAll('.qty-badge').forEach(b => {
        b.style.display = 'none';
        b.querySelector('.badge-qty').innerText = 0;
    });

    cart.forEach((item, index) => {

        let price = item.base_price + item.extra;

        // badge cộng dồn
        let badge = document.getElementById('badge-' + item.product_id);
        if (badge) {
            badge.style.display = 'inline-block';

            let current = parseInt(badge.querySelector('.badge-qty').innerText) || 0;
            badge.querySelector('.badge-qty').innerText = current + item.qty;
        }

        html += `
        <div style="border-bottom:1px solid #eee;padding:5px 0">
            <b>${item.name}</b> x${item.qty}
            <button type="button" onclick="removeItem(${index})" style="float:right">❌</button>
            <br>
            <small>${item.size} (${price.toLocaleString()}đ) | ${item.sugar} | ${item.ice}</small>
        </div>
        `;

        inputs += `
        <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
        <input type="hidden" name="items[${index}][size_id]" value="${item.size_id}">
        <input type="hidden" name="items[${index}][sugar]" value="${item.sugar}">
        <input type="hidden" name="items[${index}][ice]" value="${item.ice}">
        <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
        `;

        total += price * item.qty;
    });

    preview.innerHTML = html || '<p class="text-muted">Chưa có món</p>';
    itemsContainer.innerHTML = inputs;
    totalEl.innerText = total.toLocaleString() + 'đ';
}

// ===== INIT =====
document.addEventListener("DOMContentLoaded", function () {
    loadInitialCartFromHTML();
});
