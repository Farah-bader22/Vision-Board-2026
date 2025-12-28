<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile Settings" />

    <AuthenticatedLayout>
        <div class="profile-page-wrapper">
            <header class="profile-header">
                <div class="header-content">
                    <span class="v.arc-label">Account Management</span>
                    <h1>System <span>Settings</span></h1>
                    <p>Update your personal information and security preferences.</p>
                </div>
            </header>

            <div class="profile-sections-container">
                <div class="settings-card shadow-lg">
                    <div class="card-inner">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>
                </div>

                <div class="settings-card shadow-lg">
                    <div class="card-inner">
                        <UpdatePasswordForm />
                    </div>
                </div>

                <div class="settings-card danger-card shadow-lg">
                    <div class="card-inner">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

.profile-page-wrapper {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}


.profile-header {
    margin-bottom: 40px;
}
.v.arc-label {
    color: #8e2de2;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
}
.profile-header h1 {
    font-size: 32px;
    font-weight: 800;
    color: #fff;
    margin-top: 5px;
}
.profile-header h1 span {
    color: #8e2de2;
}
.profile-header p {
    color: #666;
    font-size: 14px;
}


.profile-sections-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.settings-card {
    background: #0a0a0a;
    border: 1px solid #1a1a1a;
    border-radius: 24px;
    transition: transform 0.3s ease;
}

.settings-card:hover {
    border-color: #333;
}

.card-inner {
    padding: 30px;
}


.danger-card {
    border: 1px dashed #330000;
}

:deep(h2) {
    color: #fff !important;
    font-size: 1.25rem !important;
    font-weight: 700 !important;
}

:deep(p) {
    color: #888 !important;
    font-size: 0.875rem !important;
}

:deep(input) {
    background-color: #111 !important;
    border: 1px solid #222 !important;
    color: #fff !important;
    border-radius: 12px !important;
    padding: 12px !important;
}

:deep(label) {
    color: #aaa !important;
    font-weight: 500 !important;
    margin-bottom: 8px !important;
}

:deep(button[type="submit"]), :deep(.btn-primary) {
    background: #8e2de2 !important;
    border: none !important;
    border-radius: 12px !important;
    padding: 10px 24px !important;
    font-weight: 700 !important;
    transition: 0.3s !important;
}

:deep(button:hover) {
    opacity: 0.9;
    transform: scale(1.02);
}


@media (max-width: 640px) {
    .profile-page-wrapper {
        padding: 15px;
    }
    .profile-header h1 {
        font-size: 24px;
    }
    .card-inner {
        padding: 20px;
    }
}
</style>
