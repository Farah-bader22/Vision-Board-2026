<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import confetti from 'canvas-confetti';
// تأكدي من استيراد مكتبة الرندر إذا كنتِ تستخدمينها في هذا الملف
// import html2canvas from 'html2canvas';

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

// وظيفة مساعدة للحصول على الرابط الكامل للصورة (لحل مشكلة السواد)
const getImageUrl = (path) => {
    if (!path) return '';
    // استخدام window.location.origin يضمن أن الرندر يرى الرابط كاملاً سواء على جهازك أو على Render.com
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

// ملاحظة: إذا أضفتِ زر الرندر مستقبلاً، تأكدي من إضافة useCORS: true في الإعدادات
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

                <div class="v26-grid" id="report-area"> <div v-for="goal in goals" :key="goal.id" class="v26-card">
                        <div class="v26-card-img-wrapper" @click.stop>
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
                        <p>Start your journey</p>
                    </div>
                </div>
            </main>

            <Transition name="modal-fade">
                <div v-if="showModal" class="v26-modal-overlay" @click.self="showModal = false">
                    <div class="v26-modal">
                        <div class="v26-modal-header">
                            <h2>{{ isEditing ? 'Edit Vision' : 'New Vision' }}</h2>
                            <p>Map your future for 2026</p>
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
                                    {{ form.processing ? 'Saving...' : 'Confirm' }}
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
/* التنسيق العام */
.v26-app { display: flex; min-height: 100vh; background: #000; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }

/* Sidebar */
.v26-sidebar { width: 260px; border-right: 1px solid #111; padding: 40px 25px; flex-shrink: 0; background: #050505; }
.v26-logo { font-size: 28px; font-weight: 900; color: #8e2de2; margin-bottom: 60px; }
.v26-logo span { color: #fff; }
.v26-nav { display: flex; flex-direction: column; gap: 8px; }
.v26-link { display: flex; align-items: center; gap: 12px; padding: 14px 18px; color: #666; text-decoration: none; border-radius: 12px; transition: 0.3s; }
.v26-link.active { color: #8e2de2; background: rgba(142, 45, 226, 0.1); }

/* Main */
.v26-main { flex: 1; padding: 40px 60px; max-width: 1400px; margin: 0 auto; width: 100%; }
.v26-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
.v26-label { color: #8e2de2; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; }

/* الزر الأساسي */
.v26-btn-main {
    background: #fff;
    color: #000;
    border: none;
    padding: 12px 24px;
    border-radius: 100px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: 0.3s ease;
    flex-shrink: 0;
}

.btn-icon { font-size: 18px; line-height: 1; }

/* Grid & Cards */
.v26-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
.v26-card { background: #0d0d0d; border: 1px solid #1a1a1a; border-radius: 24px; overflow: hidden; transition: 0.4s; }

.v26-card-img-wrapper { height: 220px; position: relative; background: #050505; cursor: pointer; overflow: hidden; }

/* تنسيق الصورة الجديد لضمان الرندر السليم */
.v26-actual-img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* للحفاظ على أبعاد الصورة كاملة */
    display: block;
}

.v26-card-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.6); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s ease; }
.v26-card-img-wrapper:hover .v26-card-overlay, .v26-card-img-wrapper:active .v26-card-overlay { opacity: 1; }

.v26-card-actions { display: flex; gap: 10px; }
.v26-mini-btn { width: 40px; height: 40px; border-radius: 12px; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.v26-mini-btn.edit { background: #8e2de2; }
.v26-mini-btn.del { background: #ff3b30; }
.v26-mini-btn.archive { background: #007aff; }

.v26-card-tag { position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.7); padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 600; }
.v26-card-content { padding: 20px; }
.v26-prog-bar { height: 6px; background: #1a1a1a; border-radius: 10px; margin: 12px 0; }
.v26-prog-fill { height: 100%; background: linear-gradient(90deg, #8e2de2, #4a00e0); border-radius: 10px; }
.v26-prog-info { display: flex; justify-content: space-between; font-size: 12px; color: #666; }

/* المودال */
.v26-modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999; padding: 20px; }
.v26-modal { background: #111; border: 1px solid #222; width: 100%; max-width: 480px; padding: 30px; border-radius: 28px; }
.v26-modal-header h2 { font-size: 24px; font-weight: 800; }
.v26-modal-header p { color: #555; font-size: 14px; margin-bottom: 25px; }

.v26-input-group { margin-bottom: 18px; }
.v26-input-group label { display: block; font-size: 11px; color: #888; margin-bottom: 8px; text-transform: uppercase; font-weight: 700; }
.v26-form input, .v26-form select { width: 100%; background: #000; border: 1px solid #222; padding: 14px; border-radius: 12px; color: #fff; outline: none; }
.v26-form input:focus { border-color: #8e2de2; }
.v26-input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

.v26-file-area { position: relative; }
.v26-file-label { display: flex; flex-direction: column; align-items: center; padding: 20px; border: 2px dashed #222; border-radius: 15px; cursor: pointer; }
.v26-hidden { display: none; }
.v26-upload-icon { font-size: 24px; margin-bottom: 5px; }
.v26-file-status { font-size: 12px; color: #666; }

.v26-modal-actions { display: flex; gap: 12px; margin-top: 25px; }
.v26-btn-confirm { flex: 2; background: #8e2de2; color: #fff; border: none; padding: 15px; border-radius: 12px; font-weight: 700; cursor: pointer; }
.v26-btn-cancel { flex: 1; background: #222; color: #fff; border: none; padding: 15px; border-radius: 12px; font-weight: 600; cursor: pointer; }

/* الموبايل */
@media (max-width: 768px) {
    .v26-sidebar { display: none; }
    .v26-main { padding: 20px; }
    .v26-header h1 { font-size: 22px; }

    .v26-btn-main {
        width: 44px;
        height: 44px;
        padding: 0;
        border-radius: 50%;
    }
    .v26-btn-main .btn-text {
        display: none;
    }
    .v26-btn-main .btn-icon {
        margin: 0;
        font-size: 24px;
    }

    .v26-modal-overlay { align-items: flex-end; padding: 0; }
    .v26-modal { border-radius: 30px 30px 0 0; }
}
</style>
