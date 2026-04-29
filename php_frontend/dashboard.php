<?php include 'includes/header.php'; ?>

<div class="flex-between">
    <h2>Available Books</h2>
    <div id="user-greeting" class="badge badge-success" style="font-size: 0.9rem; padding: 0.5rem 1rem;"></div>
</div>

<div class="grid" id="books-grid">
    <!-- Books will be loaded here dynamically -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Fetch User
        try {
            const userRes = await api.getUser();
            if (userRes.data && userRes.data.name) {
                document.getElementById('user-greeting').innerText = `Hello, ${userRes.data.name}!`;
            }
        } catch (e) {
            console.error('Failed to get user', e);
        }

        // Fetch Books
        loadBooks();
    });

    async function loadBooks() {
        const grid = document.getElementById('books-grid');
        grid.innerHTML = '<p>Loading books...</p>';
        
        try {
            const res = await api.getBooks();
            const books = res.data;
            
            if (!books || books.length === 0) {
                grid.innerHTML = '<p>No books found.</p>';
                return;
            }

            grid.innerHTML = '';
            books.forEach(book => {
                const isAvailable = book.available_copies > 0;
                
                const card = document.createElement('div');
                card.className = 'card glass';
                card.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <h3 class="card-title">${escapeHTML(book.title)}</h3>
                            <p class="card-subtitle">By ${escapeHTML(book.author?.name || 'Unknown Author')}</p>
                        </div>
                        <span class="badge ${isAvailable ? 'badge-success' : 'badge-warning'}">
                            ${isAvailable ? 'Available' : 'Out of Stock'}
                        </span>
                    </div>
                    <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem;">
                        Published: ${book.published_year} <br>
                        Copies Left: ${book.available_copies}
                    </p>
                    <button class="btn btn-primary btn-sm w-100" 
                            style="width: 100%"
                            ${!isAvailable ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''} 
                            onclick="borrowBook(${book.id})">
                        ${isAvailable ? 'Borrow Book' : 'Unavailable'}
                    </button>
                `;
                grid.appendChild(card);
            });
        } catch (err) {
            grid.innerHTML = `<p style="color:var(--danger)">Error loading books: ${err.message}</p>`;
        }
    }

    async function borrowBook(bookId) {
        // Setup return date (e.g. 14 days from now)
        const returnDate = new Date();
        returnDate.setDate(returnDate.getDate() + 14);
        const returnDateStr = returnDate.toISOString().split('T')[0];

        try {
            await api.borrowBook(bookId, returnDateStr);
            showToast('Book borrowed successfully!');
            loadBooks(); // refresh list to show updated count
        } catch (err) {
            showToast(err.message || 'Failed to borrow book', 'error');
        }
    }

    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.innerText = str;
        return div.innerHTML;
    }
</script>

<?php include 'includes/footer.php'; ?>
