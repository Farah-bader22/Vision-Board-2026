<template>
    <div class="v26-auth-page">
        <div class="v26-ambient-light"></div>
        <div class="v26-dots-overlay"></div>

        <div class="v26-auth-card">
            <div class="v26-auth-header">
                <div class="v26-mini-logo">V<span>.</span>ARC</div>
                <h2>Create Account</h2>
                <p>Join 10,000+ visionaries shaping 2026.</p>
            </div>

            <form @submit.prevent="submit">
                <div class="v26-input-group">
                    <label>Full Name</label>
                    <input type="text" v-model="form.name" placeholder="Farah Bader" required autofocus :class="{'input-error': form.errors.name}" />
                    <span v-if="form.errors.name" class="error-msg">{{ form.errors.name }}</span>
                </div>

                <div class="v26-input-group">
                    <label>Email Address</label>
                    <input type="email" v-model="form.email" placeholder="farah@example.com" required :class="{'input-error': form.errors.email}" />
                    <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                </div>

                <div class="v26-grid-inputs">
                    <div class="v26-input-group">
                        <label>Password</label>
                        <input type="password" v-model="form.password" placeholder="••••••••" required :class="{'input-error': form.errors.password}" />
                    </div>
                    <div class="v26-input-group">
                        <label>Confirm</label>
                        <input type="password" v-model="form.password_confirmation" placeholder="••••••••" required />
                    </div>
                </div>
                <span v-if="form.errors.password" class="error-msg mb-4">{{ form.errors.password }}</span>

                <button type="submit" class="v26-submit-btn" :disabled="form.processing">
                    {{ form.processing ? 'Creating Vision...' : 'Start Your Vision Board' }}
                </button>
            </form>

            <div class="v26-auth-divider"><span>OR CONTINUE WITH</span></div>

            <div class="v26-social-grid">
                <button class="v26-social-btn">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="G"> Google
                </button>
                <button class="v26-social-btn">
                    <img src="https://www.svgrepo.com/show/511330/apple-173.svg" width="18" filter="invert(1)" alt="A"> Apple
                </button>
            </div>

            <p class="v26-switch-auth">
                Already have an account? <Link :href="route('login')">Sign In</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<style scoped>
.v26-auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #050505;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

/* خلفية النجوم المتحركة خفيفاً */
.v26-dots-overlay {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(#ffffff10 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
}

.v26-ambient-light {
    position: absolute;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.08) 0%, transparent 70%);
    z-index: 0;
    animation: pulse 10s infinite alternate;
}

@keyframes pulse {
    from { transform: translate(-20%, -20%); }
    to { transform: translate(10%, 10%); }
}

.v26-auth-card {
    background: rgba(18, 18, 18, 0.8);
    backdrop-filter: blur(15px);
    padding: 40px;
    border-radius: 40px;
    width: 100%;
    max-width: 480px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.8);
    z-index: 1;
}

.v26-mini-logo {
    font-size: 1.5rem; font-weight: 900; color: #8b5cf6;
    margin-bottom: 15px; text-align: center; letter-spacing: 2px;
}
.v26-mini-logo span { color: #fff; }

h2 { color: #fff; font-size: 1.7rem; font-weight: 800; text-align: center; margin-bottom: 8px; }
p { color: #71717a; text-align: center; margin-bottom: 30px; font-size: 0.9rem; }

.v26-input-group { margin-bottom: 20px; text-align: left; }
.v26-grid-inputs { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

label { display: block; color: #a1a1aa; font-size: 0.8rem; font-weight: 600; margin-bottom: 8px; margin-left: 4px; }

input {
    width: 100%; background: #0a0a0a; border: 1px solid #27272a;
    padding: 15px; border-radius: 16px; color: #fff; outline: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

input:focus {
    border-color: #8b5cf6;
    background: #000;
    box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
}

.input-error { border-color: #ef4444 !important; }
.error-msg { color: #ef4444; font-size: 0.75rem; margin-top: 5px; display: block; }
.mb-4 { margin-bottom: 1rem; }

.v26-submit-btn {
    width: 100%; background: #fff; color: #000; padding: 16px;
    border-radius: 16px; font-weight: 800; border: none; cursor: pointer;
    transition: 0.3s; margin-top: 5px;
}

.v26-submit-btn:hover {
    background: #8b5cf6; color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
}

.v26-auth-divider {
    margin: 25px 0; text-align: center;
    border-bottom: 1px solid #27272a; line-height: 0.1em;
}
.v26-auth-divider span { background: #121212; padding: 0 15px; color: #52525b; font-size: 0.7rem; font-weight: 800; }

.v26-social-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
.v26-social-btn {
    background: #18181b; border: 1px solid #27272a; color: #fff;
    padding: 12px; border-radius: 16px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.3s;
}
.v26-social-btn:hover { background: #27272a; border-color: #3f3f46; }

.v26-switch-auth { text-align: center; color: #71717a; font-size: 0.9rem; }
.v26-switch-auth a { color: #8b5cf6; text-decoration: none; font-weight: 700; margin-left: 5px; }
.v26-switch-auth a:hover { text-decoration: underline; }
</style>
