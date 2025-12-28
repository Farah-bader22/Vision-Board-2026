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
                customClass: {
                    popup: 'v26-swal-custom'
                }
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
                text: 'Moved to your collection of victories.',
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
                        <span class="nav-icon">⊞</span> Dashboard
                    </Link>
                    <Link :href="route('stats')" class="v26-link">
                        <span class="nav-icon">📊</span> Statistics
                    </Link>
                    <Link :href="route('archive')" class="v26-link" :class="{ 'active': route().current('archive') }">
                        <span class="nav-icon">📁</span> Archive
                    </Link>
                </nav>
            </aside>

            <main class="v26-main">
                <header class="v26-header">
                    <div class="v26-welcome">
                        <span class="v26-label">Dream Big</span>
                        <h1>{{ $page.props.auth.user.name }}'s Board</h1>
                    </div>
                    <button @click="openCreateModal" class="v26-btn-main">+ Create Vision</button>
                </header>

                <div class="v26-grid">
                    <div v-for="goal in goals" :key="goal.id" class="v26-card">
                        <div class="v26-card-img" :style="{ backgroundImage: `url(/storage/${goal.image_path})` }">
                            <div class="v26-card-overlay"></div>
                            <div class="v26-card-actions">
                                <button v-if="goal.progress == 100" @click="archiveGoal(goal.id)" class="v26-mini-btn archive" title="Archive Goal">📥</button>
                                <button @click="openEditModal(goal)" class="v26-mini-btn edit">✎</button>
                                <button @click="deleteGoal(goal.id)" class="v26-mini-btn del">✕</button>
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
                        <p>Your 2026 journey starts here</p>
                    </div>
                </div>
            </main>

            <Transition name="modal-fade">
                <div v-if="showModal" class="v26-modal-overlay" @click.self="showModal = false">
                    <div class="v26-modal">
                        <div class="modal-header">
                            <h2>{{ isEditing ? 'Edit Vision' : 'New Vision' }}</h2>
                            <p>Map your future for 2026</p>
                        </div>

                        <form @submit.prevent="submitForm">
                            <div class="v26-input-box">
                                <label>Vision Title</label>
                                <input v-model="form.title" type="text" placeholder="e.g. Master AI Engineering" required>
                            </div>

                            <div class="v26-grid-inputs">
                                <div class="v26-input-box">
                                    <label>Category</label>
                                    <select v-model="form.category">
                                        <option>Career</option>
                                        <option>Health</option>
                                        <option>Wealth</option>
                                        <option>Travel</option>
                                    </select>
                                </div>
                                <div class="v26-input-box">
                                    <label>Progress (%)</label>
                                    <input v-model="form.progress" type="number" min="0" max="100">
                                </div>
                            </div>

                            <div class="v26-input-box">
                                <label>Inspiration Image</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" @input="form.image = $event.target.files[0]" accept="image/*" id="file" class="hidden-input">
                                    <label for="file" class="file-upload-label">
                                        <span class="upload-icon">🖼️</span>
                                        {{ form.image ? form.image.name : 'Click to upload inspiration' }}
                                    </label>
                                </div>
                            </div>

                            <div class="v26-modal-btns">
                                <button type="button" @click="showModal = false" class="v26-btn-cancel">Cancel</button>
                                <button type="submit" class="v26-btn-save" :disabled="form.processing">
                                    {{ form.processing ? 'Syncing...' : 'Confirm Vision' }}
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
/* Main App Structure */
.v26-app { display: flex; min-height: 100vh; background: #000; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }

/* Sidebar */
.v26-sidebar { width: 260px; border-right: 1px solid #111; padding: 40px 25px; flex-shrink: 0; background: #050505; }
.v26-logo { font-size: 28px; font-weight: 900; color: #8e2de2; margin-bottom: 60px; letter-spacing: -1px; }
.v26-logo span { color: #fff; }
.v26-nav { display: flex; flex-direction: column; gap: 8px; }
.v26-link { display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: #666; text-decoration: none; font-weight: 600; border-radius: 12px; transition: 0.3s; }
.v26-link:hover, .v26-link.active { color: #fff; background: rgba(255,255,255,0.03); }
.v26-link.active { color: #8e2de2; background: rgba(142, 45, 226, 0.1); }
.nav-icon { font-size: 18px; }

/* Main Content */
.v26-main { flex: 1; padding: 40px 60px; }
.v26-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px; }
.v26-label { color: #8e2de2; text-transform: uppercase; font-size: 11px; font-weight: 800; letter-spacing: 2px; }
.v26-header h1 { font-size: 32px; font-weight: 800; margin-top: 5px; }

/* Main Button with Glow */
.v26-btn-main {
    background: #fff; color: #000; border: none; padding: 14px 32px; border-radius: 100px;
    font-weight: 700; cursor: pointer; transition: 0.4s;
    box-shadow: 0 0 20px rgba(255,255,255,0.1);
}
.v26-btn-main:hover { background: #8e2de2; color: #fff; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(142, 45, 226, 0.3); }

/* Grid & Cards */
.v26-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 35px; }
.v26-card {
    background: #0d0d0d; border: 1px solid #1a1a1a; border-radius: 32px;
    overflow: hidden; transition: 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.v26-card:hover { transform: translateY(-12px); border-color: #333; box-shadow: 0 30px 60px rgba(0,0,0,0.6); }

.v26-card-img { height: 220px; background-size: cover; background-position: center; position: relative; }
.v26-card-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.4)); }

.v26-card-tag {
    position: absolute; bottom: 15px; left: 15px; background: #8e2de2;
    font-size: 10px; font-weight: 800; padding: 6px 14px; border-radius: 10px;
    text-transform: uppercase; letter-spacing: 1px; z-index: 2;
}

.v26-card-actions {
    position: absolute; top: 15px; right: 15px; display: flex; gap: 8px;
    opacity: 0; transform: translateY(-10px); transition: 0.3s; z-index: 3;
}
.v26-card:hover .v26-card-actions { opacity: 1; transform: translateY(0); }

.v26-mini-btn {
    width: 38px; height: 38px; border-radius: 12px; border: none;
    background: rgba(0,0,0,0.5); color: #fff; cursor: pointer;
    backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; transition: 0.3s;
}
.v26-mini-btn:hover { background: #fff; color: #000; }
.v26-mini-btn.del:hover { background: #ff3b30; color: #fff; }
.v26-mini-btn.archive:hover { background: #4ecdc4; color: #000; }

.v26-card-content { padding: 25px; }
.v26-card-content h3 { font-size: 20px; font-weight: 700; margin-bottom: 18px; color: #eee; }

/* Progress Bar */
.v26-prog-container { margin-top: 10px; }
.v26-prog-bar { height: 8px; background: #1a1a1a; border-radius: 10px; overflow: hidden; }
.v26-prog-fill { height: 100%; background: linear-gradient(90deg, #8e2de2, #4a00e0); border-radius: 10px; transition: 1.5s cubic-bezier(0.2, 0.8, 0.2, 1); }
.v26-prog-info { display: flex; justify-content: space-between; margin-top: 10px; }
.v26-prog-text { font-size: 12px; color: #555; font-weight: 700; }
.v26-target-year { font-size: 12px; color: #222; font-weight: 900; }

/* Empty State */
.v26-empty { border: 2px dashed #1a1a1a; border-radius: 32px; height: 350px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; color: #333; }
.v26-empty:hover { border-color: #8e2de2; background: rgba(142,45,226,0.02); color: #fff; }
.v26-empty .plus { font-size: 54px; margin-bottom: 10px; font-weight: 200; }

/* Modal Styling */
.v26-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(20px); display: flex; align-items: center; justify-content: center; z-index: 1000; }
.v26-modal { background: #0a0a0a; border: 1px solid #1a1a1a; padding: 45px; border-radius: 40px; width: 100%; max-width: 520px; box-shadow: 0 40px 100px rgba(0,0,0,0.8); }
.modal-header { margin-bottom: 35px; }
.modal-header h2 { font-size: 28px; font-weight: 800; margin-bottom: 5px; }
.modal-header p { color: #444; font-size: 14px; }

.v26-input-box { margin-bottom: 25px; }
.v26-input-box label { display: block; font-size: 12px; color: #555; margin-bottom: 10px; text-transform: uppercase; font-weight: 700; }

input, select {
    width: 100%; background: #000; border: 1px solid #1a1a1a; padding: 16px;
    border-radius: 16px; color: #fff; outline: none; transition: 0.3s; font-weight: 500;
}
input:focus, select:focus { border-color: #8e2de2; background: rgba(142,45,226,0.05); }

.v26-grid-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

/* File Upload Custom */
.file-upload-wrapper { position: relative; }
.hidden-input { display: none; }
.file-upload-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 25px; border: 1px dashed #222; border-radius: 16px; cursor: pointer; transition: 0.3s;
}
.file-upload-label:hover { border-color: #8e2de2; background: rgba(255,255,255,0.02); }
.upload-icon { font-size: 24px; margin-bottom: 8px; }

.v26-modal-btns { display: flex; gap: 15px; margin-top: 40px; }
.v26-btn-save { flex: 2; background: #8e2de2; color: #fff; border: none; padding: 18px; border-radius: 18px; font-weight: 800; cursor: pointer; transition: 0.3s; }
.v26-btn-save:hover { transform: scale(1.02); box-shadow: 0 10px 20px rgba(142, 45, 226, 0.2); }
.v26-btn-cancel { flex: 1; background: #1a1a1a; color: #fff; border: none; padding: 18px; border-radius: 18px; font-weight: 700; cursor: pointer; }

/* Animations */
.modal-fade-enter-active, .modal-fade-leave-active { transition: 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; transform: scale(0.9) translateY(20px); }

@media (max-width: 900px) {
    .v26-app { flex-direction: column; }
    .v26-sidebar { width: 100%; padding: 20px; }
    .v26-main { padding: 30px 20px; }
}
</style>
