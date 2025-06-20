<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecom CMS</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <section class="vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="text-center">
            <h1 class="display-4 fw-bold">Welcome to Ecom CMS</h1>
            <p class="lead text-muted">A clean and powerful content management system for your e-commerce store.</p>
            <a href="{{ url('/login') }}" class="btn btn-primary btn-lg m-2">Login</a>
            <a href="{{ url('/register') }}" class="btn btn-outline-secondary btn-lg m-2">Register</a>
        </div>
    </section>

    <footer class="text-center py-4 bg-white shadow-sm">
        <small>&copy; {{ date('Y') }} Ecom CMS. All rights reserved.</small>
    </footer>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- production version of vuejs 2.0, optimized for size and speed -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</body>
</html>
