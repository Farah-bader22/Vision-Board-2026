<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="v26-profile-section">
        <header class="v26-section-header">
            <h2 class="v26-title">
                Identity Profile
            </h2>

            <p class="v26-subtitle">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="v26-form"
        >
            <div class="v26-input-group">
                <InputLabel for="name" value="Full Name" class="v26-label" />

                <TextInput
                    id="name"
                    type="text"
                    class="v26-input"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your name"
                />

                <InputError class="v26-error" :message="form.errors.name" />
            </div>

            <div class="v26-input-group">
                <InputLabel for="email" value="Email Address" class="v26-label" />

                <TextInput
                    id="email"
                    type="email"
                    class="v26-input"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="your@email.com"
                />

                <InputError class="v26-error" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="v26-warning-text">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="v26-link-verify"
                    >
                        Click here to re-send verification.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="v26-status-msg"
                >
                    A new verification link has been sent.
                </div>
            </div>

            <div class="v26-action-area">
                <PrimaryButton :disabled="form.processing" class="v26-btn-save">
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="v26-success-msg">
                        Profile updated successfully.
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
    margin-bottom: 30px;
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
    color: #8e2de2 !important;
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

.v26-warning-text {
    font-size: 13px;
    color: #ffd60a;
    margin-top: 8px;
}

.v26-link-verify {
    color: #8e2de2;
    text-decoration: underline;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.v26-status-msg {
    margin-top: 8px;
    font-size: 13px;
    color: #4ecdc4;
}

.v26-action-area {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 10px;
}

.v26-btn-save {
    background: #fff !important; /* خليت الزر هون أبيض عشان يكسر اللون البنفسجي شوي */
    color: #000 !important;
    border: none !important;
    padding: 14px 28px !important;
    border-radius: 14px !important;
    font-weight: 700 !important;
    transition: 0.3s !important;
}

.v26-btn-save:hover {
    background: #8e2de2 !important;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(142, 45, 226, 0.2);
}

.v26-success-msg {
    font-size: 14px;
    color: #4ecdc4;
    font-weight: 600;
}
</style>
