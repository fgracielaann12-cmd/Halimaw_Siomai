document.addEventListener('DOMContentLoaded', () => {
    // Inject CSS for pagination logic
    const style = document.createElement('style');
    style.textContent = `
        tr[data-pagination-hidden="true"] {
            display: none !important;
        }
        .pagination-container {
            margin-top: 15px;
            display: flex;
            justify-content: flex-end;
        }
        .pagination-container .page-item { cursor: pointer; }
    `;
    document.head.appendChild(style);

    const rowsPerPage = 10;

    document.querySelectorAll('table').forEach(table => {
        // Only target tables that have a tbody and are inside a responsive container or are explicitly main tables
        // Avoid applying to small info tables inside modals.
        if (table.closest('.modal')) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        let currentPage = 1;
        
        // Create Pagination UI Container
        const paginationWrapper = document.createElement('div');
        paginationWrapper.className = 'pagination-container';
        // Insert right after the table's container to ensure it stays aligned
        const tableContainer = table.closest('.table-responsive, .table-responsive-custom, .table-card') || table;
        tableContainer.parentNode.insertBefore(paginationWrapper, tableContainer.nextSibling);

        const renderPaginationUI = (totalPages) => {
            paginationWrapper.innerHTML = '';
            if (totalPages <= 1) return;

            const ul = document.createElement('ul');
            ul.className = 'pagination pagination-sm mb-0 shadow-sm';

            // Previous Button
            ul.innerHTML += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link text-dark" data-page="prev">Previous</a>
            </li>`;

            // Page Numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                ul.innerHTML += `<li class="page-item"><a class="page-link text-dark" data-page="1">1</a></li>`;
                if (startPage > 2) ul.innerHTML += `<li class="page-item disabled"><a class="page-link text-dark">...</a></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = currentPage === i ? 'active bg-primary text-white border-primary' : 'text-dark';
                ul.innerHTML += `<li class="page-item"><a class="page-link ${isActive}" data-page="${i}">${i}</a></li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) ul.innerHTML += `<li class="page-item disabled"><a class="page-link text-dark">...</a></li>`;
                ul.innerHTML += `<li class="page-item"><a class="page-link text-dark" data-page="${totalPages}">${totalPages}</a></li>`;
            }

            // Next Button
            ul.innerHTML += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link text-dark" data-page="next">Next</a>
            </li>`;

            ul.addEventListener('click', (e) => {
                if(e.target.tagName === 'A' && !e.target.parentNode.classList.contains('disabled')) {
                    const action = e.target.getAttribute('data-page');
                    if (!action) return;
                    
                    if (action === 'prev' && currentPage > 1) currentPage--;
                    else if (action === 'next' && currentPage < totalPages) currentPage++;
                    else if (!isNaN(action)) currentPage = parseInt(action);
                    
                    applyPagination();
                }
            });
            paginationWrapper.appendChild(ul);
        };

        const applyPagination = () => {
            // Get all rows that are NOT hidden by the custom search filter
            const allRows = Array.from(tbody.querySelectorAll('tr'));
            const visibleRows = allRows.filter(row => row.style.display !== 'none');
            
            const totalPages = Math.ceil(visibleRows.length / rowsPerPage) || 1;
            if (currentPage > totalPages) currentPage = totalPages;

            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            visibleRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.removeAttribute('data-pagination-hidden');
                } else {
                    row.setAttribute('data-pagination-hidden', 'true');
                }
            });

            // Make sure rows hidden by search stay completely unaffected by pagination logic
            allRows.filter(row => row.style.display === 'none').forEach(row => {
                row.removeAttribute('data-pagination-hidden'); // clean up
            });

            renderPaginationUI(totalPages);
        };

        // Initial setup
        applyPagination();

        // Setup MutationObserver to watch for changes in 'style' attribute (from filtering)
        let isPaginating = false;
        let lastKnownVisibleCount = -1;

        const observer = new MutationObserver((mutations) => {
            if (isPaginating) return;
            
            let shouldRepaginate = false;
            for (let mutation of mutations) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    shouldRepaginate = true;
                    break;
                }
                if (mutation.type === 'childList') {
                    shouldRepaginate = true;
                    break;
                }
            }

            if (shouldRepaginate) {
                isPaginating = true;
                // Debounce to wait for the filter function to finish processing all rows
                setTimeout(() => {
                    // Check if the number of rows visible by filter actually changed
                    const currentVisibleCount = Array.from(tbody.querySelectorAll('tr')).filter(row => row.style.display !== 'none').length;
                    
                    if (currentVisibleCount !== lastKnownVisibleCount || mutations.some(m => m.type === 'childList')) {
                        currentPage = 1; // Reset to page 1 on new search/sort
                        lastKnownVisibleCount = currentVisibleCount;
                    }

                    applyPagination();
                    isPaginating = false;
                }, 100);
            }
        });

        observer.observe(tbody, { 
            attributes: true, 
            attributeFilter: ['style'], 
            childList: true, 
            subtree: true 
        });
    });
});
