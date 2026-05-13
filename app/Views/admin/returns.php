<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= esc($title) ?> | Halimaw POS Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #858796;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #d1d5db;
            --sidebar-hover: #34495e;
            --sidebar-active: #4e73df;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --border-radius: 5px;
        }

        * {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-x: clip;
        }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        #sidebar .nav {
            width: 100%;
            min-width: 0;
        }

        #sidebar .nav-item {
            width: 100%;
            min-width: 0;
        }

        #sidebar .navbar-brand {
            padding: 1.25rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        #sidebar .navbar-brand img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            background-color: #f0f2f5;
            padding: 2px;
        }

        #sidebar .nav-link:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            background-color: var(--sidebar-hover);
            color: white;
            text-decoration: none;
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 1040; display: none; opacity: 0;
            transition: opacity 0.3s;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }

        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            .top-navbar h5 { font-size: 1rem; }
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .mobile-menu-toggle-inline {
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 5px;
            width: 42px;
            height: 42px;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        body > #mobileMenuToggle { display: none !important; }
        
        .top-navbar { 
            position: sticky; top: 0; z-index: 1000;
            background: white;
            height: 60px;
            padding: 0 20px !important;
            border-radius: 0 0 var(--border-radius) var(--border-radius) !important;
            box-shadow: var(--card-shadow);
            margin: 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100% !important;
            left: 0;
        }
        .top-navbar h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        /* USER PROFILE */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .profile-initial {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        .profile-name {
            line-height: 1.1;
            font-size: 0.85rem;
            font-weight: 600;
            color: #3a3b45;
        }
        .profile-role {
            font-size: 0.7rem;
            color: #858796;
        }

    /* Metric Cards Styling */
    .metric-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .metric-icon.blue { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
    .metric-icon.green { background: rgba(28, 200, 138, 0.1); color: #1cc88a; }
    .metric-icon.yellow { background: rgba(246, 194, 62, 0.1); color: #f6c23e; }
    .metric-icon.red { background: rgba(231, 74, 59, 0.1); color: #e74a3b; }
    
    .metric-info h6 {
        margin: 0;
        font-size: 0.85rem;
        color: #858796;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .metric-info h3 {
        margin: 5px 0 0 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #3a3b45;
    }
    .metric-info p {
        margin: 0;
        font-size: 0.85rem;
        color: #858796;
    }
    
    .action-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .action-restocked { background: #e3fbec; color: #1cc88a; }
    .action-pullout { background: #fce8e6; color: #e74a3b; }

    /* TABLE STYLES to match Inventory */
    #returnsTable {
        width: 100%;
        font-size: 0.9rem;
    }
    #returnsTable th, #returnsTable td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    #returnsTable thead th {
        background: var(--primary, #4e73df) !important;
        color: white !important;
        font-weight: 600;
        position: sticky;
        top: -1px;
        z-index: 10;
        box-shadow: 0 1px 0 var(--primary), 0 -1px 0 var(--primary);
    }
    #returnsTable thead th:first-child {
        border-top-left-radius: 12px;
    }
    #returnsTable thead th:last-child {
        border-top-right-radius: 12px;
    }
    #returnsTable tbody tr {
        transition: background 0.2s;
    }
    #returnsTable tbody tr:hover {
        background-color: #f8f9ff;
    }

    /* Unified Process Return Button Design */
    .btn-process-return {
        background-color: #4e73df;
        color: white;
        border: none;
        border-radius: 12px !important;
        padding: 0 20px;
        height: 40px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
        cursor: pointer;
        text-decoration: none;
        margin: 0;
    }
    .btn-process-return:hover {
        background-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(78, 115, 223, 0.3);
        color: white;
    }
    .btn-process-return i {
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .btn-process-return {
            height: 36px;
            padding: 0 16px;
            font-size: 0.85rem;
        }
        .btn-process-return i {
            font-size: 1.1rem;
        }
    }
</style>

    

    <!-- DISABLE BROWSER BACK/FORWARD BUTTONS COMPLETELY -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Push an empty state immediately
        history.pushState(null, null, location.href);
        // If the user tries to go back, instantly push them forward again
        window.onpopstate = function () {
            history.go(1);
        };
        
        function enforceClientAuth() {
            if (localStorage.getItem('auth_status') === 'logged_out') {
                document.documentElement.style.display = 'none';
                if(document.body) document.body.style.display = 'none';
                window.location.replace('/Halimaw_Siomai/index.php/login?blocked=1&cb=' + new Date().getTime());
            }
        }
        enforceClientAuth();
        window.addEventListener('pageshow', enforceClientAuth);
    </script>
</head>
<body>

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <?php
    $extra_buttons = '
        <button class="btn-process-return shadow-sm" data-bs-toggle="modal" data-bs-target="#returnModal">
            <i class="bi bi-plus me-2"></i>Process Return
        </button>
    ';
    echo view('partials/admin_topbar', [
        'title' => 'Customer Returns',
        'icon' => 'bi bi-arrow-return-left text-primary',
        'extra_buttons' => $extra_buttons,
        'hide_toggle' => true
    ]);
    ?>
        
        <div class="container-fluid px-4 py-4">
            <!-- Dashboard Metrics -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card border-primary">
                        <div class="metric-icon blue"><i class="bi bi-arrow-return-left"></i></div>
                        <div class="metric-info">
                            <h6>Total Returns</h6>
                            <h3 class="text-primary"><?= number_format($totalReturns) ?></h3>
                            <p>All-time processed</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card border-success">
                        <div class="metric-icon green"><i class="bi bi-percent"></i></div>
                        <div class="metric-info">
                            <h6>Return Rate</h6>
                            <h3 class="text-success"><?= $returnRate ?>%</h3>
                            <p>Versus total sales</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card border-warning">
                        <div class="metric-icon yellow"><i class="bi bi-trash3"></i></div>
                        <div class="metric-info">
                            <h6>Waste Escalation</h6>
                            <h3 class="text-warning"><?= $pullOutRate ?>%</h3>
                            <p>Returns sent to pull-outs</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="metric-card border-danger">
                        <div class="metric-icon red"><i class="bi bi-cash-coin"></i></div>
                        <div class="metric-info">
                            <h6>Return Losses</h6>
                            <h3 class="text-danger">₱<?= number_format($financialLoss, 2) ?></h3>
                            <p>Value of non-restockables</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Returns Data Table -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive" style="border-radius: 12px; overflow: hidden;">
                        <table class="table table-hover align-middle mb-0 text-center" id="returnsTable">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Date</th>
                                    <th class="text-center align-middle">Transaction ID</th>
                                    <th class="text-center align-middle">Item Returned</th>
                                    <th class="text-center align-middle">Qty</th>
                                    <th class="text-center align-middle">Reason</th>
                                    <th class="text-center align-middle">Evidence</th>
                                    <th class="text-center align-middle">Condition</th>
                                    <th class="text-center align-middle">Action Taken</th>
                                    <th class="text-center align-middle">Processed By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($returns)): ?>
                                    <tr><td colspan="9" class="text-center py-4 text-muted">No returns have been processed yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach($returns as $return): ?>
                                    <tr>
                                        <td class="text-muted small"><?= date('M d, Y h:i A', strtotime($return['created_at'])) ?></td>
                                        <td class="fw-semibold text-primary"><?= esc($return['transaction_id']) ?></td>
                                        <td>
                                            <div class="fw-bold"><?= esc($return['product_name'] ?? 'Unknown Item') ?></div>
                                            <?php if($return['variation']): ?>
                                                <small class="text-muted text-uppercase">Variation: <?= esc($return['variation']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold"><?= esc($return['quantity']) ?></td>
                                        <td class="text-muted"><?= esc($return['reason']) ?></td>
                                        <td>
                                            <?php if(!empty($return['evidence_path'])): ?>
                                                <a href="<?= base_url($return['evidence_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm" style="border-radius: 20px; font-size: 0.75rem;">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">None</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($return['return_condition'] === 'RESTOCKABLE'): ?>
                                                <span class="badge bg-success">RESTOCKABLE</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">NON-RESTOCKABLE</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($return['action_taken'] === 'RESTOCKED'): ?>
                                                <span class="action-badge action-restocked"><i class="bi bi-box-arrow-in-down me-1"></i> RESTOCKED</span>
                                            <?php else: ?>
                                                <span class="action-badge action-pullout"><i class="bi bi-trash3 me-1"></i> PULL OUT</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted"><i class="bi bi-person-circle me-1"></i> <?= esc($return['staff_name'] ?? 'Unknown') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        </div>
    </div>

    <!-- CUSTOMER RETURN MODAL -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header p-3 border-0 position-relative" style="background-color: #2c3e50; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title fw-semibold text-white w-100 text-center" style="font-size: 1.15rem;">
                        <i class="bi bi-arrow-return-left me-2"></i>Process Customer Return
                    </h5>
                    <button type="button" class="btn-close btn-close-white position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form id="returnFormModal">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="returnCsrf">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-receipt me-1"></i> Transaction ID
                            </label>
                            <input type="text" class="form-control form-control-lg" id="returnTransactionId" placeholder="Enter Transaction ID (e.g. TXN-12345)" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-box me-1"></i> Select Item
                            </label>
                            <select class="form-select form-select-lg" id="returnItemModal" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                                <option value="" disabled selected>— Choose an item —</option>
                                <?php if(isset($items) && !empty($items)): ?>
                                    <?php foreach($items as $item): ?>
                                        <?php if (!empty($item['is_variation_child']) && !empty($item['variation_label'])): ?>
                                            <option value="<?= esc($item['id']) ?>" data-variation="<?= esc($item['variation_label']) ?>">
                                                <?= esc($item['name']) ?> — <?= esc($item['variation_label']) ?>
                                            </option>
                                        <?php elseif (empty($item['variation_group_id'])): ?>
                                            <option value="<?= esc($item['id']) ?>">
                                                <?= esc($item['name']) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="bi bi-hash me-1"></i> Quantity
                                </label>
                                <input type="number" class="form-control form-control-lg" id="returnQtyModal" min="1" placeholder="Enter amount" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Reason for Return
                                </label>
                                <select class="form-select form-select-lg" id="returnReasonModal" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                                    <option value="" disabled selected>— Select Reason —</option>
                                    <option value="Wrong Item Served">Wrong Item Served</option>
                                    <option value="Customer Changed Mind">Customer Changed Mind</option>
                                    <option value="Item Damaged / Bad Quality">Item Damaged / Bad Quality</option>
                                    <option value="Foreign Object Found">Foreign Object Found</option>
                                    <option value="Under-cooked / Spoilage">Under-cooked / Spoilage</option>
                                </select>
                            </div>
                        </div>

                        <!-- PROOF OF EVIDENCE -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-camera me-1 text-primary"></i> Proof of Evidence (Optional)
                            </label>
                            <input type="file" class="form-control form-control-lg" id="returnEvidenceModal" accept="image/*,video/*" style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.85rem;"><i class="bi bi-info-circle me-1"></i>Attach photo or video showing the item's condition.</small>
                        </div>

                        <!-- CONDITION EVALUATION -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-3 d-block" style="font-size: 0.95rem;">
                                <i class="bi bi-check-circle me-1"></i> Item Condition Evaluation
                            </label>
                            
                            <div class="d-flex flex-column gap-3">
                                <!-- Restockable Option -->
                                <div class="form-check p-3 rounded" style="background-color: #f8fff9; border: 1px solid #28a745;">
                                    <input class="form-check-input ms-2 mt-2" type="radio" name="returnCondition" id="condRestockable" value="RESTOCKABLE" required style="transform: scale(1.3);">
                                    <label class="form-check-label ms-3 w-100" for="condRestockable" style="cursor:pointer;">
                                        <div class="fw-bold text-success" style="font-size: 1.05rem;">RESTOCKABLE</div>
                                        <small class="text-muted">Item is in perfect condition, safe for consumption, and can be resold immediately.</small>
                                    </label>
                                </div>
                                
                                <!-- Non-Restockable Option -->
                                <div class="form-check p-3 rounded" style="background-color: #fff8f8; border: 1px solid #dc3545;">
                                    <input class="form-check-input ms-2 mt-2" type="radio" name="returnCondition" id="condNonRestockable" value="NON-RESTOCKABLE" required style="transform: scale(1.3);">
                                    <label class="form-check-label ms-3 w-100" for="condNonRestockable" style="cursor:pointer;">
                                        <div class="fw-bold text-danger" style="font-size: 1.05rem;">NON-RESTOCKABLE (Waste)</div>
                                        <small class="text-muted">Item is compromised, spoiled, damaged, or unsafe. This will automatically generate a <strong class="text-danger">Pull-Out Request</strong>.</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <style>
                                .btn-return-cancel { transition: all 0.2s ease-in-out; }
                                .btn-return-cancel:hover { background-color: #d1d5db !important; border-color: #d1d5db !important; }
                                .btn-return-process { transition: all 0.2s ease-in-out; }
                                .btn-return-process:hover { background-color: #1e40af !important; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
                            </style>
                            <button type="button" class="btn btn-light px-4 py-2 fw-bold btn-return-cancel" data-bs-dismiss="modal" style="border-radius: 10px !important; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold btn-return-process" style="border-radius: 10px !important; background-color: #1d4ed8; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <i class="bi bi-send me-1"></i> Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#returnsTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 10,
        "language": {
            "search": "Search Returns:"
        }
    });

    if ($('#returnItemModal').length) {
        $('#returnItemModal').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#returnModal'),
            width: '100%'
        });
    }

    if ($('#returnReasonModal').length) {
        $('#returnReasonModal').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#returnModal'),
            width: '100%',
            minimumResultsForSearch: Infinity
        });
    }

    // RETURNS SUBMISSION
    const txnInput = document.getElementById('returnTransactionId');
    const itemSelect = $('#returnItemModal');
    const qtyInput = document.getElementById('returnQtyModal');

    if (txnInput) {
        // Clear item select initially to guide the user
        itemSelect.empty().append('<option value="" disabled selected>— Enter Transaction ID first —</option>').trigger('change');

        // Handle Transaction ID entry
        txnInput.addEventListener('change', async function() {
            const txnId = this.value.trim();
            if (!txnId) {
                itemSelect.empty().append('<option value="" disabled selected>— Enter Transaction ID first —</option>').trigger('change');
                return;
            }

            try {
                // Show loading state
                itemSelect.empty().append('<option value="" disabled selected>— Fetching Items... —</option>').trigger('change');
                
                const response = await fetch(`<?= site_url('admin/sales/transaction-items') ?>/${txnId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (data.success && data.items.length > 0) {
                    itemSelect.empty().append('<option value="" disabled selected>— Choose item from this transaction —</option>');
                    data.items.forEach(item => {
                        const optionText = `${item.product_name} (${item.pack})`;
                        const newOption = new Option(optionText, item.product_id, false, false);
                        // Store qty and variation for auto-fill
                        $(newOption).attr('data-qty', item.quantity);
                        $(newOption).attr('data-variation', item.pack);
                        itemSelect.append(newOption);
                    });
                    itemSelect.trigger('change');
                } else {
                    alert('No items found for this Transaction ID. Please verify the ID.');
                    itemSelect.empty().append('<option value="" disabled selected>— No items found —</option>').trigger('change');
                }
            } catch (err) {
                console.error(err);
                alert('Error fetching transaction details.');
            }
        });
    }

    // Auto-fill Quantity when item is selected
    itemSelect.on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const qty = selectedOption.attr('data-qty');
        if (qty) {
            qtyInput.value = qty;
        }
    });

    const returnForm = document.getElementById("returnFormModal");
    if (returnForm) {
        returnForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitBtn = returnForm.querySelector("button[type='submit']");
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass me-2"></i>Processing...';

            const selectElement = document.getElementById("returnItemModal");
            const itemId = selectElement.value;
            const variation = selectElement.options[selectElement.selectedIndex].getAttribute("data-variation");
            
            const transactionId = document.getElementById("returnTransactionId").value.trim();
            const quantity = parseInt(document.getElementById("returnQtyModal").value) || 0;
            const reason = document.getElementById("returnReasonModal").value;
            const evidenceFile = document.getElementById("returnEvidenceModal").files[0];
            
            let condition = "";
            const condRadios = document.getElementsByName("returnCondition");
            for (let i=0; i<condRadios.length; i++) {
                if (condRadios[i].checked) {
                    condition = condRadios[i].value;
                    break;
                }
            }

            if (!transactionId || !itemId || !quantity || !reason || !condition) {
                alert("Please fill all required fields correctly.");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
                return;
            }

            const formData = new FormData();
            formData.append("transaction_id", transactionId);
            formData.append("item_id", itemId);
            if(variation) formData.append("variation", variation);
            formData.append("quantity", quantity);
            formData.append("reason", reason);
            formData.append("return_condition", condition);
            if(evidenceFile) formData.append("evidence_file", evidenceFile);

            try {
                const response = await fetch("<?= site_url('user/submit-return') ?>", {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.getElementById("returnCsrf").value
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.status === 'success' || data.success) {
                    alert("Return processed successfully: " + (data.message || ''));
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
                }
            } catch (err) {
                console.error(err);
                alert("A network error occurred. Please check console.");
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
            }
        });
    }

});

document.addEventListener("DOMContentLoaded", () => {
    const mobileMenuToggleInline = document.getElementById('mobileMenuToggleInline');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        if (sidebar) sidebar.classList.toggle('active');
        if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
    }

    if (mobileMenuToggleInline) {
        mobileMenuToggleInline.addEventListener('click', toggleSidebar);
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
