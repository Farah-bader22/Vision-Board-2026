<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="v26-profile-section">
        <header class="v26-section-header">
            <h2 class="v26-title">
                Security Upgrade
            </h2>

            <p class="v26-subtitle">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="v26-form">
            <div class="v26-input-group">
                <InputLabel for="current_password" value="Current Password" class="v26-label" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="v26-input"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError :message="form.errors.current_password" class="v26-error" />
            </div>

            <div class="v26-input-group">
                <InputLabel for="password" value="New Password" class="v26-label" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="v26-input"
                    autocomplete="new-password"
                    placeholder="New secure password"
                />

                <InputError :message="form.errors.password" class="v26-error" />
            </div>

            <div class="v26-input-group">
                <InputLabel for="password_confirmation" value="Confirm Password" class="v26-label" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="v26-input"
                    autocomplete="new-password"
                    placeholder="Repeat new password"
                />

                <InputError :message="form.errors.password_confirmation" class="v26-error" />
            </div>

            <div class="v26-action-area">
                <PrimaryButton :disabled="form.processing" class="v26-btn-save">
                    {{ form.processing ? 'Updating...' : 'Update Password' }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="v26-success-msg">
                        Successfully updated.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
.v26-profile-section {
    background: #0a0a0a;
    padding: 40px;
    border-radius: 32px;
    border: 1px solid #1a1a1a;
    max-width: 600px;
}

.v26-section-header {
    margin-bottom: 35px;
}

.v26-title {
    font-size: 24px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.5px;
}

.v26-subtitle {
    font-size: 14px;
    color: #555;
    margin-top: 8px;
}

.v26-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.v26-label {
    color: #8e2de2 !important; /* لون الماركة */
    font-size: 12px !important;
    text-transform: uppercase;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.v26-input {
    background: #000 !important;
    border: 1px solid #1a1a1a !important;
    color: #fff !important;
    border-radius: 16px !important;
    padding: 14px 20px !important;
    width: 100%;
    transition: 0.3s;
}

.v26-input:focus {
    border-color: #8e2de2 !important;
    box-shadow: 0 0 0 2px rgba(142, 45, 226, 0.1) !important;
}

.v26-error {
    color: #ff3b30;
    font-size: 12px;
    margin-top: 8px;
    font-weight: 500;
}

.v26-action-area {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 10px;
}

.v26-btn-save {
    background: #8e2de2 !important;
    color: #fff !important;
    border: none !important;
    padding: 14px 28px !important;
    border-radius: 14px !important;
    font-weight: 700 !important;
    text-transform: none !important;
    letter-spacing: 0 !important;
    transition: 0.3s !important;
    cursor: pointer;
}

.v26-btn-save:hover {
    background: #a445f0 !important;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(142, 45, 226, 0.2);
}

.v26-btn-save:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.v26-success-msg {
    font-size: 14px;
    color: #4ecdc4;
    font-weight: 600;
}

/* تحسين شكل الـ placeholder */
::placeholder {
    color: #333;
    opacity: 1;
}
</style>
