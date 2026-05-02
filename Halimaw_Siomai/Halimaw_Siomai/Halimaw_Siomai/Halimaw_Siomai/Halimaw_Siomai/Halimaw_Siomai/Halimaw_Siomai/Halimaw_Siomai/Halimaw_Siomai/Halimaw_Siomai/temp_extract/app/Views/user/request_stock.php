<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Stock Adjustment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light p-4">

<div class="container">
    <h2 class="mb-3">📦 Request Stock Adjustment</h2>

    <!-- Stock Request Form -->
    <form id="stockRequestForm" class="card p-4 shadow-sm mb-4">
        <div class="mb-3">
            <label for="item_id" class="form-label">Select Item</label>
            <input type="text" class="form-control" id="item_id" name="item_id" placeholder="Enter Item ID" required>
        </div>

        <div class="mb-3">
            <label for="action" class="form-label">Action</label>
            <select class="form-select" id="action" name="action" required>
                <option value="" disabled selected>Select action</option>
                <option value="add">Add</option>
                <option value="subtract">Subtract</option>
                <option value="remove">Remove</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity Adjustment</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Reason / Note</label>
            <textarea class="form-control" id="reason" name="reason" placeholder="Enter reason" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Submit Request</button>
    </form>

    <!-- User Requests Table -->
    <h4>Your Requests</h4>
    <div class="card shadow-sm">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item ID</th>
                    <th>Action</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $index => $r): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($r['item_id']) ?></td>
                            <td><?= ucfirst($r['action']) ?></td>
                            <td><?= esc($r['quantity']) ?></td>
                            <td><?= esc($r['reason']) ?></td>
                            <td>
                                <?php if ($r['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($r['status'] === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($r['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">No requests yet</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelector("#stockRequestForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    const response = await fetch("<?= base_url('user/submit-stock-request') ?>", {
        method: "POST",
        body: formData
    });

    const result = await response.json();

    Swal.fire({
        icon: result.success ? 'success' : 'error',
        title: result.message,
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        if (result.success) location.reload();
    });
});
</script>
</body>
</html>