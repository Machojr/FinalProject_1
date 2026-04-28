<?php
/**
 * Home Page - Referral Management System (RMS)
 * Landing page with modern design and animations
 */

require_once(__DIR__ . '/config/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Digitalizing Tanzania's Healthcare Referrals</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/FinalProject_1/assets/css/style.css">

    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.8); backdrop-filter: blur(10px);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4" href="#home">
                <i class="fas fa-hospital-user me-2"></i>RMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-3 px-4" href="/FinalProject_1/modules/auth/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-heartbeat me-3"></i>
                    Digitalizing Tanzania's Healthcare Referrals
                </h1>
                <p class="hero-subtitle">
                    Streamline patient referrals across all five tiers of Tanzania's public healthcare system with our modern, secure, and efficient platform.
                </p>
                <div class="hero-buttons">
                    <a href="/FinalProject_1/modules/auth/login.php" class="btn-custom btn-primary-custom">
                        <i class="fas fa-rocket me-2"></i>Get Started
                    </a>
                    <a href="#features" class="btn-custom btn-secondary-custom">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="position-absolute top-50 start-10 translate-middle-y d-none d-lg-block">
            <div class="floating-element" style="animation: float 4s ease-in-out infinite;">
                <i class="fas fa-user-md fa-3x text-white opacity-50"></i>
            </div>
        </div>
        <div class="position-absolute top-25 end-10 d-none d-lg-block">
            <div class="floating-element" style="animation: float 5s ease-in-out infinite 1s;">
                <i class="fas fa-hospital fa-3x text-white opacity-50"></i>
            </div>
        </div>
        <div class="position-absolute bottom-25 start-20 d-none d-lg-block">
            <div class="floating-element" style="animation: float 6s ease-in-out infinite 2s;">
                <i class="fas fa-chart-line fa-3x text-white opacity-50"></i>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title fade-in">Powerful Features for Modern Healthcare</h2>
            <div class="features-grid">
                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3 class="feature-title">Seamless Referrals</h3>
                    <p class="feature-description">
                        Create and manage patient referrals with real-time tracking across all healthcare tiers. No more lost paper referrals.
                    </p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Role-Based Security</h3>
                    <p class="feature-description">
                        Secure access control for Clinicians, Hospital Administrators, and Ministry of Health officials with encrypted data.
                    </p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="feature-title">Smart Notifications</h3>
                    <p class="feature-description">
                        Automated Email and SMS notifications at every referral stage, ensuring timely communication between facilities.
                    </p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Analytics Dashboard</h3>
                    <p class="feature-description">
                        Comprehensive reporting and analytics for the Ministry of Health to monitor referral patterns and system performance.
                    </p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Bidirectional Feedback</h3>
                    <p class="feature-description">
                        Receive clinical outcomes and feedback from receiving facilities, closing the referral loop effectively.
                    </p>
                </div>

                <div class="feature-card fade-in">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Mobile Responsive</h3>
                    <p class="feature-description">
                        Access the system from any device - desktop, tablet, or mobile. Works seamlessly across all platforms.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item fade-in">
                    <span class="stat-number">5</span>
                    <span class="stat-label">Healthcare Tiers Covered</span>
                </div>
                <div class="stat-item fade-in">
                    <span class="stat-number">3</span>
                    <span class="stat-label">User Roles</span>
                </div>
                <div class="stat-item fade-in">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">System Availability</span>
                </div>
                <div class="stat-item fade-in">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Secure & Encrypted</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="features-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in">
                    <h2 class="section-title text-start">About RMS</h2>
                    <p class="lead">
                        The Referral Management System (RMS) addresses the critical challenges in Tanzania's healthcare referral process. Traditional paper-based referrals often get lost, lack tracking, and provide no feedback mechanism.
                    </p>
                    <p>
                        Our digital platform ensures that every patient referral is tracked from initiation to completion, with real-time status updates and automated notifications. This improves patient outcomes and optimizes resource allocation across the healthcare system.
                    </p>
                    <div class="mt-4">
                        <a href="/FinalProject_1/modules/auth/login.php" class="btn-custom btn-primary-custom">
                            <i class="fas fa-play me-2"></i>Start Using RMS
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 fade-in">
                    <div class="text-center">
                        <div style="width: 300px; height: 300px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                            <i class="fas fa-hospital-user fa-6x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Transform Healthcare Referrals?</h2>
                <p class="cta-text">
                    Join Tanzania's healthcare facilities in adopting this innovative referral management solution.
                </p>
                <a href="/FinalProject_1/modules/auth/login.php" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-sign-in-alt me-2"></i>Access the System
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-hospital-user me-2"></i>RMS
                    </h5>
                    <p class="mb-3">
                        Referral Management System for Tanzania's Public Healthcare
                    </p>
                    <p class="small text-muted">
                        © 2026 RMS. Academic Project - NIT
                    </p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#features" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>Features
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#about" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>About
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/FinalProject_1/modules/auth/login.php" class="text-light text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>Login
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">Contact Info</h5>
                    <p class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        National Institute of Transport, Dar es Salaam
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        rms@nit.go.tz
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        +255 XXX XXX XXX
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0 small">
                    Developed as part of BSc Information Technology Project
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="/FinalProject_1/assets/js/main.js"></script>

    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(0,0,0,0.95)';
            } else {
                navbar.style.background = 'rgba(0,0,0,0.8)';
            }
        });
    </script>
</body>
</html>