@extends('layouts.admintemplate')
@section('title','Point of Sale - Penjualan')
@section('content')
@section('styles')
<style>
.product-card {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-img-wrapper {
  height: 120px;
  overflow: hidden;
}

.card-img-top {
  height: 100%;
  object-fit: cover;
}

.cart-item {
  transition: background-color 0.2s;
}

.cart-item:hover {
  background-color: #f8f9fa;
}

.qty-btn {
  width: 30px;
  height: 30px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-pills .nav-link {
  border-radius: 20px;
  margin-right: 8px;
}

.nav-pills .nav-link.active {
  background-color: #696cff;
}

@media (max-width: 768px) {
  .col-6 {
    flex: 0 0 50%;
  }
  
  .card-body.p-2 {
    padding: 0.75rem !important;
  }
}
</style>
@endsection

<div class="row g-3">
  <div class="col-lg-5 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
        <h5 class="mb-0 text-white">
          <i class="bx bx-shopping-bag me-2"></i>Keranjang
        </h5>
      </div>
      <div class="card-body p-0">
        <!-- Customer Selection -->
        <div class="p-3 border-bottom">

          <div class="mb-3">
            <label class="form-label fw-semibold">No. Invoice</label>
            <div class="col-sm-10">
              <input type="text" name="noinvoice" class="form-control" value="INV{{ date('YmdHis') }}" readonly>
            </div>
          </div>


          <div class="mb-3">
            <label class="form-label fw-semibold">Pelanggan</label>
            <select name="idpelanggan" class="form-select" id="customer-select">
              <option value="">-- Pilih Pelanggan --</option>
              <!-- Dynamic options here -->
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">No. HP</label>
            <input type="text" name="nohp" class="form-control" id="customer-phone" placeholder="No. HP Pelanggan">
          </div>
        </div>

        <!-- Cart Items -->
        <div class="cart-items" style="max-height: 300px; overflow-y: auto;">
          <div class="text-center p-4" id="empty-cart">
            <i class="bx bx-cart text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Keranjang masih kosong</p>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="p-3 border-top bg-light">
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span class="fw-bold" id="subtotal">Rp 0</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Total Items:</span>
            <span class="fw-bold" id="total-items">0</span>
          </div>
          <button class="btn btn-success w-100" id="process-order" disabled>
            <i class="bx bx-check-circle me-2"></i>Proses Pesanan
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-7 col-md-12">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Katalog Produk</h5>
      </div>
      <div class="card-body">
        <!-- Search Bar -->
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text"><i class="bx bx-search"></i></span>
            <input type="text" class="form-control" placeholder="Cari produk" id="search-products">
          </div>
        </div>

        <!-- Category Tabs -->
        <div class="mb-3">
            <ul class="nav nav-pills" id="category-tabs">
                <li class="nav-item">
                <button class="nav-link active" data-category="all">Semua</button>
                </li>
                <li class="nav-item">
                <button class="nav-link" data-category="Makanan">Makanan</button>
                </li>
                <li class="nav-item">
                <button class="nav-link" data-category="minuman">Minuman</button>
                </li>
                {{-- @foreach($categories as $category)
                <li class="nav-item">
                <button class="nav-link" data-category="{{ $category }}">{{ ucfirst($category) }}</button>
                </li>
                @endforeach --}}
            </ul>
        </div>
        <div class="row g-3" id="product-grid" style="max-height: 500px; overflow-y: auto;">
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="minuman" data-id="1" data-name="Iced Coffee" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Iced Coffee</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="minuman" data-id="1" data-name="Iced Coffee" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Iced Coffee</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="minuman" data-id="1" data-name="Iced Coffee" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Iced Coffee</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="minuman" data-id="1" data-name="Iced Coffee" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Iced Coffee</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="minuman" data-id="1" data-name="Iced Coffee" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Iced Coffee</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card h-100" data-category="Makanan" data-id="2" data-name="Es Kopi" data-price="15000">
              <div class="card-img-wrapper position-relative">
                <img src="{{ asset('foto/foto.jpg') }}" class="card-img-top" alt="Iced Coffee">
              </div>
              <div class="card-body p-2">
                <h6 class="card-title mb-1 text-truncate">Es Kopi</h6>
                <p class="card-text text-primary fw-bold mb-2">Rp 15.000</p>
                <button class="btn btn-outline-primary btn-sm w-100 add-to-cart">
                  <i class="bx bx-plus"></i> Tambah
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Hidden Form for Submission -->
<form id="sales-form" action="{{ url('bookingtambahsimpan') }}" method="POST" style="display: none;">
  @csrf
  <input type="hidden" name="idpelanggan" id="form-customer">
  <input type="hidden" name="nohp" id="form-phone">
  <input type="hidden" name="noinvoice" value="INV{{ date('YmdHis') }}">
  <input type="hidden" name="tanggalbooking" value="{{ date('Y-m-d') }}">
  <input type="hidden" name="items" id="form-items">
</form>

@endsection

@section('script')
<script>
  let cart = [];
  let cartTotal = 0;

  // Add to cart functionality
  document.addEventListener('click', function(e) {
    if (e.target.closest('.add-to-cart')) {
      e.preventDefault();
      const productCard = e.target.closest('.product-card');
      const product = {
        id: productCard.dataset.id,
        name: productCard.dataset.name,
        price: parseInt(productCard.dataset.price),
        quantity: 1
      };
      
      addToCart(product);
    }

    // Remove from cart
    if (e.target.closest('.remove-from-cart')) {
      e.preventDefault();
      const itemId = e.target.closest('.cart-item').dataset.id;
      removeFromCart(itemId);
    }

    // Update quantity
    if (e.target.classList.contains('qty-btn')) {
      e.preventDefault();
      const cartItem = e.target.closest('.cart-item');
      const itemId = cartItem.dataset.id;
      const action = e.target.dataset.action;
      updateQuantity(itemId, action);
    }

    // Category filter
    if (e.target.hasAttribute('data-category')) {
      e.preventDefault();
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
      e.target.classList.add('active');
      filterProducts(e.target.dataset.category);
    }
  });

  function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      cart.push(product);
    }
    
    updateCartDisplay();
    showToast('success', `${product.name} ditambahkan ke keranjang`);
  }

  function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    updateCartDisplay();
    showToast('info', 'Item dihapus dari keranjang');
  }

  function updateQuantity(itemId, action) {
    const item = cart.find(item => item.id === itemId);
    if (!item) return;

    if (action === 'increase') {
      item.quantity += 1;
    } else if (action === 'decrease' && item.quantity > 1) {
      item.quantity -= 1;
    }
    
    updateCartDisplay();
  }

  function updateCartDisplay() {
    const cartItemsContainer = document.querySelector('.cart-items');
    const emptyCart = document.getElementById('empty-cart');
    const cartCount = document.getElementById('cart-count');
    const subtotal = document.getElementById('subtotal');
    const totalItems = document.getElementById('total-items');
    const processBtn = document.getElementById('process-order');

    if (cart.length === 0) {
      cartItemsContainer.innerHTML = '<div class="text-center p-4" id="empty-cart"><i class="bx bx-cart text-muted" style="font-size: 3rem;"></i><p class="text-muted mt-2">Keranjang masih kosong</p></div>';
      cartCount.textContent = '0';
      subtotal.textContent = 'Rp 0';
      totalItems.textContent = '0';
      processBtn.disabled = true;
      return;
    }

    let html = '';
    let total = 0;
    let itemCount = 0;

    cart.forEach(item => {
      const itemTotal = item.price * item.quantity;
      total += itemTotal;
      itemCount += item.quantity;

      html += `
        <div class="cart-item border-bottom p-3" data-id="${item.id}">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-1">${item.name}</h6>
            <button class="btn btn-outline-danger btn-sm remove-from-cart">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="decrease">-</button>
              <span class="mx-2 fw-bold">${item.quantity}</span>
              <button class="btn btn-outline-secondary btn-sm qty-btn" data-action="increase">+</button>
            </div>
            <div class="text-end">
              <div class="text-muted small">Rp ${numberFormat(item.price)}</div>
              <div class="fw-bold">Rp ${numberFormat(itemTotal)}</div>
            </div>
          </div>
        </div>
      `;
    });

    cartItemsContainer.innerHTML = html;
    cartCount.textContent = itemCount;
    subtotal.textContent = `Rp ${numberFormat(total)}`;
    totalItems.textContent = itemCount;
    processBtn.disabled = false;
    cartTotal = total;
  }

  function filterProducts(category) {
    const products = document.querySelectorAll('.product-card');
    products.forEach(product => {
      if (category === 'all' || product.dataset.category === category) {
        product.closest('.col-6').style.display = 'block';
      } else {
        product.closest('.col-6').style.display = 'none';
      }
    });
  }

  // Search functionality
  document.getElementById('search-products').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
      const productName = product.dataset.name.toLowerCase();
      if (productName.includes(searchTerm)) {
        product.closest('.col-6').style.display = 'block';
      } else {
        product.closest('.col-6').style.display = 'none';
      }
    });
  });

  // Process order
  document.getElementById('process-order').addEventListener('click', function() {
    const customer = document.getElementById('customer-select').value;
    const phone = document.getElementById('customer-phone').value;

    if (!phone.trim()) {
      showToast('error', 'Harap isi nomor HP pelanggan');
      return;
    }

    if (cart.length === 0) {
      showToast('error', 'Keranjang masih kosong');
      return;
    }

    // Prepare form data
    document.getElementById('form-customer').value = customer;
    document.getElementById('form-phone').value = phone;
    document.getElementById('form-items').value = JSON.stringify(cart);

    // Submit form
    document.getElementById('sales-form').submit();
  });

  function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
  }

  function showToast(type, message) {
    // Simple toast notification (you can replace with your preferred toast library)
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
      <div class="d-flex align-items-center">
        <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'} me-2"></i>
        ${message}
      </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }

  // Refresh products
  document.getElementById('refresh-products').addEventListener('click', function() {
    showToast('info', 'Produk telah diperbarui');
  });
</script>
@endsection