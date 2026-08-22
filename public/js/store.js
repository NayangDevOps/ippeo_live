(function () {
  const KEY = 'ippeo_cart_v2';
  const money = (n) => '₹' + Number(n).toLocaleString('en-IN');

  function getCart() {
    try { return JSON.parse(localStorage.getItem(KEY) || '[]'); } catch { return []; }
  }
  function save(items) {
    localStorage.setItem(KEY, JSON.stringify(items));
    updateCount();
  }
  function updateCount() {
    const n = getCart().reduce((s, i) => s + i.qty, 0);
    document.querySelectorAll('.cart-count').forEach((el) => { el.textContent = String(n); });
  }
  function showToast(msg) {
    let t = document.getElementById('toast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'toast'; t.className = 'toast'; t.setAttribute('role', 'status');
      document.body.appendChild(t);
    }
    t.textContent = msg; t.classList.add('show');
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => t.classList.remove('show'), 2200);
  }
  function add(item) {
    const items = getCart();
    const ex = items.find((i) => Number(i.id) === Number(item.id));
    if (ex) ex.qty += item.qty || 1;
    else items.push({ id: Number(item.id), name: item.name, price: Number(item.price), image: item.image, qty: item.qty || 1 });
    save(items);
    showToast((item.name || 'Product') + ' added to cart');
  }
  function setQty(id, qty) {
    save(getCart().map((i) => Number(i.id) === Number(id) ? { ...i, qty } : i).filter((i) => i.qty > 0));
  }
  function remove(id) {
    save(getCart().filter((i) => Number(i.id) !== Number(id)));
    showToast('Item removed');
  }
  function clear() { save([]); }
  function totals(opts = {}) {
    const freeMin = Number(opts.freeShippingMin ?? window.IppeoShipping?.freeShippingMin ?? 499);
    const shipFee = Number(opts.shippingFee ?? window.IppeoShipping?.shippingFee ?? 49);
    const items = getCart();
    const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
    const shipping = subtotal >= freeMin || subtotal === 0 ? 0 : shipFee;
    return { items, subtotal, shipping, total: subtotal + shipping };
  }
  function renderPage() {
    const root = document.getElementById('cartRoot');
    if (!root) return;
    const { items, subtotal, shipping, total } = totals();
    if (!items.length) {
      root.innerHTML = '<div class="empty-state content-card"><p>Your cart is empty.</p><a class="btn btn-primary" href="/shop">Continue Shopping</a></div>';
      return;
    }
    root.innerHTML = `
      <div class="cart-layout">
        <div class="content-card">
          <table class="cart-table">
            <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
            <tbody>
              ${items.map((i) => `
                <tr>
                  <td><div class="cart-item"><img src="${i.image}" alt=""/><strong>${i.name}</strong></div></td>
                  <td>${money(i.price)}</td>
                  <td><div class="qty-control">
                    <button type="button" data-dec="${i.id}">−</button>
                    <input value="${i.qty}" readonly />
                    <button type="button" data-inc="${i.id}">+</button>
                  </div></td>
                  <td><strong>${money(i.price * i.qty)}</strong></td>
                  <td><button class="icon-btn" type="button" data-remove="${i.id}">✕</button></td>
                </tr>`).join('')}
            </tbody>
          </table>
        </div>
        <aside class="content-card">
          <h3 style="margin-top:0;color:var(--green)">Order Summary</h3>
          <div class="summary-row"><span>Subtotal</span><span>${money(subtotal)}</span></div>
          <div class="summary-row"><span>Shipping</span><span>${shipping ? money(shipping) : 'FREE'}</span></div>
          <div class="summary-row total"><span>Total</span><span>${money(total)}</span></div>
          <a class="btn btn-primary btn-wide" href="/checkout">Proceed to Checkout</a>
        </aside>
      </div>`;
    root.querySelectorAll('[data-inc]').forEach((b) => b.onclick = () => {
      const it = getCart().find((x) => Number(x.id) === Number(b.dataset.inc));
      setQty(b.dataset.inc, (it?.qty || 0) + 1); renderPage();
    });
    root.querySelectorAll('[data-dec]').forEach((b) => b.onclick = () => {
      const it = getCart().find((x) => Number(x.id) === Number(b.dataset.dec));
      setQty(b.dataset.dec, (it?.qty || 1) - 1); renderPage();
    });
    root.querySelectorAll('[data-remove]').forEach((b) => b.onclick = () => { remove(b.dataset.remove); renderPage(); });
  }
  function renderCheckout(opts = {}) {
    if (opts.freeShippingMin != null || opts.shippingFee != null) {
      window.IppeoShipping = {
        freeShippingMin: opts.freeShippingMin ?? 499,
        shippingFee: opts.shippingFee ?? 49,
      };
    }
    const box = document.getElementById('checkoutSummary');
    const inputs = document.getElementById('cartItemsInputs');
    const form = document.getElementById('checkoutForm');
    if (!box || !inputs || !form) return;
    const { items, subtotal, shipping, total } = totals(opts);
    if (!items.length) {
      box.innerHTML = '<p>Your cart is empty.</p><a class="btn btn-primary" href="/shop">Shop Now</a>';
      form.style.display = 'none';
      return;
    }
    inputs.innerHTML = items.map((i, idx) => `
      <input type="hidden" name="items[${idx}][id]" value="${i.id}" />
      <input type="hidden" name="items[${idx}][qty]" value="${i.qty}" />`).join('');
    box.innerHTML = `
      <h3 style="margin-top:0;color:var(--green)">Your Order</h3>
      ${items.map((i) => `<div class="summary-row"><span>${i.name} × ${i.qty}</span><span>${money(i.price * i.qty)}</span></div>`).join('')}
      <div class="summary-row"><span>Subtotal</span><span>${money(subtotal)}</span></div>
      <div class="summary-row"><span>Shipping</span><span>${shipping ? money(shipping) : 'FREE'}</span></div>
      <div class="summary-row total"><span>Total</span><span>${money(total)}</span></div>`;
  }

  window.IppeoCart = { add, setQty, remove, clear, totals, renderPage, renderCheckout, updateCount, showToast };

  document.addEventListener('DOMContentLoaded', () => {
    updateCount();

    // Sticky header scroll polish
    const header = document.getElementById('siteHeader');
    const onScroll = () => {
      if (!header) return;
      header.classList.toggle('is-scrolled', window.scrollY > 24);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const drawer = document.getElementById('mobileDrawer');
    const overlay = document.getElementById('drawerOverlay');
    const menuBtn = document.getElementById('menuToggle');
    const closeBtn = document.getElementById('menuClose');

    function openMenu() {
      if (!drawer || !overlay) return;
      overlay.hidden = false;
      overlay.classList.add('is-open');
      drawer.classList.add('is-open');
      drawer.setAttribute('aria-hidden', 'false');
      menuBtn?.setAttribute('aria-expanded', 'true');
      document.body.classList.add('menu-open');
      document.documentElement.style.filter = 'none';
      document.body.style.filter = 'none';
    }

    function closeMenu() {
      if (!drawer || !overlay) return;
      drawer.classList.remove('is-open');
      overlay.classList.remove('is-open');
      drawer.setAttribute('aria-hidden', 'true');
      menuBtn?.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('menu-open');
      document.documentElement.style.filter = 'none';
      document.body.style.filter = 'none';
      overlay.hidden = true;
    }

    // Ensure page never stays dimmed/blurred after reload
    closeMenu();

    menuBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      if (drawer?.classList.contains('is-open')) closeMenu();
      else openMenu();
    });
    closeBtn?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);
    drawer?.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMenu(); });

    document.getElementById('backTop')?.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    document.body.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-add]');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();
      const qty = Math.max(1, parseInt(btn.dataset.qty || '1', 10) || 1);
      add({
        id: btn.dataset.add,
        name: btn.dataset.name,
        price: btn.dataset.price,
        image: btn.dataset.image,
        qty
      });
    });
  });
})();
