
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    var sidebar = document.getElementById('sidebar');
                    var overlay = document.getElementById('sidebarOverlay');
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
        if ($('#requestItemModal').length) {
            $('#requestItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%'
            });
        }
        
        if ($('#pullOutItemModal').length) {
            $('#pullOutItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#pullOutModal'),
                width: '100%'
            });
        }
        
        if ($('#requestActionModal').length) {
            $('#requestActionModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }

        if ($('#pullOutReasonModal').length) {
            $('#pullOutReasonModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#pullOutModal'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }
        
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


        // 🔄 CUSTOM DROPDOWN LOGIC
        window.selectCategory = (value, text, event) => {
            if (event) event.preventDefault();
            document.getElementById('statusFilterText').innerText = text;
            document.getElementById('statusFilter').value = value;
            const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            items.forEach(i => i.classList.remove('active'));
            event.target.classList.add('active');
            if (typeof filterTable === 'function') filterTable();
        };

        window.selectSort = (value, text, event) => {
            if (event) event.preventDefault();
            document.getElementById('sortFilterText').innerText = text;
            document.getElementById('sortFilter').value = value;
            const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            items.forEach(i => i.classList.remove('active'));
            event.target.classList.add('active');
            if (typeof sortItems === 'function') sortItems();
            if (typeof filterTable === 'function') filterTable(); // Also trigger filter incase
        };

        // 🔎 FILTERS & SEARCH
        window.filterTable = () => {
            const query = (document.getElementById("searchQuery")?.value || "").toLowerCase().trim();
            const category = (document.getElementById("statusFilter")?.value || "all").toLowerCase();
            const sortValue = document.getElementById("sortFilter")?.value || "default";
            
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const name = row.children[2]?.textContent.toLowerCase() || "";
                const pid = row.children[0]?.textContent.toLowerCase() || "";
                const rowCategory = row.children[5]?.textContent.toLowerCase().trim() || "";
                const statusBadge = row.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
                
                let matchesSortFilter = true;
                if (sortValue === "expiring_soon") matchesSortFilter = (statusBadge === "expiring soon" || statusBadge === "expiring today");
                else if (sortValue === "expired") matchesSortFilter = (statusBadge === "expired");
                else if (sortValue === "active") matchesSortFilter = (statusBadge === "active");
                else if (sortValue === "low_stock") matchesSortFilter = (row.getAttribute("data-low-stock") === "true");
                
                const matchesSearch = name.includes(query) || pid.includes(query);
                const matchesCategory = category === "all" || rowCategory === category;
                
                row.style.display = (matchesSearch && matchesCategory && matchesSortFilter) ? "" : "none";
            });
        };
        window.searchItems = window.filterTable; // Fallback helper

        // 🔄 SORT
        window.sortItems = () => {
            const sortValue = document.getElementById("sortFilter")?.value || "default";
            const tbody = document.querySelector("#itemsTable tbody");
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll("tr"));
            rows.sort((a, b) => {
                const nameA = a.children[2]?.textContent.trim().toLowerCase() || "";
                const nameB = b.children[2]?.textContent.trim().toLowerCase() || "";
                const qtyA = parseFloat(a.children[4]?.textContent) || 0;
                const qtyB = parseFloat(b.children[4]?.textContent) || 0;
                const dateA = new Date(a.children[9]?.textContent.trim() || 0);
                const dateB = new Date(b.children[9]?.textContent.trim() || 0);
                const statusA = a.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
                const statusB = b.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
                const statusOrder = { 'expired': 0, 'expiring today': 1, 'expiring soon': 1, 'active': 2, 'n/a': 3 };
                switch (sortValue) {
                    case "name_asc": return nameA.localeCompare(nameB);
                    case "name_desc": return nameB.localeCompare(nameA);
                    case "quantity_asc": return qtyA - qtyB;
                    case "quantity_desc": return qtyB - qtyA;
                    case "date_asc": return dateA - dateB;
                    case "date_desc": return dateB - dateA;
                    case "expiring_soon": return (statusOrder[statusA] || 99) - (statusOrder[statusB] || 99);
                    case "expired": return (statusA === "expired" ? -1 : 1) - (statusB === "expired" ? -1 : 1);
                    case "active": return (statusOrder[statusB] || 99) - (statusOrder[statusA] || 99);
                    case "low_stock": return (a.getAttribute("data-low-stock") === "true" ? -1 : 1) - (b.getAttribute("data-low-stock") === "true" ? -1 : 1);
                    default: return parseInt(a.dataset.originalIndex || 0) - parseInt(b.dataset.originalIndex || 0);
                }
            });
            rows.forEach(r => tbody.appendChild(r));
            if (typeof window.filterTable === "function") window.filterTable();
        };

        // Capture original index on load
        document.querySelectorAll("#itemsTable tbody tr").forEach((row, i) => {
            row.dataset.originalIndex = i;
        });

        // 📉 FILTERS
        window.showLowStockItems = () => {
            let found = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                if (row.getAttribute("data-low-stock") === "true") {
                    row.style.display = "";
                    found++;
                } else row.style.display = "none";
            });
            document.getElementById("showAllBtn").style.display = found ? "inline-block" : "none";
        };
        window.showExpiringSoon = () => {
            let found = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const days = parseInt(row.children[6]?.dataset.daysLeft) || 9999;
                if (days >= 0 && days <= 10) {
                    row.style.display = "";
                    found++;
                } else row.style.display = "none";
            });
            document.getElementById("showAllBtn").style.display = found ? "inline-block" : "none";
        };
        window.showAllItems = () => {
            document.querySelectorAll("#itemsTable tbody tr").forEach(r => r.style.display = "");
            document.getElementById("showAllBtn").style.display = "none";
        };

        // ✅ STOCK REQUEST SUBMISSION
        const form = document.getElementById("stockRequestFormModal");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = form.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submitting...';

                const selectElement = document.getElementById("requestItemModal");
                const itemId = selectElement.value;
                const variation = selectElement.options[selectElement.selectedIndex].getAttribute("data-variation");
                const action = document.getElementById("requestActionModal").value;
                const quantity = parseInt(document.getElementById("requestQtyModal").value) || 0;
                let reason = document.getElementById("requestReasonModal").value.trim();

                if (variation) {
                    reason = `[Variation: ${variation}] ` + reason;
                }

                if (!itemId || !action || !quantity || !reason) {
                    alert("Please fill all fields.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Stock Request';
                    return;
                }

                try {
                    const response = await fetch("<?= site_url('user/submit-stock-request') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({
                            item_id: itemId,
                            action: action,
                            quantity: quantity,
                            reason: reason
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || "Request submitted successfully!");
                        form.reset();
                        bootstrap.Modal.getInstance(document.getElementById("helpModal")).hide();
                    } else {
                        alert(result.message || "Failed to submit request.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("An error occurred while submitting.");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Stock Request';
                }
            });
        }
        // ✅ PULL-OUT SUBMISSION
        const pullOutForm = document.getElementById("pullOutFormModal");
        if (pullOutForm) {
            pullOutForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = pullOutForm.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submitting...';

                const pullOutItemSelect = document.getElementById("pullOutItemModal");
                const itemId = pullOutItemSelect.value;
                const variation = pullOutItemSelect.options[pullOutItemSelect.selectedIndex].getAttribute("data-variation");
                const reason = document.getElementById("pullOutReasonModal").value;
                const quantity = parseInt(document.getElementById("pullOutQtyModal").value) || 0;
                let note = document.getElementById("pullOutNoteModal").value.trim();

                if (variation) {
                    note = `[Variation: ${variation}] ` + note;
                }

                if (!itemId || !reason || !quantity) {
                    alert("Please fill all required fields.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Pull-Out';
                    return;
                }

                try {
                    const response = await fetch("<?= site_url('user/submit-pull-out') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({
                            item_id: itemId,
                            variation: variation || "",
                            reason: reason,
                            quantity: quantity,
                            note: note
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || "Pull-out submitted successfully!");
                        pullOutForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById("pullOutModal")).hide();
                    } else {
                        alert(result.message || "Failed to submit pull-out.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("An error occurred while submitting.");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Pull-Out';
                }
            });
        // ✅ RETURNS SUBMISSION
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
                        returnForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById("returnModal")).hide();
                    } else {
                        alert("Error: " + (data.message || 'Failed'));
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
    