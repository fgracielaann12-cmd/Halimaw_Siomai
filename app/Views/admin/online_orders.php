<?= $this->include('templates/header') ?>
<div class="d-flex">
    <?= $this->include('partials/admin_sidebar') ?>
    
    <div class="main-content flex-grow-1">
        <?= $this->include('partials/admin_topbar') ?>

        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0"><i class="bi bi-globe me-2 text-primary"></i> Online Orders Management</h4>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary"><?= $order->order_id ?></td>
                                            <td>
                                                <div class="fw-bold"><?= esc($order->customer_name) ?></div>
                                                <small class="text-muted"><?= esc($order->customer_phone) ?></small>
                                            </td>
                                            <td><?= date('M d, Y h:i A', strtotime($order->created_at)) ?></td>
                                            <td class="fw-bold">₱<?= number_format($order->total_amount, 2) ?></td>
                                            <td>
                                                <?php if ($order->status === 'Pending'): ?>
                                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                                <?php elseif ($order->status === 'Completed'): ?>
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">Completed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary px-3 py-2 rounded-pill"><?= $order->status ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewOrder('<?= $order->order_id ?>')">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    <?php if ($order->status === 'Pending'): ?>
                                                        <a href="<?= site_url('admin/online-orders/confirm/' . $order->id) ?>" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-lg"></i>
                                                        </a>
                                                        <a href="<?= site_url('admin/online-orders/cancel/' . $order->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this order?')">
                                                            <i class="bi bi-x-lg"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                            <p class="text-muted">No online orders found.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="modalOrderId">Order Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="modalBody">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-bold">Customer Information</label>
                        <h5 id="modalCustName" class="fw-bold mb-1">-</h5>
                        <div id="modalCustEmail" class="text-muted">-</div>
                        <div id="modalCustPhone" class="text-muted">-</div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="text-muted small text-uppercase fw-bold">Order Summary</label>
                        <h4 id="modalTotal" class="fw-bold text-primary mb-0">₱0.00</h4>
                        <div id="modalDate" class="text-muted small">-</div>
                    </div>
                </div>
                
                <label class="text-muted small text-uppercase fw-bold mb-2">Items Ordered</label>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Variation</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function viewOrder(orderId) {
    try {
        const response = await fetch(`<?= site_url('admin/online-orders/view/') ?>${orderId}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('modalOrderId').innerText = `Order: ${data.order.order_id}`;
            document.getElementById('modalCustName').innerText = data.order.customer_name;
            document.getElementById('modalCustEmail').innerText = data.order.customer_email || 'No Email';
            document.getElementById('modalCustPhone').innerText = data.order.customer_phone;
            document.getElementById('modalTotal').innerText = `₱${parseFloat(data.order.total_amount).toFixed(2)}`;
            document.getElementById('modalDate').innerText = data.order.created_at;

            const itemsBody = document.getElementById('modalItemsBody');
            itemsBody.innerHTML = '';
            
            data.items.forEach(item => {
                itemsBody.innerHTML += `
                    <tr>
                        <td class="fw-bold">${item.product_name}</td>
                        <td><span class="badge bg-light text-dark border">${item.variation || 'Regular'}</span></td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">₱${parseFloat(item.price).toFixed(2)}</td>
                        <td class="text-end fw-bold">₱${parseFloat(item.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });

            new bootstrap.Modal(document.getElementById('orderModal')).show();
        }
    } catch (error) {
        console.error('Error fetching order:', error);
        alert('Failed to load order details');
    }
}
</script>

<?= $this->include('templates/footer') ?>
