<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="v26-danger-section">
        <header class="v26-section-header">
            <h2 class="v26-title danger-text">
                Danger Zone
            </h2>

            <p class="v26-subtitle">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="v26-btn-danger-outline">
            Terminate Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="v26-modal-content">
                <h2 class="v26-modal-title">
                    Are you absolutely sure?
                </h2>

                <p class="v26-modal-desc">
                    This action is irreversible. Please enter your password to confirm.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="v26-input"
                        placeholder="Confirm with your password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="v26-error" />
                </div>

                <div class="v26-modal-actions">
                    <SecondaryButton @click="closeModal" class="v26-btn-cancel">
                        No, keep it
                    </SecondaryButton>

                    <DangerButton
                        class="v26-btn-final-delete"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Permanently
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
.v26-danger-section {
    background: #0a0a0a;
    padding: 40px;
    border-radius: 32px;
    border: 1px solid #3d0000; /* حدود حمراء غامقة جداً */
    max-width: 600px;
}

.v26-title.danger-text {
    color: #ff3b30;
    font-size: 24px;
    font-weight: 800;
}

.v26-subtitle {
    font-size: 14px;
    color: #555;
    margin-top: 8px;
}

.v26-btn-danger-outline {
    background: transparent !important;
    border: 1px solid #ff3b30 !important;
    color: #ff3b30 !important;
    padding: 12px 24px !important;
    border-radius: 14px !important;
    font-weight: 700 !important;
    transition: 0.3s !important;
    text-transform: none !important;
}

.v26-btn-danger-outline:hover {
    background: #ff3b30 !important;
    color: #fff !important;
    box-shadow: 0 0 15px rgba(255, 59, 48, 0.3);
}

/* الـ Modal Style */
.v26-modal-content {
    background: #0f0f0f; /* خلفية الـ Modal */
    padding: 30px;
    border: 1px solid #1a1a1a;
}

.v26-modal-title {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
}

.v26-modal-desc {
    color: #888;
    font-size: 14px;
    margin-top: 10px;
}

.v26-input {
    background: #000 !important;
    border: 1px solid #333 !important;
    color: #fff !important;
    border-radius: 12px !important;
    width: 100% !important;
    padding: 12px !important;
}

.v26-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 30px;
}

.v26-btn-cancel {
    background: #1a1a1a !important;
    color: #eee !important;
    border: none !important;
    border-radius: 10px !important;
}

.v26-btn-final-delete {
    background: #ff3b30 !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
}

.v26-error {
    color: #ff3b30;
    font-size: 12px;
    margin-top: 8px;
}
</style>
