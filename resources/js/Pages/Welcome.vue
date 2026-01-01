<template>
    <div class="v26-root">
        <nav class="v26-nav">
            <div class="v26-nav-inner">
                <div class="v26-logo">VISION<span>ARC</span>2026</div>
                <div class="v26-nav-actions">
                    <Link v-if="!$page.props.auth.user" :href="route('login')" class="v26-btn-ghost">Login</Link>

                    <Link v-if="!$page.props.auth.user" :href="route('register')" class="v26-btn-glow">
                        <span class="v26-text-desktop">Get Started Free</span>
                        <span class="v26-text-mobile">Start</span>
                    </Link>

                    <Link v-else :href="route('dashboard')" class="v26-btn-glow">Dashboard</Link>
                </div>
            </div>
        </nav>

        <header class="v26-hero">
            <div class="v26-hero-content">
                <div class="v26-promo-tag">New Era of Productivity</div>
                <h1 class="v26-main-title">Design Your Life <br><span class="v26-gradient-text">Beyond 2026</span></h1>
                <p class="v26-description">
                    The world's first AI-ready visual manifestation board. Organize your dreams, track your evolution, and master your future.
                </p>

                <div class="v26-hero-btns">
                    <Link :href="route('register')" class="v26-btn-primary">Build Your Board Now</Link>
                    <button @click="scrollToPreview" class="v26-btn-secondary">Explore Features</button>
                </div>

                <div class="v26-stats-preview">
                    <div class="v26-stat-pill">🚀 10k+ Dreamers</div>
                    <div class="v26-stat-pill">✨ 2026 Ready</div>
                    <div class="v26-stat-pill">🛡️ Data Encrypted</div>
                </div>
            </div>
        </header>

        <section ref="previewSection" class="v26-preview-section">
            <div class="v26-glass-frame">
                <div class="v26-mock-sidebar"></div>
                <div class="v26-mock-content">
                    <div v-for="goal in displayGoals.slice(0, 3)" :key="goal.id" class="v26-real-card">
                        <div class="v26-card-media">
                            <img
                                :src="goal.image_path.startsWith('http') ? goal.image_path : `/storage/${goal.image_path}`"
                                :alt="goal.title"
                                class="v26-img-obj"
                            >
                        </div>
                        <div class="v26-card-body">
                            <h6>{{ goal.title }}</h6>
                            <div class="v26-mini-progress">
                                <div class="v26-bar-bg">
                                    <div class="v26-bar-fill" :style="{ width: goal.progress + '%' }"></div>
                                </div>
                                <span class="v26-pct">{{ goal.progress }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    goals: Array
});

const dummyGoals = [
    {
        id: 'd1',
        title: 'Mastering AI Development 2026',
        progress: 65,
        image_path: 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=400'
    },
    {
        id: 'd2',
        title: 'Digital Nomad Life in Bali',
        progress: 40,
        image_path: 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=400'
    },
    {
        id: 'd3',
        title: 'Building a $10k/mo SaaS',
        progress: 85,
        image_path: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=400'
    }
];

const displayGoals = computed(() => {
    return props.goals && props.goals.length > 0 ? props.goals : dummyGoals;
});

const previewSection = ref(null);
const scrollToPreview = () => {
    if (previewSection.value) {
        previewSection.value.scrollIntoView({ behavior: 'smooth' });
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

.v26-root {
    background: #050505;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
    width: 100%;
}

/* Navbar المطور */
.v26-nav {
    padding: 12px 0;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 100;
    background: rgba(5, 5, 5, 0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.v26-nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 15px;
}

.v26-logo {
    font-size: clamp(0.9rem, 4vw, 1.2rem);
    font-weight: 800;
    white-space: nowrap;
}
.v26-logo span { color: #8b5cf6; }

.v26-nav-actions { display: flex; align-items: center; gap: 10px; }

.v26-btn-ghost {
    text-decoration: none;
    color: #a1a1aa;
    font-weight: 600;
    font-size: 0.9rem;
}

/* تبديل النص في الموبايل */
.v26-text-mobile { display: none; }

@media (max-width: 450px) {
    .v26-btn-ghost { display: none; } /* إخفاء تسجيل الدخول في الموبايل الصغير */
    .v26-text-desktop { display: none; }
    .v26-text-mobile { display: inline; }
}

.v26-btn-glow {
    background: #fff;
    color: #000 !important;
    padding: 8px 18px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.85rem;
    white-space: nowrap;
}

/* Hero Section */
.v26-hero {
    padding: clamp(100px, 15vh, 160px) 15px 40px;
    text-align: center;
    background: radial-gradient(circle at top, #1e1b4b 0%, #050505 70%);
}

.v26-promo-tag {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    display: inline-block;
    margin-bottom: 20px;
}

.v26-main-title {
    font-size: clamp(2rem, 9vw, 5rem);
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 20px;
}

.v26-gradient-text { background: linear-gradient(90deg, #8b5cf6, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

.v26-description {
    max-width: 600px;
    margin: 0 auto 35px;
    font-size: clamp(0.9rem, 3vw, 1.1rem);
    color: #a1a1aa;
    line-height: 1.6;
}

.v26-hero-btns {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
}

@media (min-width: 640px) {
    .v26-hero-btns { flex-direction: row; justify-content: center; max-width: none; }
}

.v26-btn-primary { background: #8b5cf6; color: white; padding: 15px 30px; border-radius: 12px; text-decoration: none; font-weight: 700; }
.v26-btn-secondary { background: transparent; border: 1px solid #333; color: white; padding: 15px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; }

/* Preview Section */
.v26-preview-section { padding: 40px 15px 80px; }
.v26-glass-frame {
    max-width: 1000px;
    margin: 0 auto;
    background: rgba(20, 20, 23, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    display: flex;
    overflow: hidden;
    backdrop-filter: blur(20px);
}

.v26-mock-sidebar { width: 60px; border-right: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2); flex-shrink: 0; }
@media (max-width: 768px) { .v26-mock-sidebar { display: none; } }

.v26-mock-content {
    flex: 1;
    padding: clamp(15px, 3vw, 30px);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
    gap: 20px;
}

.v26-real-card { background: #0f0f12; border: 1px solid #222; border-radius: 16px; overflow: hidden; }
.v26-card-media { width: 100%; height: 150px; }
.v26-img-obj { width: 100%; height: 100%; object-fit: cover; }
.v26-card-body { padding: 15px; }
.v26-card-body h6 { margin: 0 0 10px; font-size: 0.95rem; }

.v26-mini-progress { display: flex; align-items: center; gap: 10px; }
.v26-bar-bg { flex: 1; height: 6px; background: #222; border-radius: 10px; overflow: hidden; }
.v26-bar-fill { height: 100%; background: linear-gradient(90deg, #8b5cf6, #d946ef); }
.v26-pct { font-size: 0.7rem; color: #777; font-weight: 800; }

.v26-stats-preview { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 35px; }
.v26-stat-pill { background: #111; padding: 6px 14px; border-radius: 50px; border: 1px solid #222; font-size: 0.7rem; color: #888; }
</style>
