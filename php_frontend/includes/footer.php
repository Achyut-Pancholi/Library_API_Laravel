    </div> <!-- end container -->

    <script src="js/api.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const currentPath = window.location.pathname;
            const isAuthPage = currentPath.endsWith('index.php') || currentPath.endsWith('/');
            
            if (api.getToken()) {
                if (isAuthPage) {
                    window.location.href = 'dashboard.php';
                    return;
                }
                
                // Show navbar and fetch user details to verify token
                const nav = document.getElementById('main-nav');
                if (nav) nav.style.display = 'flex';
                
                try {
                    const res = await api.getUser();
                    // Setup logout
                    const logoutBtn = document.getElementById('logout-btn');
                    if(logoutBtn) {
                        logoutBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            api.clearToken();
                            window.location.href = 'index.php';
                        });
                    }

                    // highlight active nav link
                    const navLinks = document.querySelectorAll('.nav-links a');
                    navLinks.forEach(link => {
                        if (link.href === window.location.href) {
                            link.classList.add('active');
                        }
                    });

                } catch (e) {
                    api.clearToken();
                    window.location.href = 'index.php';
                }

            } else {
                if (!isAuthPage) {
                    window.location.href = 'index.php';
                }
            }
        });
    </script>
</body>
</html>
