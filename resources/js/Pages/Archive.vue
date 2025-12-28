<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    archivedGoals: Array
});

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
                        <img
                            :src="`/storage/${goal.image_path}`"
                            :alt="goal.title"
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
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
.v26-archive-container { padding: 30px 0; max-width: 1200px; margin: 0 auto; }
.v26-archive-header { margin-bottom: 50px; text-align: left; }
.v26-label {
    background: linear-gradient(90deg, #8e2de2, #4ecdc4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 2px;
}
.v26-archive-header h1 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 800; color: #fff; letter-spacing: -1px; }
.v26-archive-header h1 span { color: #8e2de2; }
.v26-archive-header p { color: #a1a1aa; margin-top: 10px; font-size: 1.1rem; }

.v26-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}


.v26-card {
    background: #111111;
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 32px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.v26-card:hover {
    transform: translateY(-10px);
    border-color: rgba(142, 45, 226, 0.4);
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7), 0 18px 36px -18px rgba(142, 45, 226, 0.3);
}

.v26-card-media {
    width: 100%;
    aspect-ratio: 16 / 9;
    position: relative;
    overflow: hidden;
    background: #000;
}

.v26-full-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s ease, filter 0.8s ease;
    filter: grayscale(60%) brightness(0.7);
}

.v26-card:hover .v26-full-img {
    filter: grayscale(0%) brightness(1);
    transform: scale(1.1);
}

.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #111 0%, transparent 40%);
    z-index: 1;
}

.status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(78, 205, 196, 0.9);
    backdrop-filter: blur(5px);
    color: #000;
    padding: 6px 14px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 800;
    z-index: 2;
}


.v26-card-content { padding: 25px; position: relative; z-index: 2; }

.card-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.category-tag {
    font-size: 10px;
    color: #8e2de2;
    font-weight: 800;
    text-transform: uppercase;
    background: rgba(142, 45, 226, 0.1);
    padding: 4px 10px;
    border-radius: 6px;
}
.date-tag { font-size: 10px; color: #52525b; font-weight: 600; }

.v26-card-content h3 { font-size: 20px; margin-bottom: 25px; color: #fff; font-weight: 700; line-height: 1.4; }


.v26-restore-btn {
    width: 100%;
    padding: 14px;
    background: #18181b;
    border: 1px solid #27272a;
    color: #fff;
    border-radius: 16px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.v26-restore-btn:hover {
    background: #fff;
    color: #000;
    border-color: #fff;
    transform: scale(1.02);
}

.btn-icon { transition: transform 0.3s ease; }
.v26-restore-btn:hover .btn-icon { transform: rotate(-180deg); }


.v26-empty-state {
    text-align: center;
    margin-top: 80px;
    padding: 60px;
    background: #09090b;
    border-radius: 40px;
    border: 1px dashed #27272a;
}
.empty-icon { font-size: 60px; margin-bottom: 20px; opacity: 0.5; }
.v26-empty-state p { font-size: 20px; color: #fff; font-weight: 600; }
.v26-empty-state small { color: #52525b; display: block; margin-top: 10px; }

@media (max-width: 768px) {
    .v26-grid { grid-template-columns: 1fr; }
    .v26-archive-header { text-align: center; }
}
</style>
