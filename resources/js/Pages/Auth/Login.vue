<template>
    <div class="v26-auth-page">
        <div class="v26-bg-glow"></div>

        <div class="auth-card">
            <div class="brand">V<span>.</span>Arc</div>
            <h2>Welcome Back</h2>
            <p>Enter your details to access your 2026 visions.</p>

            <form @submit.prevent="submit">
                <div class="input-group">
                    <label>Email Address</label>
                    <input
                        type="email"
                        v-model="form.email"
                        placeholder="name@example.com"
                        required
                        :class="{ 'input-error': form.errors.email }"
                    >
                    <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input
                        type="password"
                        v-model="form.password"
                        placeholder="••••••••"
                        required
                        :class="{ 'input-error': form.errors.password }"
                    >
                    <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
                </div>

                <div class="remember-me">
                    <label class="checkbox-container">
                        <input type="checkbox" v-model="form.remember">
                        <span class="checkmark"></span>
                        Keep me signed in
                    </label>
                </div>

                <button type="submit" :disabled="form.processing" class="login-btn">
                    <span v-if="form.processing" class="loader"></span>
                    {{ form.processing ? 'Verifying...' : 'Sign In' }}
                </button>
            </form>

            <p class="footer-text">Don't have an account? <Link :href="route('register')">Join the vision</Link></p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
.v26-auth-page {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #050505;
    color: white;
    font-family: 'Plus Jakarta Sans', sans-serif;
    position: relative;
    overflow: hidden;
}

.v26-bg-glow {
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, transparent 70%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 0;
}

.auth-card {
    background: rgba(14, 14, 17, 0.8);
    padding: 50px;
    border-radius: 40px;
    width: 90%;
    max-width: 450px;
    border: 1px solid rgba(255, 255, 255, 0.05);
    text-align: center;
    backdrop-filter: blur(20px);
    z-index: 1;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.brand { font-size: 2.2rem; font-weight: 900; color: #8b5cf6; margin-bottom: 20px; letter-spacing: -1px; }
.brand span { color: #fff; }

h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 10px; }
p { color: #71717a; margin-bottom: 30px; font-size: 0.95rem; }

.input-group { text-align: left; margin-bottom: 24px; }
label { display: block; margin-bottom: 10px; font-size: 0.85rem; font-weight: 600; color: #a1a1aa; }

input[type="email"], input[type="password"] {
    width: 100%;
    background: #18181b;
    border: 1px solid #27272a;
    padding: 16px;
    border-radius: 16px;
    color: #fff;
    outline: none;
    transition: 0.3s;
}

input:focus {
    border-color: #8b5cf6;
    background: #1a1a1e;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
}

.input-error { border-color: #ef4444 !important; }
.error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 6px; display: block; }

.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    font-size: 0.85rem;
    color: #a1a1aa;
}

.login-btn {
    width: 100%;
    background: #fff;
    color: #000;
    border: none;
    padding: 16px;
    border-radius: 16px;
    font-weight: 800;
    cursor: pointer;
    transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.login-btn:hover {
    background: #8b5cf6;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2);
}

.login-btn:disabled { opacity: 0.7; cursor: not-allowed; }

.footer-text { margin-top: 30px; font-size: 0.9rem; color: #71717a; }
.footer-text a { color: #8b5cf6; text-decoration: none; font-weight: 700; margin-left: 5px; }
.footer-text a:hover { text-decoration: underline; }
</style>
