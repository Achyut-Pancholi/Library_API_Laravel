<?php include 'includes/header.php'; ?>

<div class="flex-between">
    <h2>My Borrowed Books</h2>
</div>

<div class="glass" style="padding: 1.5rem; border-radius: 16px;">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrowed On</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="borrows-table-body">
                <tr><td colspan="6">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadMyBorrows();
    });

    async function loadMyBorrows() {
        const tbody = document.getElementById('borrows-table-body');
        tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
        
        try {
            const res = await api.getMyBorrows();
            const borrows = res.data;
            
            if (!borrows || borrows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6">You have no active borrowings.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            borrows.forEach(borrow => {
                const book = borrow.book;
                const author = book?.author?.name || 'Unknown';
                const isReturned = !!borrow.returned_at;
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${escapeHTML(book?.title || 'Unknown Book')}</strong></td>
                    <td>${escapeHTML(author)}</td>
                    <td>${formatDate(borrow.borrowed_at)}</td>
                    <td>${formatDate(borrow.return_date)}</td>
                    <td>
                        <span class="badge ${isReturned ? 'badge-success' : 'badge-warning'}">
                            ${isReturned ? 'Returned' : 'Active'}
                        </span>
                    </td>
                    <td>
                        ${!isReturned ? 
                            `<button class="btn btn-primary btn-sm" onclick="returnBook(${borrow.id})">Return</button>` 
                            : `<span style="color:var(--text-muted);font-size:0.875rem">Returned on ${formatDate(borrow.returned_at)}</span>`}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="6" style="color:var(--danger)">Error loading borrows: ${err.message}</td></tr>`;
        }
    }

    async function returnBook(borrowId) {
        if (!confirm('Are you sure you want to return this book?')) return;
        
        try {
            await api.returnBook(borrowId);
            showToast('Book returned successfully!');
            loadMyBorrows();
        } catch (err) {
            showToast(err.message || 'Failed to return book', 'error');
        }
    }

    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.innerText = str;
        return div.innerHTML;
    }

    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        return new Date(dateStr).toLocaleDateString();
    }
</script>

<?php include 'includes/footer.php'; ?>
