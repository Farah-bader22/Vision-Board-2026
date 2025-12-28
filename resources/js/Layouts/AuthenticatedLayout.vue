<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="dashboard-layout">
        <aside class="sidebar-pro">
            <div class="sidebar-brand">V<span>.</span>Arc</div>

            <nav class="sidebar-nav">
                <Link :href="route('dashboard')"
                      :class="{'active': route().current('dashboard')}"
                      class="nav-item">
                    Dashboard
                </Link>

                <Link :href="route('stats')"
                      :class="{'active': route().current('stats')}"
                      class="nav-item">
                    Statistics
                </Link>

                <Link :href="route('archive')"
                      :class="{'active': route().current('archive')}"
                      class="nav-item">
                    Archive
                </Link>

                <Link :href="route('profile.edit')"
                      :class="{'active': route().current('profile.edit')}"
                      class="nav-item">
                    Settings
                </Link>
            </nav>

            <div class="sidebar-footer">
                <Link :href="route('logout')"
                      method="post"
                      as="button"
                      class="logout-btn">
                    Logout
                </Link>
            </div>
        </aside>

        <main class="main-pro">
            <header class="top-header">
                <div class="header-info">
                    <span class="welcome-text">Welcome back,</span>
                    <span class="user-name">{{ $page.props.auth.user.name }}</span>
                </div>
            </header>

            <div class="content-wrapper">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
/* تحسين الألوان لتناسب التصميم الاحترافي الغامق */
.dashboard-layout { display: flex; height: 100vh; background: #050505; color: #fff; }

.sidebar-pro {
    width: 260px;
    background: #000;
    border-right: 1px solid #111;
    display: flex;
    flex-direction: column;
    padding: 30px 15px;
}

.sidebar-brand {
    font-size: 1.8rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 50px;
    text-align: center;
    letter-spacing: -1px;
}
.sidebar-brand span { color: #8e2de2; }

.sidebar-nav { flex: 1; display: flex; flex-direction: column; gap: 5px; }

.nav-item {
    display: block;
    padding: 12px 20px;
    border-radius: 12px;
    cursor: pointer;
    color: #666;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
}

/* حالة الرابط النشط */
.nav-item.active {
    background: #111;
    color: #fff;
    border-left: 3px solid #8e2de2;
}

.nav-item:hover { color: #8e2de2; background: rgba(142, 45, 226, 0.05); }

.logout-btn {
    background: none;
    border: none;
    color: #ff3b30;
    font-weight: 700;
    cursor: pointer;
    padding: 15px 20px;
    width: 100%;
    text-align: left;
    transition: 0.3s;
}
.logout-btn:hover { background: rgba(255, 59, 48, 0.1); border-radius: 10px; }

.main-pro { flex: 1; display: flex; flex-direction: column; overflow-y: auto; background: #080808; }

.top-header {
    padding: 20px 40px;
    display: flex;
    justify-content: flex-end;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #111;
}

.header-info { display: flex; flex-direction: column; align-items: flex-end; }
.welcome-text { color: #444; font-size: 12px; }
.user-name { color: #fff; font-weight: 600; font-size: 14px; }

.content-wrapper { padding: 40px; }

/* Responsive */
@media (max-width: 768px) {
    .dashboard-layout { flex-direction: column; }
    .sidebar-pro { width: 100%; height: auto; padding: 15px; border-right: none; border-bottom: 1px solid #111; }
    .sidebar-nav { flex-direction: row; overflow-x: auto; gap: 10px; }
    .sidebar-brand { margin-bottom: 15px; font-size: 1.2rem; }
    .nav-item { white-space: nowrap; padding: 8px 15px; font-size: 13px; }
}
</style>
