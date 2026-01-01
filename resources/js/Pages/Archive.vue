<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    archivedGoals: Array
});

// وظيفة لضمان الحصول على رابط الصورة الكامل لحل مشكلة السواد في الرندر
const getImageUrl = (path) => {
    if (!path) return '';
    // نستخدم window.location.origin للتأكد من أن الرابط يبدأ بـ http/https
    return `${window.location.origin}/storage/${path}`;
};

const unarchive = (id) => {
    router.patch(`/goals/${id}/archive`, {}, {
        onSuccess: () => {
            Swal.fire({
                title: 'Restored!',
                text: 'Goal moved back to your active board.',
                icon: 'success',
                background: '#121212',
                color: '#fff',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
};
</script>

<template>
    <Head title="Vision Archive" />

    <AuthenticatedLayout>
        <div class="v26-archive-container">
            <header class="v26-archive-header">
                <div class="header-text">
                    <span class="v26-label">Hall of Fame</span>
                    <h1>Your <span>Accomplishments</span></h1>
                    <p>Every milestone you've reached in 2026.</p>
                </div>
            </header>

            <div v-if="archivedGoals.length > 0" class="v26-grid">
                <div v-for="goal in archivedGoals" :key="goal.id" class="v26-card archived">
                    <div class="v26-card-media">
                        <div class="status-badge">🏆 Completed</div>

                        <div
                            class="blur-bg"
                            :style="{ backgroundImage: `url(${getImageUrl(goal.image_path)})` }"
                        ></div>

                        <img
                            :src="getImageUrl(goal.image_path)"
                            :alt="goal.title"
                            crossorigin="anonymous"
                            class="v26-full-img"
                        >
                        <div class="image-overlay"></div>
                    </div>

                    <div class="v26-card-content">
                        <div class="card-meta">
                            <span class="category-tag">{{ goal.category }}</span>
                            <span class="date-tag">2026 Archive</span>
                        </div>
                        <h3>{{ goal.title }}</h3>
                        <button @click="unarchive(goal.id)" class="v26-restore-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                            </svg>
                            Restore to Board
                        </button>
                    </div>
                </div>
            </div>

            <div v-else class="v26-empty-state">
                <div class="empty-icon">📂</div>
                <p>No archived visions yet.</p>
                <small>Complete a goal and click the archive button to see it here.</small>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v26-archive-container { padding: 40px 20px; max-width: 1200px; margin: 0 auto; }
.v26-archive-header { margin-bottom: 50px; }

.v26-label {
    background: linear-gradient(90deg, #8e2de2, #4ecdc4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 3px;
}

.v26-archive-header h1 { font-size: clamp(2.2rem, 5vw, 3.5rem); font-weight: 800; color: #fff; letter-spacing: -2px; margin: 10px 0; }
.v26-archive-header h1 span { color: #8e2de2; }
.v26-archive-header p { color: #71717a; font-size: 1.1rem; }

/* Grid System */
.v26-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 35px;
}

/* Card Styling */
.v26-card {
    background: #0a0a0a;
    border: 1px solid rgba(255, 255, 255, 0.03);
    border-radius: 32px;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    position: relative;
}

.v26-card:hover {
    transform: translateY(-12px);
    border-color: rgba(142, 45, 226, 0.3);
    box-shadow: 0 40px 80px -15px rgba(0, 0, 0, 0.8);
}

.v26-card-media {
    width: 100%;
    aspect-ratio: 4 / 3;
    position: relative;
    overflow: hidden;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.blur-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(20px) brightness(0.4);
    transform: scale(1.2);
    z-index: 0;
}

.v26-full-img {
    position: relative;
    z-index: 1;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    display: block; /* يمنع الفراغات الصغيرة أسفل الصورة */
}

.v26-card:hover .v26-full-img {
    transform: scale(1.05);
}

.status-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 8px 16px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    z-index: 10;
}

/* Content Styling */
.v26-card-content { padding: 30px; }

.category-tag {
    font-size: 10px;
    color: #a78bfa;
    font-weight: 800;
    text-transform: uppercase;
    background: rgba(139, 92, 246, 0.1);
    padding: 6px 14px;
    border-radius: 100px;
}

.v26-card-content h3 {
    font-size: 22px;
    margin: 20px 0 30px;
    color: #fff;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.v26-restore-btn {
    width: 100%;
    padding: 16px;
    background: #111;
    border: 1px solid #1f1f1f;
    color: #efefef;
    border-radius: 20px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.v26-restore-btn:hover {
    background: #fff;
    color: #000;
    border-color: #fff;
}

.v26-empty-state {
    text-align: center;
    padding: 100px 20px;
    color: #71717a;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .v26-grid { grid-template-columns: 1fr; }
    .v26-archive-container { padding: 20px; }
}
</style>
