<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resources & Library | PENSA UMaT Portal</title>
    <link rel="icon" type="image/png" href="images/icons/favicon-96x96.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0a58ca;
            --secondary-bg: #f8fafc;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --sidebar-width: 280px;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
        }

        body {
            background-color: var(--secondary-bg);
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid #e2e8f0;
            position: fixed;
            height: calc(100vh - 70px);
            top: 70px;
            padding: 30px 20px;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1002;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-logo {
            height: 40px;
        }

        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-dark);
        }

        .nav-links {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: var(--text-gray);
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s;
            gap: 15px;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 25px;
            text-align: center;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #eff6ff;
            color: var(--primary-color);
        }

        .nav-link.active {
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            margin-top: 70px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: var(--text-gray);
        }

        /* Search Bar */
        .search-container {
            margin-bottom: 30px;
            position: relative;
            max-width: 600px;
        }

        .search-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-dark);
            outline: none;
            transition: all 0.2s;
            background: #fff;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 8px 16px;
            border-radius: 20px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: var(--text-gray);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .filter-tab.active {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .filter-tab:hover:not(.active) {
            background: #f1f5f9;
        }

        /* Resources Grid */
        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .resource-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover);
        }

        .file-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .icon-pdf {
            background: #fee2e2;
            color: #ef4444;
        }

        .icon-doc {
            background: #dbeafe;
            color: #2563eb;
        }

        .icon-audio {
            background: #f3e8ff;
            color: #9333ea;
        }

        .icon-image {
            background: #ffedd5;
            color: #ea580c;
        }

        .resource-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .resource-meta {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .resource-desc {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 20px;
            line-height: 1.5;
            display: -webkit-box;
            line-clamp: 2;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-download {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: #eff6ff;
            color: var(--primary-color);
            border-radius: 10px;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-download:hover {
            background: var(--primary-color);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 10px 0 20px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                margin-top: 70px;
            }

            .menu-toggle {
                display: block;
            }

            .nav-logo-area {
                display: flex;
            }
        }

        @media (min-width: 993px) {
            .nav-logo-area {
                display: none;
            }
        }

        /* Profile Dropdown */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #fff;
            padding: 8px 15px;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
        }

        .user-profile:hover {
            transform: translateY(-2px);
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-gray);
        }

        .profile-dropdown {
            position: absolute;
            top: 120%;
            right: 0;
            width: 220px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: none;
            flex-direction: column;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            border: 1px solid #f1f5f9;
        }

        .profile-dropdown.active {
            display: flex;
            opacity: 1;
            transform: translateY(0);
        }

        .profile-dropdown a {
            padding: 12px 15px;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            transition: background 0.2s;
            font-size: 0.95rem;
        }

        .profile-dropdown a:hover {
            background: #f8fafc;
        }

        .profile-dropdown .logout-btn {
            color: #ef4444;
            margin-top: 5px;
            border-top: 1px solid #f1f5f9;
        }

        .profile-dropdown .logout-btn:hover {
            background: #fee2e2;
        }
    </style>
</head>

<body>
    <!-- Sticky Navbar -->
    <nav class="top-navbar">
        <div class="nav-logo-area">
            <i class="fas fa-bars menu-toggle" onclick="toggleSidebar()"></i>
            <img src="images/PENSA-LEGON-LOGO.png" alt="Logo" class="nav-logo">
            <span style="font-weight: 700; font-size: 1.2rem; margin-left: 5px;">PENSA UMaT</span>
        </div>

        <!-- Profile -->
        <div class="user-profile" onclick="toggleProfileMenu()">
            <div class="avatar-circle" id="user-initial">U</div>
            <div class="user-info">
                <span class="user-name" id="user-name-display">User Name</span>
                <span class="user-role" id="user-role-display">Member</span>
            </div>
            <i class="fas fa-chevron-down" style="font-size: 0.8rem; margin-left: 10px; color: var(--text-gray);"></i>

            <!-- Dropdown Menu -->
            <div class="profile-dropdown" id="profileDropdown">
                <a href="portal/profile.html"><i class="fas fa-user-circle"></i> My Profile</a>
                <a href="portal/financials.html"><i class="fas fa-wallet"></i> Financials</a>
                <a href="javascript:logout()" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    <aside class="sidebar" id="sidebar">

        <ul class="nav-links">
            <li class="nav-item">
                <a href="portal/dashboard.html" class="nav-link">
                    <i class="fas fa-th-large"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/profile.html" class="nav-link">
                    <i class="fas fa-user"></i><span>My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/financials.html" class="nav-link">
                    <i class="fas fa-wallet"></i><span>Financials</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/events.html" class="nav-link">
                    <i class="fas fa-calendar-alt"></i><span>Events & Calendar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/departments.html" class="nav-link">
                    <i class="fas fa-users"></i><span>Departments</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/gallery.html" class="nav-link">
                    <i class="fas fa-images"></i><span>Photo Gallery</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/resources.html" class="nav-link active">
                    <i class="fas fa-cloud-download-alt"></i><span>Resources</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="portal/prayer.html" class="nav-link">
                    <i class="fas fa-praying-hands"></i><span>Prayer Requests</span>
                </a>
            </li>
        </ul>

        <div style="margin-top: auto; padding-top: 50px;">
            <a href="javascript:logout()" class="nav-link" style="color: #ef4444;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>Resources & Library</h1>
            <p>Access and download sermon notes, constitutions, and spiritual materials.</p>
        </div>

        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search for documents, audio, or files..."
                onkeyup="filterResources()">
        </div>

        <div class="filter-tabs">
            <div class="filter-tab active" onclick="setCategory('all')">All Files</div>
            <div class="filter-tab" onclick="setCategory('sermon')">Sermon Notes</div>
            <div class="filter-tab" onclick="setCategory('constitution')">Constitution & Guides</div>
            <div class="filter-tab" onclick="setCategory('audio')">Audio Sermons</div>
            <div class="filter-tab" onclick="setCategory('form')">Forms & Templates</div>
        </div>

        <div class="resource-grid" id="resource-container">
            <div style="text-align: center; color: var(--text-gray); grid-column: 1/-1; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
                <p style="margin-top: 10px;">Loading resources...</p>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); }
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');
            const profile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('profileDropdown');

            if (sidebar && sidebar.classList.contains('active') && !sidebar.contains(event.target) && toggle && !toggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }

            // Profile logic
            if (dropdown && dropdown.classList.contains('active') && !profile.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.toggle('active');
        }

        function logout() {
            fetch('portal/auth_logout.php').then(() => {
                window.location.href = 'portal/index.html';
            });
        }

        // Auth Check & Load Resources
        document.addEventListener('DOMContentLoaded', function () {
            // Auth
            fetch('portal/auth_session.php').then(res => res.json()).then(data => {
                if (!data.logged_in) {
                    window.location.href = 'portal/index.html';
                } else {
                    const name = data.user_name || 'Saint';
                    document.getElementById('user-name-display').textContent = name;
                    document.getElementById('user-initial').textContent = name.charAt(0).toUpperCase();
                }
            });

            // Load Resources
            loadResources();
        });

        function loadResources() {
            fetch('portal/api_media.php?action=list_resources_public')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('resource-container');

                    if (data.success && data.data.length > 0) {
                        container.innerHTML = data.data.map(item => {
                            let iconClass = 'icon-doc';
                            let iconFa = 'fa-file-alt';

                            const ext = item.file_type ? item.file_type.toLowerCase() : '';
                            if (['pdf'].includes(ext)) { iconClass = 'icon-pdf'; iconFa = 'fa-file-pdf'; }
                            else if (['doc', 'docx'].includes(ext)) { iconClass = 'icon-doc'; iconFa = 'fa-file-word'; }
                            else if (['mp3', 'wav'].includes(ext)) { iconClass = 'icon-audio'; iconFa = 'fa-music'; }
                            else if (['jpg', 'png', 'jpeg'].includes(ext)) { iconClass = 'icon-image'; iconFa = 'fa-image'; }

                            // Map category to class for filtering (lowercase, remove spaces)
                            const catAttr = item.category ? item.category.toLowerCase().replace(/\s+/g, '') : 'general';

                            return `
                                <div class="resource-card" data-category="${catAttr}">
                                    <div class="file-icon ${iconClass}"><i class="fas ${iconFa}"></i></div>
                                    <h3 class="resource-title">${item.title}</h3>
                                    <div class="resource-meta">
                                        <span><i class="fas fa-calendar"></i> ${new Date(item.created_at).toLocaleDateString()}</span>
                                        <span><i class="fas fa-file"></i> ${ext.toUpperCase()}</span>
                                    </div>
                                    <p class="resource-desc">${item.description || ''}</p>
                                    <a href="portal/${item.file_url}" class="btn-download" download target="_blank">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            `;
                        }).join('');
                    } else {
                        container.innerHTML = `
                             <div style="text-align: center; color: var(--text-gray); grid-column: 1/-1; padding: 40px; background: #fff; border-radius: 16px;">
                                <i class="fas fa-folder-open" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                                <p>No resources available at the moment.</p>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('resource-container').innerHTML = '<p style="text-align: center; color: red;">Failed to load resources.</p>';
                });
        }

        // Filter Logic
        let currentCategory = 'all';

        function setCategory(cat) {
            currentCategory = cat.toLowerCase().replace(/\s+/g, ''); // Normalize

            // Update tabs
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');

            filterResources();
        }

        function filterResources() {
            const query = document.querySelector('.search-input').value.toLowerCase();
            const cards = document.querySelectorAll('.resource-card');

            cards.forEach(card => {
                const title = card.querySelector('.resource-title').textContent.toLowerCase();
                const category = card.dataset.category; // Ensure this matches normalization

                const matchesQuery = title.includes(query);

                // Flexible matching for category
                let matchesCategory = false;
                if (currentCategory === 'all') {
                    matchesCategory = true;
                } else {
                    // Normalize the card category for comparison if it wasn't strictly controlled
                    const cardCatNormalized = category.toLowerCase().replace(/\s+/g, '');
                    if (cardCatNormalized.includes(currentCategory) || currentCategory.includes(cardCatNormalized)) {
                        matchesCategory = true;
                    }
                }

                if (matchesQuery && matchesCategory) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>