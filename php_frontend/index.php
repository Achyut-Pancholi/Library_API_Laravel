<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-box glass">
        <!-- Login Form -->
        <div id="login-section">
            <h2 style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>
            <form id="login-form">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
            </form>
            <div class="auth-switch">
                <p>Don't have an account? <a href="#" id="show-register">Register here</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div id="register-section" class="hidden">
            <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
            <form id="register-form">
                <div class="form-group">
                    <label for="reg-name">Full Name</label>
                    <input type="text" id="reg-name" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label for="reg-password-confirm">Confirm Password</label>
                    <input type="password" id="reg-password-confirm" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
            </form>
            <div class="auth-switch">
                <p>Already have an account? <a href="#" id="show-login">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loginSection = document.getElementById('login-section');
        const registerSection = document.getElementById('register-section');
        
        document.getElementById('show-register').addEventListener('click', (e) => {
            e.preventDefault();
            loginSection.classList.add('hidden');
            registerSection.classList.remove('hidden');
        });

        document.getElementById('show-login').addEventListener('click', (e) => {
            e.preventDefault();
            registerSection.classList.add('hidden');
            loginSection.classList.remove('hidden');
        });

        // Login Submit
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            try {
                btn.innerText = 'Loading...';
                btn.disabled = true;
                await api.login(email, password);
                showToast('Login successful!');
                window.location.href = 'dashboard.php';
            } catch (err) {
                showToast(err.message || 'Login failed', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });

        // Register Submit
        document.getElementById('register-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;
            const confirm = document.getElementById('reg-password-confirm').value;

            if (password !== confirm) {
                return showToast('Passwords do not match', 'error');
            }

            const btn = e.target.querySelector('button');
            const originalText = btn.innerText;

            try {
                btn.innerText = 'Loading...';
                btn.disabled = true;
                await api.register(name, email, password, confirm);
                showToast('Registration successful!');
                window.location.href = 'dashboard.php';
            } catch (err) {
                showToast(err.message || 'Registration failed', 'error');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
