<template>
    <div class="v26-root">
        <nav class="v26-nav">
            <div class="v26-nav-inner">
                <div class="v26-logo">VISION<span>ARC</span>2026</div>
                <div class="v26-nav-actions">
                    <Link v-if="!$page.props.auth.user" :href="route('login')" class="v26-btn-ghost">Login</Link>
                    <Link v-if="!$page.props.auth.user" :href="route('register')" class="v26-btn-glow">Get Started Free</Link>
                    <Link v-else :href="route('dashboard')" class="v26-btn-glow">Go to Dashboard</Link>
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

// تعريف الـ Props
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    goals: Array
});

// الأهداف الافتراضية الملهمة (Dummy Data)
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

// منطق عرض الأهداف: إذا كانت المصفوفة القادمة من السيرفر فارغة، اعرض الافتراضية
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
}

/* Navbar */
.v26-nav {
    padding: 20px 0;
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
    padding: 0 20px;
}

.v26-logo { font-size: 1.2rem; font-weight: 800; letter-spacing: 1px; }
.v26-logo span { color: #8b5cf6; }

.v26-btn-ghost { text-decoration: none; color: #a1a1aa; font-weight: 600; margin-right: 20px; transition: 0.3s; }
.v26-btn-ghost:hover { color: #fff; }
.v26-btn-glow { background: #fff; color: #000; padding: 10px 24px; border-radius: 50px; text-decoration: none; font-weight: 700; transition: 0.3s; }
.v26-btn-glow:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,255,255,0.2); }

/* Hero */
.v26-hero {
    padding: 160px 20px 80px;
    text-align: center;
    background: radial-gradient(circle at top, #1e1b4b 0%, #050505 70%);
}

.v26-main-title { font-size: clamp(2.5rem, 8vw, 5rem); font-weight: 800; line-height: 1.1; letter-spacing: -3px; margin-bottom: 20px; }
.v26-gradient-text { background: linear-gradient(90deg, #8b5cf6, #d946ef); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.v26-description { max-width: 650px; margin: 0 auto 40px; font-size: clamp(1rem, 2vw, 1.2rem); color: #a1a1aa; line-height: 1.6; }

.v26-hero-btns { display: flex; flex-direction: column; align-items: center; gap: 15px; }
@media (min-width: 640px) { .v26-hero-btns { flex-direction: row; justify-content: center; } }

.v26-btn-primary { background: #8b5cf6; color: white; padding: 18px 36px; border-radius: 14px; text-decoration: none; font-weight: 700; transition: 0.3s; }
.v26-btn-primary:hover { background: #7c3aed; transform: translateY(-3px); box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3); }

.v26-btn-secondary { background: transparent; border: 1px solid #3f3f46; color: white; padding: 18px 36px; border-radius: 14px; font-weight: 700; cursor: pointer; transition: 0.3s; }
.v26-btn-secondary:hover { background: rgba(255,255,255,0.05); }

/* Preview Section */
.v26-preview-section { padding: 40px 20px 120px; scroll-margin-top: 100px; }
.v26-glass-frame {
    max-width: 1000px;
    margin: 0 auto;
    background: rgba(20, 20, 23, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 32px;
    display: flex;
    min-height: 400px;
    overflow: hidden;
    backdrop-filter: blur(20px);
    animation: floating 6s ease-in-out infinite; /* إضافة حركة الطفو */
}

@keyframes floating {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

.v26-mock-sidebar { width: 70px; border-right: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.3); flex-shrink: 0; }
.v26-mock-content { flex: 1; padding: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }

/* Real Goal Card */
.v26-real-card {
    background: #0f0f12;
    border: 1px solid #222;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.v26-real-card:hover {
    transform: translateY(-10px) scale(1.02);
    border-color: #8b5cf6;
    box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
    background: #141418;
}

.v26-card-media {
    width: 100%;
    height: 160px;
    background: #000;
    overflow: hidden;
}

.v26-img-obj {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.5s;
}

.v26-real-card:hover .v26-img-obj {
    transform: scale(1.1);
}

.v26-card-body { padding: 16px; }
.v26-card-body h6 { font-size: 1rem; font-weight: 700; margin-bottom: 12px; color: #fff; }

.v26-mini-progress { display: flex; align-items: center; gap: 12px; }
.v26-bar-bg { flex: 1; height: 6px; background: #222; border-radius: 10px; overflow: hidden; }
.v26-bar-fill { height: 100%; background: linear-gradient(90deg, #8b5cf6, #d946ef); border-radius: 10px; }
.v26-pct { font-size: 12px; color: #888; font-weight: 800; }

/* Stats Pill */
.v26-stats-preview { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 50px; }
.v26-stat-pill { background: #111; padding: 10px 20px; border-radius: 50px; border: 1px solid #222; font-size: 0.85rem; font-weight: 600; color: #ccc; }

/* Responsive Queries */
@media (max-width: 768px) {
    .v26-mock-sidebar { display: none; }
    .v26-main-title { font-size: 3.2rem; }
    .v26-mock-content { grid-template-columns: 1fr; }
}
</style>
