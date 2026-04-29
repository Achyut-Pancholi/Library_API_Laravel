<?php include 'includes/header.php'; ?>

<div class="flex-between">
    <h2>Manage Books</h2>
    <button class="btn btn-primary" onclick="openBookModal()">+ Add Book</button>
</div>

<div class="glass" style="padding: 1.5rem; border-radius: 16px;">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Published Year</th>
                    <th>Available Copies</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="books-table-body">
                <tr><td colspan="6">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Book Modal -->
<div class="modal" id="book-modal">
    <div class="modal-content glass" style="background: var(--bg-dark);">
        <div class="modal-header">
            <h3>Add New Book</h3>
            <button class="close-modal" onclick="closeBookModal()">&times;</button>
        </div>
        <form id="book-form">
            <div class="form-group">
                <label for="book-title">Title</label>
                <input type="text" id="book-title" required placeholder="The Great Gatsby">
            </div>
            <div class="form-group">
                <label for="book-author">Author</label>
                <select id="book-author" required>
                    <option value="">Select Author...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="book-isbn">ISBN</label>
                <input type="text" id="book-isbn" required placeholder="978-3-16-148410-0">
            </div>
            <div class="form-group">
                <label for="book-genre">Genre</label>
                <input type="text" id="book-genre" required placeholder="Fantasy">
            </div>
            <div class="form-group">
                <label for="book-year">Published Year</label>
                <input type="number" id="book-year" required placeholder="1925" min="1000" max="2099">
            </div>
            <div class="form-group">
                <label for="book-copies">Total Copies</label>
                <input type="number" id="book-copies" required placeholder="5" min="1">
            </div>
            <button type="submit" class="btn btn-primary w-100" style="width: 100%;">Save Book</button>
        </form>
    </div>
</div>

<script>
    let authorsLoaded = false;

    document.addEventListener('DOMContentLoaded', () => {
        loadManageBooks();

        document.getElementById('book-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            const data = {
                title: document.getElementById('book-title').value,
                author_id: document.getElementById('book-author').value,
                isbn: document.getElementById('book-isbn').value,
                genre: document.getElementById('book-genre').value,
                published_year: document.getElementById('book-year').value,
                total_copies: document.getElementById('book-copies').value
            };

            try {
                btn.innerText = 'Saving...';
                btn.disabled = true;
                await api.createBook(data);
                showToast('Book added successfully!');
                closeBookModal();
                loadManageBooks();
            } catch (err) {
                showToast(err.message || 'Failed to add book', 'error');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    });

    async function loadManageBooks() {
        const tbody = document.getElementById('books-table-body');
        tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
        
        try {
            const res = await api.getBooks();
            const books = res.data;
            
            if (!books || books.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6">No books found.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            books.forEach(book => {
                const author = book.author?.name || 'Unknown';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>${escapeHTML(book.title)}</strong></td>
                    <td>${escapeHTML(author)}</td>
                    <td>${escapeHTML(book.isbn)}</td>
                    <td>${book.published_year}</td>
                    <td>${book.available_copies} / ${book.total_copies}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="deleteBook(${book.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="6" style="color:var(--danger)">Error loading books: ${err.message}</td></tr>`;
        }
    }

    async function deleteBook(id) {
        if(!confirm('Are you sure you want to delete this book?')) return;

        try {
            await api.deleteBook(id);
            showToast('Book deleted successfully');
            loadManageBooks();
        } catch(err) {
            showToast(err.message || 'Failed to delete book', 'error');
        }
    }

    async function openBookModal() {
        document.getElementById('book-form').reset();
        
        if (!authorsLoaded) {
            try {
                const res = await api.getAuthors();
                const select = document.getElementById('book-author');
                res.data.forEach(author => {
                    const opt = document.createElement('option');
                    opt.value = author.id;
                    opt.textContent = author.name;
                    select.appendChild(opt);
                });
                authorsLoaded = true;
            } catch (err) {
                showToast('Failed to load authors', 'error');
                return;
            }
        }
        
        document.getElementById('book-modal').classList.add('active');
    }

    function closeBookModal() {
        document.getElementById('book-modal').classList.remove('active');
    }

    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.innerText = str;
        return div.innerHTML;
    }
</script>

<?php include 'includes/footer.php'; ?>
