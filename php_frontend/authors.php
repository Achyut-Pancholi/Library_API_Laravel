<?php include 'includes/header.php'; ?>

<div class="flex-between">
    <h2>Manage Authors</h2>
    <button class="btn btn-primary" onclick="openAuthorModal()">+ Add Author</button>
</div>

<div class="grid" id="authors-grid">
    <!-- Authors will be loaded here dynamically -->
</div>

<!-- Add Author Modal -->
<div class="modal" id="author-modal">
    <div class="modal-content glass" style="background: var(--bg-dark);">
        <div class="modal-header">
            <h3>Add New Author</h3>
            <button class="close-modal" onclick="closeAuthorModal()">&times;</button>
        </div>
        <form id="author-form">
            <div class="form-group">
                <label for="author-name">Name</label>
                <input type="text" id="author-name" required placeholder="J.K. Rowling">
            </div>
            <div class="form-group">
                <label for="author-bio">Biography</label>
                <textarea id="author-bio" rows="3" placeholder="Brief biography..."></textarea>
            </div>
            <div class="form-group">
                <label for="author-nationality">Nationality</label>
                <input type="text" id="author-nationality" required placeholder="British">
            </div>
            <div class="form-group">
                <label for="author-born-year">Birth Year</label>
                <input type="number" id="author-born-year" required placeholder="1965">
            </div>
            <button type="submit" class="btn btn-primary w-100" style="width: 100%;">Save Author</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadAuthors();

        document.getElementById('author-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            const data = {
                name: document.getElementById('author-name').value,
                bio: document.getElementById('author-bio').value,
                nationality: document.getElementById('author-nationality').value,
                born_year: document.getElementById('author-born-year').value
            };

            try {
                btn.innerText = 'Saving...';
                btn.disabled = true;
                await api.createAuthor(data);
                showToast('Author added successfully!');
                closeAuthorModal();
                loadAuthors();
            } catch (err) {
                showToast(err.message || 'Failed to add author', 'error');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    });

    async function loadAuthors() {
        const grid = document.getElementById('authors-grid');
        grid.innerHTML = '<p>Loading authors...</p>';
        
        try {
            const res = await api.getAuthors();
            const authors = res.data;
            
            if (!authors || authors.length === 0) {
                grid.innerHTML = '<p>No authors found.</p>';
                return;
            }

            grid.innerHTML = '';
            authors.forEach(author => {
                const card = document.createElement('div');
                card.className = 'card glass';
                card.innerHTML = `
                    <div class="flex-between">
                        <h3 class="card-title">${escapeHTML(author.name)}</h3>
                        <button class="btn btn-danger btn-sm" onclick="deleteAuthor(${author.id})">Delete</button>
                    </div>
                    <p class="card-subtitle" style="margin-bottom: 0.5rem">
                        Born: ${author.birth_date ? formatDate(author.birth_date) : 'Unknown'}
                    </p>
                    <p style="font-size: 0.875rem; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        ${escapeHTML(author.biography || 'No biography available.')}
                    </p>
                `;
                grid.appendChild(card);
            });
        } catch (err) {
            grid.innerHTML = `<p style="color:var(--danger)">Error loading authors: ${err.message}</p>`;
        }
    }

    async function deleteAuthor(id) {
        if(!confirm('Are you sure you want to delete this author?')) return;

        try {
            await api.deleteAuthor(id);
            showToast('Author deleted successfully');
            loadAuthors();
        } catch(err) {
            showToast(err.message || 'Failed to delete author', 'error');
        }
    }

    function openAuthorModal() {
        document.getElementById('author-form').reset();
        document.getElementById('author-modal').classList.add('active');
    }

    function closeAuthorModal() {
        document.getElementById('author-modal').classList.remove('active');
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
