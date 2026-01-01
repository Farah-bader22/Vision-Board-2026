<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import confetti from 'canvas-confetti';

const props = defineProps({
    goals: { type: Array, default: () => [] }
});

const showModal = ref(false);
const isEditing = ref(false);
const currentGoalId = ref(null);

const form = useForm({
    title: '',
    category: 'Career',
    image: null,
    progress: 0,
    _method: 'POST'
});

const getImageUrl = (path) => {
    if (!path) return '';
    return `${window.location.origin}/storage/${path}`;
};

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form._method = 'POST';
    showModal.value = true;
};

const openEditModal = (goal) => {
    isEditing.value = true;
    currentGoalId.value = goal.id;
    form.title = goal.title;
    form.category = goal.category;
    form.progress = goal.progress;
    form.image = null;
    form._method = 'PUT';
    showModal.value = true;
};

const submitForm = () => {
    const url = isEditing.value ? `/goals/${currentGoalId.value}` : '/goals';
    form.post(url, {
        forceFormData: true,
        onSuccess: () => {
            if (form.progress == 100) {
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#8e2de2', '#ffffff', '#4a00e0']
                });
            }
            showModal.value = false;
            form.reset();
            Swal.fire({
                title: isEditing.value ? 'Vision Updated!' : 'Vision Manifested!',
                text: 'Your 2026 board has been synced.',
                icon: 'success',
                background: '#121212',
                color: '#fff',
                showConfirmButton: false,
                timer: 2000,
            });
        }
    });
};

const deleteGoal = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "This vision will be permanently removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff3b30',
        cancelButtonColor: '#2c2c2e',
        confirmButtonText: 'Yes, delete it!',
        background: '#121212',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/goals/${id}`);
        }
    });
};

const archiveGoal = (id) => {
    router.patch(`/goals/${id}/archive`, {}, {
        onSuccess: () => {
            Swal.fire({
                title: 'Goal Archived!',
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
    <Head title="VisionArc 2026 - Dashboard" />

    <AuthenticatedLayout>
        <div class="v26-app">
            <aside class="v26-sidebar">
                <div class="v26-logo">V<span>.</span>Arc</div>
                <nav class="v26-nav">
                    <Link :href="route('dashboard')" class="v26-link" :class="{ 'active': route().current('dashboard') }">
                        <span class="nav-icon">⊞</span> <span class="nav-text">Dashboard</span>
                    </Link>
                    <Link :href="route('stats')" class="v26-link">
                        <span class="nav-icon">📊</span> <span class="nav-text">Stats</span>
                    </Link>
                    <Link :href="route('archive')" class="v26-link" :class="{ 'active': route().current('archive') }">
                        <span class="nav-icon">📁</span> <span class="nav-text">Archive</span>
                    </Link>
                </nav>
            </aside>

            <main class="v26-main">
                <header class="v26-header">
                    <div class="v26-welcome">
                        <span class="v26-label">Dream Big</span>
                        <h1>{{ $page.props.auth.user.name.split(' ')[0] }}'s Board</h1>
                    </div>
                    <button @click="openCreateModal" class="v26-btn-main">
                        <span class="btn-icon">+</span>
                        <span class="btn-text">Create Vision</span>
                    </button>
                </header>

                <div class="v26-grid">
                    <div v-for="goal in goals" :key="goal.id" class="v26-card">
                        <div class="v26-card-media">
                            <img
                                :src="getImageUrl(goal.image_path)"
                                :alt="goal.title"
                                crossorigin="anonymous"
                                class="v26-actual-img"
                            >
                            <div class="v26-card-overlay">
                                <div class="v26-card-actions">
                                    <button v-if="goal.progress == 100" @click="archiveGoal(goal.id)" class="v26-mini-btn archive">📥</button>
                                    <button @click="openEditModal(goal)" class="v26-mini-btn edit">✎</button>
                                    <button @click="deleteGoal(goal.id)" class="v26-mini-btn del">✕</button>
                                </div>
                            </div>
                            <span class="v26-card-tag">{{ goal.category }}</span>
                        </div>

                        <div class="v26-card-content">
                            <h3>{{ goal.title }}</h3>
                            <div class="v26-prog-container">
                                <div class="v26-prog-bar">
                                    <div class="v26-prog-fill" :style="{ width: goal.progress + '%' }"></div>
                                </div>
                                <div class="v26-prog-info">
                                    <span class="v26-prog-text">{{ goal.progress }}% Complete</span>
                                    <span class="v26-target-year">2026</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="goals.length === 0" class="v26-empty" @click="openCreateModal">
                        <div class="plus">+</div>
                        <p>No visions found. Start your journey.</p>
                    </div>
                </div>
            </main>

            <Transition name="modal-fade">
                <div v-if="showModal" class="v26-modal-overlay" @click.self="showModal = false">
                    <div class="v26-modal">
                        <div class="v26-modal-header">
                            <h2>{{ isEditing ? 'Edit Vision' : 'New Vision' }}</h2>
                            <p>Define your milestones for 2026</p>
                        </div>

                        <form @submit.prevent="submitForm" class="v26-form">
                            <div class="v26-input-group">
                                <label>Vision Title</label>
                                <input v-model="form.title" type="text" placeholder="e.g. Master AI Engineering" required>
                            </div>

                            <div class="v26-input-row">
                                <div class="v26-input-group">
                                    <label>Category</label>
                                    <select v-model="form.category">
                                        <option>Career</option>
                                        <option>Health</option>
                                        <option>Wealth</option>
                                        <option>Travel</option>
                                    </select>
                                </div>
                                <div class="v26-input-group">
                                    <label>Progress (%)</label>
                                    <input v-model="form.progress" type="number" min="0" max="100">
                                </div>
                            </div>

                            <div class="v26-input-group">
                                <label>Inspiration Image</label>
                                <div class="v26-file-area">
                                    <input type="file" @input="form.image = $event.target.files[0]" accept="image/*" id="file-upload" class="v26-hidden">
                                    <label for="file-upload" class="v26-file-label">
                                        <span class="v26-upload-icon">🖼️</span>
                                        <span class="v26-file-status">{{ form.image ? form.image.name : 'Click to upload image' }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="v26-modal-actions">
                                <button type="button" @click="showModal = false" class="v26-btn-cancel">Cancel</button>
                                <button type="submit" class="v26-btn-confirm" :disabled="form.processing">
                                    {{ form.processing ? 'Processing...' : 'Save Vision' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v26-app { display: flex; min-height: 100vh; background: #000; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }

/* Sidebar */
.v26-sidebar { width: 260px; border-right: 1px solid #111; padding: 40px 25px; flex-shrink: 0; background: #050505; }
.v26-logo { font-size: 28px; font-weight: 900; color: #8e2de2; margin-bottom: 60px; }
.v26-logo span { color: #fff; }
.v26-nav { display: flex; flex-direction: column; gap: 8px; }
.v26-link { display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: #666; text-decoration: none; border-radius: 12px; transition: 0.3s; }
.v26-link.active { color: #8e2de2; background: rgba(142, 45, 226, 0.1); }

/* Main Content */
.v26-main { flex: 1; padding: clamp(20px, 5vw, 40px); max-width: 1400px; margin: 0 auto; width: 100%; }
.v26-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
.v26-welcome h1 { font-size: clamp(20px, 6vw, 32px); font-weight: 800; }
.v26-label { color: #8e2de2; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

/* Buttons */
.v26-btn-main {
    background: #fff; color: #000; border: none; padding: 12px 24px; border-radius: 100px;
    font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}
.v26-btn-main:hover { transform: scale(1.05); }

/* Grid & Cards */
.v26-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 25px; }
.v26-card { background: #0d0d0d; border: 1px solid #1a1a1a; border-radius: 28px; overflow: hidden; transition: 0.4s; }

/* Full Image Fix */
.v26-card-media {
    height: 220px; position: relative; background: #050505;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.v26-actual-img { width: 100%; height: 100%; object-fit: contain; position: relative; z-index: 1; }

.v26-card-overlay {
    position: absolute; inset: 0; background: rgba(0, 0, 0, 0.7);
    display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; z-index: 2;
}
.v26-card:hover .v26-card-overlay { opacity: 1; }

.v26-mini-btn {
    width: 44px; height: 44px; border-radius: 14px; border: none; color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 18px; margin: 0 5px;
}
.v26-mini-btn.edit { background: #8e2de2; }
.v26-mini-btn.del { background: #ff3b30; }
.v26-mini-btn.archive { background: #007aff; }

.v26-card-tag {
    position: absolute; top: 15px; left: 15px; background: rgba(142, 45, 226, 0.2);
    backdrop-filter: blur(10px); color: #fff; padding: 5px 12px; border-radius: 10px;
    font-size: 10px; font-weight: 800; border: 1px solid rgba(255,255,255,0.1); z-index: 3;
}

.v26-card-content { padding: 24px; }
.v26-card-content h3 { font-size: 18px; font-weight: 700; margin-bottom: 15px; }
.v26-prog-bar { height: 6px; background: #1a1a1a; border-radius: 10px; margin-bottom: 10px; overflow: hidden; }
.v26-prog-fill { height: 100%; background: linear-gradient(90deg, #8e2de2, #4a00e0); transition: width 1s; }
.v26-prog-info { display: flex; justify-content: space-between; font-size: 11px; color: #555; font-weight: 700; }

/* Empty State */
.v26-empty { border: 2px dashed #1a1a1a; border-radius: 28px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #444; cursor: pointer; }
.v26-empty:hover { border-color: #8e2de2; color: #8e2de2; }

/* Modal Styles */
.v26-modal-overlay {
    position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 20px;
}
.v26-modal {
    background: #111; border: 1px solid #222; width: 100%; max-width: 500px; padding: 35px; border-radius: 32px;
    animation: modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
@keyframes modalScale { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.v26-input-group { margin-bottom: 20px; }
.v26-input-group label { display: block; font-size: 11px; color: #555; margin-bottom: 8px; text-transform: uppercase; font-weight: 800; }
.v26-form input, .v26-form select { width: 100%; background: #000; border: 1px solid #1a1a1a; padding: 14px; border-radius: 14px; color: #fff; outline: none; transition: 0.3s; }
.v26-form input:focus { border-color: #8e2de2; }
.v26-input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

.v26-file-label { display: flex; flex-direction: column; align-items: center; padding: 25px; border: 2px dashed #1a1a1a; border-radius: 20px; cursor: pointer; transition: 0.3s; }
.v26-file-label:hover { background: rgba(142, 45, 226, 0.05); border-color: #8e2de2; }
.v26-hidden { display: none; }

.v26-modal-actions { display: flex; gap: 12px; margin-top: 30px; }
.v26-btn-confirm { flex: 2; background: #8e2de2; color: #fff; border: none; padding: 16px; border-radius: 16px; font-weight: 800; cursor: pointer; }
.v26-btn-cancel { flex: 1; background: #1a1a1a; color: #fff; border: none; padding: 16px; border-radius: 16px; font-weight: 700; cursor: pointer; }

/* Responsive Adjustments */
@media (max-width: 768px) {
    .v26-sidebar { display: none; }
    .v26-main { padding: 20px; padding-bottom: 100px; }

    /* Mobile Floating Action Button */
    .v26-btn-main {
        position: fixed; bottom: 30px; right: 20px; width: 56px; height: 56px;
        border-radius: 50%; padding: 0; justify-content: center; background: #8e2de2;
        color: #fff; box-shadow: 0 10px 25px rgba(142, 45, 226, 0.4); z-index: 100;
    }
    .v26-btn-main .btn-text { display: none; }
    .v26-btn-main .btn-icon { font-size: 24px; }

    /* Bottom Sheet Modal for Mobile */
    .v26-modal-overlay { align-items: flex-end; padding: 0; }
    .v26-modal { border-radius: 32px 32px 0 0; max-width: 100%; animation: slideUp 0.4s ease; }
}

@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
</style>
