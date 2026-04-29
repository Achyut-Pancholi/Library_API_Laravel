const API_BASE_URL = 'http://127.0.0.1:7777/api';

const api = {
    getToken() {
        return localStorage.getItem('library_token');
    },

    setToken(token) {
        localStorage.setItem('library_token', token);
    },

    clearToken() {
        localStorage.removeItem('library_token');
    },

    async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        };

        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        const config = {
            ...options,
            headers
        };

        if (config.body && typeof config.body === 'object') {
            config.body = JSON.stringify(config.body);
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                if (response.status === 401) {
                    this.clearToken();
                    window.location.href = 'index.php'; // redirect to login
                }
                throw new Error(data.message || 'Something went wrong');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    // Auth
    async login(email, password) {
        const res = await this.request('/login', {
            method: 'POST',
            body: { email, password }
        });
        if (res.data && res.data.token) {
            this.setToken(res.data.token);
        }
        return res;
    },

    async register(name, email, password, password_confirmation) {
        const res = await this.request('/register', {
            method: 'POST',
            body: { name, email, password, password_confirmation }
        });
        if (res.data && res.data.token) {
            this.setToken(res.data.token);
        }
        return res;
    },

    async getUser() {
        return this.request('/user', { method: 'GET' });
    },

    // Books
    async getBooks() {
        return this.request('/books', { method: 'GET' });
    },

    async createBook(data) {
        return this.request('/books', { method: 'POST', body: data });
    },

    async updateBook(id, data) {
        return this.request(`/books/${id}`, { method: 'PUT', body: data });
    },

    async deleteBook(id) {
        return this.request(`/books/${id}`, { method: 'DELETE' });
    },

    // Authors
    async getAuthors() {
        return this.request('/authors', { method: 'GET' });
    },
    
    async createAuthor(data) {
        return this.request('/authors', { method: 'POST', body: data });
    },

    async deleteAuthor(id) {
        return this.request(`/authors/${id}`, { method: 'DELETE' });
    },

    // Borrows
    async borrowBook(book_id, return_date) {
        return this.request('/borrows', { method: 'POST', body: { book_id, return_date } });
    },

    async getMyBorrows() {
        return this.request('/borrows/my', { method: 'GET' });
    },

    async returnBook(borrow_id) {
        return this.request(`/borrows/${borrow_id}/return`, { method: 'PATCH' });
    }
};

// UI Utils
function showToast(message, type = 'success') {
    const area = document.getElementById('notification-area');
    if (!area) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = message;
    
    area.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
