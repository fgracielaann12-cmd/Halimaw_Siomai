<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Now | Halimaw Siomai</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #858796;
            --success: #1cc88a;
            --danger: #e74a3b;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --border-radius: 5px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
        }

        /* Top Navbar */
        .top-navbar { position: sticky; top: 0; z-index: 1000;
            background: white;
            height: 70px;
            padding: 0 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
        }

        .navbar-brand img {
            height: 45px;
            border-radius: 8px;
        }

        /* Layout */
        .order-layout {
            display: flex;
            gap: 24px;
            padding: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Products Grid */
        .products-section {
            flex: 1;
        }

        .pos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .pos-item-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            padding: 15px;
            text-align: center;
            transition: all 0.25s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .pos-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: var(--primary);
        }

        .pos-item-card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .pos-item-card h6 {
            font-weight: 700;
            font-size: 1rem;
            color: #4a4a4a;
            margin-bottom: 5px;
        }

        .btn-add {
            background-color: var(--success);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-add:hover {
            background-color: #17a673;
        }

        /* Cart Sidebar */
        .cart-sidebar {
            width: 350px;
            flex-shrink: 0;
        }

        .cart-container {
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            position: sticky;
            top: 94px;
        }

        .cart-container h4 {
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f2f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-items {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f2f6;
        }

        .cart-item-details h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .cart-item-price {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            background: #f1f2f6;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .qty-btn:hover {
            background: #e2e4ea;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 15px 0;
            padding-top: 15px;
            border-top: 2px solid #f1f2f6;
        }

        .btn-checkout {
            background: var(--primary);
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 5px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.2s;
        }

        .btn-checkout:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Loading Spinner */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 40px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ============================
           MODAL CSS
           ============================ */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .custom-modal {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            padding: 30px;
            display: flex;
            gap: 30px;
            position: relative;
            transform: translateY(30px) scale(0.95);
            transition: all 0.3s ease;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-overlay.active .custom-modal {
            transform: translateY(0) scale(1);
        }

        .modal-close {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 40px;
            height: 40px;
            background: #666;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 100;
        }
        .modal-close:hover {
            background: #333;
        }

        .modal-left {
            flex: 1;
        }
        .modal-left img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 5px;
            background: #f8f9fa;
        }

        .modal-right {
            flex: 1.2;
            display: flex;
            flex-direction: column;
        }
        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        .modal-price-box {
            background: #f8f9ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .modal-price-box .price {
            font-size: 2.2rem;
            font-weight: 700;
            color: #4e73df;
            margin: 0;
            line-height: 1;
        }

        .modal-details {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 12px 20px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        .modal-details .label {
            color: #777;
            font-weight: 500;
        }
        .modal-details .value {
            color: #333;
            font-weight: 600;
        }

        .pack-options-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        .pack-btn {
            background: white;
            border: 1px solid #ddd;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .pack-btn:hover:not(:disabled) {
            border-color: #4e73df;
            color: #4e73df;
        }
        .pack-btn.active {
            background: #4e73df;
            border-color: #4e73df;
            color: white;
        }
        .pack-btn:disabled {
            background: #f1f2f6;
            color: #aaa;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        .modal-qty-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .modal-qty-controls {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .modal-qty-btn {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            color: #555;
            cursor: pointer;
            transition: background 0.2s;
        }
        .modal-qty-btn:hover { background: #f1f2f6; }
        .modal-qty-input {
            width: 50px;
            height: 40px;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            color: #333;
        }
        .modal-qty-input:focus { outline: none; }

        .btn-modal-add {
            background: #20c997;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.1rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-modal-add:hover {
            background: #1ba87e;
            transform: translateY(-2px);
        }
        .btn-modal-add:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Full-screen Checkout CSS */
        .checkout-fullscreen {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: white;
            z-index: 10000;
            display: flex;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            overflow-y: auto;
        }
        .checkout-fullscreen.active {
            opacity: 1;
            pointer-events: all;
        }
        .checkout-left {
            flex: 1.2;
            padding: 60px 10%;
            background: white;
        }
        .checkout-right {
            flex: 1;
            padding: 60px 5%;
            background: #f8f9fa;
            border-left: 1px solid #e9ecef;
        }
        .checkout-header {
            margin-bottom: 40px;
        }
        .checkout-header h2 { font-weight: 700; margin-bottom: 5px; }
        .checkout-section-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 15px; margin-top: 30px; }
        .form-row { display: flex; gap: 15px; margin-bottom: 15px; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }
        .checkout-btn-back { background: none; border: none; color: var(--primary); font-weight: 600; padding: 0; }
        .checkout-btn-back:hover { text-decoration: underline; }
        
        .checkout-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .checkout-item-img {
            width: 65px; height: 65px; border-radius: 8px; border: 1px solid #ddd;
            position: relative; background: white;
        }
        .checkout-item-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
        .checkout-item-qty {
            position: absolute; top: -8px; right: -8px;
            background: rgba(114, 114, 114, 0.9); color: white;
            width: 20px; height: 20px; border-radius: 50%;
            font-size: 0.75rem; display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }
        .checkout-item-details { flex: 1; }
        .checkout-item-name { font-weight: 600; font-size: 0.95rem; margin-bottom: 2px; }
        .checkout-item-var { font-size: 0.8rem; color: #777; }
        .checkout-item-price { font-weight: 600; font-size: 0.95rem; }

        .checkout-discount { display: flex; gap: 10px; margin: 30px 0; border-bottom: 1px solid #e9ecef; padding-bottom: 30px; }
        .checkout-discount input { flex: 1; }
        .checkout-discount button { background: #e9ecef; border: 1px solid #ddd; color: #555; padding: 10px 20px; border-radius: 8px; font-weight: 600; }
        
        .checkout-summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #555; }
        .checkout-summary-total { display: flex; justify-content: space-between; margin-top: 20px; border-top: 1px solid #e9ecef; padding-top: 20px; font-weight: 700; font-size: 1.3rem; color: #333; }

        .btn-checkout-place { background: var(--primary); color: white; padding: 18px; border: none; border-radius: 8px; width: 100%; font-weight: 700; font-size: 1.1rem; margin-top: 30px; transition: 0.2s; }
        .btn-checkout-place:hover { background: var(--primary-dark); }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
        }

        @media (max-width: 992px) {
            .checkout-fullscreen { flex-direction: column-reverse; }
            .checkout-left, .checkout-right { width: 100%; flex: none; padding: 30px 5%; }
        }
    </style>
    
    
    
    
    
    
    
    
    <!-- UNIFIED 12PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 12px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn, .btn-export, .btn-add-new-item,
        
        /* Textboxes / Inputs */
        input, select, textarea, .form-control, .form-select, .custom-input-group,
        
        /* Tables & Wrappers */
        .table, .table-card, .table-responsive, table, .dataTables_wrapper,
        
        /* Cards & Misc UI */
        .card, .pos-item-card, .summary-card, .img-metric-card, .chart-card-premium, .pos-checkout,
        .alert, .badge, .modal-content, .modal-header, .nav-link, .login-card,
        
        /* Bootstrap Overrides */
        .rounded, .rounded-1, .rounded-2, .rounded-3, .rounded-circle, .rounded-pill,
        .rounded-top, .rounded-bottom, .rounded-start, .rounded-end {
            border-radius: 12px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 12px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* --- UNIFIED TABLE SCROLLING & SIZING FIX --- */
        .table, table {
            font-size: 0.95rem !important;
        }
        .table th, .table td, table th, table td {
            padding: 12px 15px !important;
            vertical-align: middle !important;
        }
        @media (max-width: 991px) {
            .table, table { font-size: 0.9rem !important; }
            .table th, .table td, table th, table td { padding: 0.75rem 0.5rem !important; }
        }
        .table-responsive, .table-responsive-custom {
            max-height: 65vh !important;
            overflow-y: auto !important;
        }
        .table-responsive::-webkit-scrollbar, .table-responsive-custom::-webkit-scrollbar {
            width: 8px; height: 8px;
        }
        .table-responsive::-webkit-scrollbar-track, .table-responsive-custom::-webkit-scrollbar-track {
            background: #f1f1f1; border-radius: 4px; margin: 0 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb, .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #c1c1c1; border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover, .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        /* Sticky Headers */
        .table thead th, table thead th, .table th {
            position: sticky !important;
            top: -1px !important;
            z-index: 10 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            background-color: var(--primary, #4e73df) !important;
            color: white !important;
        }
        /* Fix dropdown clipping globally */
        .controls-section {
            position: relative;
            z-index: 1050 !important;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="navbar-brand">
            <img src="<?= base_url('Images/Inventa.png') ?>" alt="Logo" onerror="this.style.display='none'">
            Halimaw Siomai Online <span style="color: #6c757d; font-weight: 500; margin-left: 5px;">| Menu</span>
        </div>
        <div>
            <span class="badge bg-primary rounded-1 px-3 py-2">
                <i class="bi bi-person-fill me-1"></i> Guest Customer
            </span>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="order-layout">
        
        <!-- Products Grid -->
        <div class="products-section">

            <div id="loading" class="loader"></div>
            <div class="pos-grid" id="productGrid">
                <!-- Products will be injected here by JS -->
            </div>
        </div>

        <!-- Cart Sidebar -->
        <div class="cart-sidebar">
            <div class="cart-container">
                <h4>
                    Shopping Bag
                    <span class="badge bg-danger rounded-1" id="cartCount">0</span>
                </h4>
                
                <div class="cart-items" id="cartItems">
                    <p class="text-center text-muted py-4 my-0">Shopping Bag is empty.</p>
                </div>

                <div class="total-row">
                    <span>Total:</span>
                    <span id="cartTotal">₱0.00</span>
                </div>

                <button class="btn-checkout" id="checkoutBtn" onclick="submitOrder()">
                    <i class="bi bi-bag-check-fill me-2"></i> Checkout
                </button>
            </div>
        </div>

    </div>

    <!-- The Product Modal -->
    <div class="modal-overlay" id="productModalOverlay">
        <div class="custom-modal">
            <button class="modal-close" onclick="closeModal()"><i class="bi bi-x"></i></button>
            
            <div class="modal-left">
                <img id="modalImg" src="" alt="Product">
            </div>
            
            <div class="modal-right">
                <div class="modal-title" id="modalTitle">Product Title</div>
                
                <div class="modal-price-box">
                    <div class="price" id="modalPrice">₱0.00</div>
                </div>

                <div class="modal-details">
                    <div class="label">Left</div>
                    <div class="value" id="modalLeft">0 packs</div>
                    
                    <div class="label" id="lblPieces">Pieces</div>
                    <div class="value" id="modalPieces">-</div>
                    
                    <div class="label" id="lblPack">Pack</div>
                    <div class="value" id="modalPackOptions">
                        <div class="pack-options-grid" id="packButtonsContainer">
                            <!-- Buttons injected here -->
                        </div>
                    </div>

                    <div class="label">Quantity</div>
                    <div class="value">
                        <div class="modal-qty-selector m-0">
                            <div class="modal-qty-controls">
                                <button class="modal-qty-btn" onclick="updateModalQty(-1)">-</button>
                                <input type="text" class="modal-qty-input" id="modalQtyInput" value="1" readonly>
                                <button class="modal-qty-btn" onclick="updateModalQty(1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn-modal-add" id="modalAddBtn" onclick="confirmModalAdd()">
                    <i class="bi bi-bag-plus"></i> Add To Bag
                </button>
            </div>
        </div>
    </div>

    <!-- Full-Screen Checkout Experience -->
    <div class="checkout-fullscreen" id="checkoutFullscreen">
        <div class="checkout-left">
            <div class="checkout-header">
                <button class="checkout-btn-back mb-4" onclick="closeCheckout()"><i class="bi bi-chevron-left"></i> Return to cart</button>
                <h2>Halimaw Siomai Online</h2>
            </div>

            <div class="checkout-section-title">Customer Details</div>
            
            <div class="form-group">
                <label for="chkName" class="mb-2 fw-semibold text-muted">Full Name</label>
                <input type="text" id="chkName" class="form-control" placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="chkPhone" class="mb-2 fw-semibold text-muted">Contact Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><img src="https://flagcdn.com/w20/ph.png" alt="PH"></span>
                    <input type="tel" id="chkPhone" class="form-control" placeholder="09XX XXX XXXX">
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="chkEmail" class="mb-2 fw-semibold text-muted">Email Address</label>
                <input type="email" id="chkEmail" class="form-control" placeholder="example@email.com">
            </div>

            <button class="btn-checkout-place" id="placeOrderBtn" onclick="placeOrderFinal()">
                Place Order
            </button>
        </div>
        
        <div class="checkout-right">
            <div id="checkoutItemsList" class="mb-5">
                <!-- Items populated via JS -->
            </div>

            <div class="checkout-summary-row">
                <span>Subtotal</span>
                <span id="chkSubtotal">₱0.00</span>
            </div>
            <div class="checkout-summary-total">
                <span>Total</span>
                <span id="chkTotal">₱0.00</span>
            </div>
        </div>
    </div>

    <!-- JavaScript to Consume the API -->
    <script>
        let cart = [];
        let products = [];
        let activeProduct = null;
        let activeVariation = null;
        let modalQuantity = 1;

        // 1. Fetch Products
        async function fetchProducts() {
            try {
                const response = await fetch('<?= base_url("api/products") ?>');
                const json = await response.json();
                
                if (json.status === 'success') {
                    products = json.data;
                    renderProducts();
                }
            } catch (error) {
                console.error("Error fetching products:", error);
                document.getElementById('productGrid').innerHTML = '<p class="text-danger">Failed to load menu. Please try again later.</p>';
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        // 2. Render Products Grid (Cards are now just triggers for the Modal)
        function renderProducts() {
            const grid = document.getElementById('productGrid');
            grid.innerHTML = '';

            products.forEach(product => {
                const imgPath = product.image ? `<?= base_url('Images/') ?>${product.image}` : `<?= base_url('Images/default-item.png') ?>`;
                
                let isOutOfStock = false;
                if (product.isSiomai) {
                    isOutOfStock = (product.pack_small_qty <= 0) && (product.pack_medium_qty <= 0) && (product.pack_biggest_qty <= 0);
                } else {
                    isOutOfStock = product.quantity <= 0;
                }
                
                const card = document.createElement('div');
                card.className = `pos-item-card ${isOutOfStock ? 'out-of-stock' : ''}`;
                card.onclick = () => {
                    if (!isOutOfStock) openModal(product.id);
                };

                card.innerHTML = `
                    <img src="${imgPath}" onerror="this.src='<?= base_url('Images/Inventa.png') ?>'" alt="${product.name}">
                    <h6>${product.name}</h6>
                `;

                grid.appendChild(card);
            });
        }

        // 3. Modal Logic
        function openModal(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;
            
            activeProduct = product;
            modalQuantity = 1;
            document.getElementById('modalQtyInput').value = 1;

            // Set Image & Title
            const imgPath = product.image ? `<?= base_url('Images/') ?>${product.image}` : `<?= base_url('Images/default-item.png') ?>`;
            document.getElementById('modalImg').src = imgPath;
            document.getElementById('modalTitle').innerText = product.name;

            const packContainer = document.getElementById('packButtonsContainer');
            packContainer.innerHTML = '';

            if (product.isSiomai) {
                document.getElementById('lblPieces').style.display = 'block';
                document.getElementById('modalPieces').style.display = 'block';
                document.getElementById('lblPack').style.display = 'block';
                document.getElementById('modalPackOptions').style.display = 'block';

                // Create variation buttons
                const variations = [
                    { id: 'Small', name: 'Small Pack', pcs: 12, price: product.pack_small_price, stock: product.pack_small_qty },
                    { id: 'Medium', name: 'Medium Pack', pcs: 20, price: product.pack_medium_price, stock: product.pack_medium_qty },
                    { id: 'Large', name: 'Large Pack', pcs: 40, price: product.pack_biggest_price, stock: product.pack_biggest_qty }
                ];

                let firstAvailable = null;

                variations.forEach(v => {
                    const btn = document.createElement('button');
                    btn.className = 'pack-btn';
                    btn.innerText = v.name;
                    if (v.stock <= 0) {
                        btn.disabled = true;
                    } else {
                        if (!firstAvailable) firstAvailable = v;
                        btn.onclick = () => selectVariation(v);
                    }
                    btn.id = `btn-var-${v.id}`;
                    packContainer.appendChild(btn);
                });

                if (firstAvailable) {
                    selectVariation(firstAvailable);
                    document.getElementById('modalAddBtn').disabled = false;
                } else {
                    document.getElementById('modalPrice').innerText = '₱0.00';
                    document.getElementById('modalLeft').innerText = '0 packs';
                    document.getElementById('modalPieces').innerText = '-';
                    document.getElementById('modalAddBtn').disabled = true;
                    activeVariation = null;
                }

            } else {
                // Non-Siomai
                document.getElementById('lblPieces').style.display = 'none';
                document.getElementById('modalPieces').style.display = 'none';
                document.getElementById('lblPack').style.display = 'none';
                document.getElementById('modalPackOptions').style.display = 'none';

                document.getElementById('modalPrice').innerText = `₱${parseFloat(product.price).toFixed(2)}`;
                document.getElementById('modalLeft').innerText = `${product.quantity} items`;
                
                if (product.quantity <= 0) {
                    document.getElementById('modalAddBtn').disabled = true;
                } else {
                    document.getElementById('modalAddBtn').disabled = false;
                    activeVariation = { id: 'Regular', name: '', price: product.price, stock: product.quantity };
                }
            }

            document.getElementById('productModalOverlay').classList.add('active');
        }

        function selectVariation(variation) {
            activeVariation = variation;
            
            // Update UI
            document.getElementById('modalPrice').innerText = `₱${parseFloat(variation.price).toFixed(2)}`;
            document.getElementById('modalLeft').innerText = `${variation.stock} packs`;
            document.getElementById('modalPieces').innerText = `${variation.pcs} pcs`;

            // Reset buttons active state
            document.querySelectorAll('.pack-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`btn-var-${variation.id}`).classList.add('active');
            
            // Reset quantity to 1 when changing variation
            modalQuantity = 1;
            document.getElementById('modalQtyInput').value = 1;
        }

        function updateModalQty(change) {
            if (!activeVariation) return;
            
            let newQty = modalQuantity + change;
            if (newQty < 1) newQty = 1;
            if (newQty > activeVariation.stock) newQty = activeVariation.stock;
            
            modalQuantity = newQty;
            document.getElementById('modalQtyInput').value = modalQuantity;
        }

        function closeModal() {
            document.getElementById('productModalOverlay').classList.remove('active');
        }

        function confirmModalAdd() {
            if (!activeProduct || !activeVariation) return;

            const baseName = activeProduct.name;
            const varName = activeVariation.name ? ` (${activeVariation.name})` : '';
            const fullName = `${baseName}${varName}`;
            
            const cartItemId = `${activeProduct.id}-${activeVariation.id}`;

            // Check if already in cart to validate total stock
            const existingItem = cart.find(item => item.id === cartItemId);
            const currentCartQty = existingItem ? existingItem.qty : 0;
            
            if (currentCartQty + modalQuantity > activeVariation.stock) {
                alert(`Cannot add more. Only ${activeVariation.stock} available in stock.`);
                return;
            }

            if (existingItem) {
                existingItem.qty += modalQuantity;
            } else {
                cart.push({ id: cartItemId, name: fullName, price: activeVariation.price, qty: modalQuantity });
            }

            renderCart();
            closeModal();
        }

        // 4. Update Quantity in Cart
        function updateQty(id, change) {
            const itemIndex = cart.findIndex(item => item.id === id);
            if (itemIndex > -1) {
                cart[itemIndex].qty += change;
                if (cart[itemIndex].qty <= 0) {
                    cart.splice(itemIndex, 1);
                }
            }
            renderCart();
        }

        // 5. Render Cart Sidebar
        function renderCart() {
            const cartContainer = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');
            const cartTotal = document.getElementById('cartTotal');

            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-center text-muted py-4 my-0">Shopping Bag is empty.</p>';
                cartCount.innerText = '0';
                cartTotal.innerText = '₱0.00';
                return;
            }

            cartContainer.innerHTML = '';
            let total = 0;
            let count = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.qty;
                total += itemTotal;
                count += item.qty;

                cartContainer.innerHTML += `
                    <div class="cart-item">
                        <div class="cart-item-details">
                            <h6>${item.name}</h6>
                            <span class="cart-item-price">₱${itemTotal.toFixed(2)}</span>
                        </div>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="updateQty('${item.id}', -1)">-</button>
                            <span>${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty('${item.id}', 1)">+</button>
                        </div>
                    </div>
                `;
            });

            cartCount.innerText = count;
            cartTotal.innerText = `₱${total.toFixed(2)}`;
        }

        // 6. Checkout Logic
        function submitOrder() {
            if (cart.length === 0) {
                alert("Please add items to your Shopping Bag first.");
                return;
            }
            
            renderCheckoutSummary();
            document.getElementById('checkoutFullscreen').classList.add('active');
            document.body.style.overflow = 'hidden'; // prevent background scrolling
        }

        function closeCheckout() {
            document.getElementById('checkoutFullscreen').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function renderCheckoutSummary() {
            const list = document.getElementById('checkoutItemsList');
            list.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.qty;
                total += itemTotal;
                
                // Try to find image and variation from product ID (format: "productId-variationId")
                const [pId, vId] = item.id.split('-');
                const product = products.find(p => p.id == pId);
                const imgPath = (product && product.image) ? `<?= base_url('Images/') ?>${product.image}` : `<?= base_url('Images/default.jpg') ?>`;
                
                // Parse name into Base Name and Variation (if any)
                let baseName = item.name;
                let varName = '';
                if (item.name.includes('(')) {
                    baseName = item.name.split('(')[0].trim();
                    varName = item.name.split('(')[1].replace(')', '').trim();
                }

                list.innerHTML += `
                    <div class="checkout-item">
                        <div class="checkout-item-img">
                            <img src="${imgPath}" alt="${baseName}">
                            <div class="checkout-item-qty">${item.qty}</div>
                        </div>
                        <div class="checkout-item-details">
                            <div class="checkout-item-name">${baseName}</div>
                            ${varName ? `<div class="checkout-item-var">${varName}</div>` : ''}
                        </div>
                        <div class="checkout-item-price">₱${itemTotal.toFixed(2)}</div>
                    </div>
                `;
            });

            document.getElementById('chkSubtotal').innerText = `₱${total.toFixed(2)}`;
            document.getElementById('chkTotal').innerText = `₱${total.toFixed(2)}`;
        }

        function placeOrderFinal() {
            const name = document.getElementById('chkName').value.trim();
            const phone = document.getElementById('chkPhone').value.trim();
            const email = document.getElementById('chkEmail').value.trim();

            if (!name || !phone || !email) {
                alert("Please fill out all your details before placing the order.");
                return;
            }

            if (cart.length === 0) {
                alert("Your shopping bag is empty!");
                return;
            }

            const btn = document.getElementById('placeOrderBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            btn.disabled = true;

            const orderData = {
                customer_name: name,
                customer_phone: phone,
                customer_email: email,
                items: cart
            };

            fetch('<?= site_url("api/submit-order") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.status === 'success') {
                    alert(`Success! Thank you ${name}, your order has been placed.\nYour Order ID is: ${data.order_id}`);
                    cart = [];
                    renderCart();
                    closeCheckout();
                    
                    // Clear form
                    document.getElementById('chkName').value = '';
                    document.getElementById('chkPhone').value = '';
                    document.getElementById('chkEmail').value = '';
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting order:', error);
                alert("An error occurred while placing your order. Please try again.");
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Initialize
        window.addEventListener('DOMContentLoaded', fetchProducts);
    </script>
</body>
</html>
