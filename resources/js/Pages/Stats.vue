<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';
import html2canvas from 'html2canvas';
import { ref, computed } from 'vue';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const props = defineProps({
    categories: Array,
    counts: Array,
    totalGoals: Number,
    avgProgress: Number
});

const isExporting = ref(false);

const chartData = computed(() => ({
    labels: props.categories,
    datasets: [{
        label: 'Visions',
        data: props.counts,
        backgroundColor: [
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#ec4899', // Pink
            '#10b981', // Emerald
            '#f59e0b'  // Amber
        ],
        hoverOffset: 15,
        borderWidth: 0,
        borderRadius: 10, 
        cutout: '75%',
    }]
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: '#a1a1aa',
                padding: 20,
                usePointStyle: true,
                font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#18181b',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 13 },
            padding: 12,
            displayColors: true,
            cornerRadius: 12
        }
    }
};

const exportReport = async () => {
    isExporting.value = true;
    const element = document.getElementById('report-area');

    setTimeout(async () => {
        const canvas = await html2canvas(element, {
            backgroundColor: '#050505',
            scale: 3,
            logging: false,
            useCORS: true,
            borderRadius: 20
        });

        const link = document.createElement('a');
        link.download = `V-ARC-2026-Report.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        isExporting.value = false;
    }, 500);
};
</script>

<template>
    <Head title="Vision Analytics" />
    <AuthenticatedLayout>
        <div class="v26-stats-page">
            <header class="v26-top-bar">
                <div class="header-titles">
                    <h1>Growth <span>Analytics</span></h1>
                    <p class="subtitle">Reviewing your 2026 evolution</p>
                </div>
                <button @click="exportReport" class="v26-export-btn" :disabled="isExporting">
                    <span v-if="!isExporting">💾 Save Report</span>
                    <span v-else class="shimmer">Generating Image...</span>
                </button>
            </header>

            <main class="v26-report-wrapper" id="report-area">
                <div class="report-brand">V<span>.</span>ARC 2026</div>

                <div class="v26-summary-grid">
                    <div class="stat-box">
                        <div class="stat-content">
                            <span class="label">Total Visions</span>
                            <div class="number">{{ totalGoals }}</div>
                        </div>
                        <div class="stat-icon">🎯</div>
                    </div>

                    <div class="stat-box accent">
                        <div class="stat-content">
                            <span class="label">Overall Completion</span>
                            <div class="number">{{ avgProgress }}%</div>
                            <div class="v26-progress-track">
                                <div class="v26-progress-fill" :style="{ width: avgProgress + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="v26-charts-layout">
                    <div class="chart-card">
                        <h3>Vision Breakdown</h3>
                        <div class="chart-container">
                            <Doughnut :data="chartData" :options="chartOptions" />
                            <div class="chart-center-text">
                                <span class="total">{{ totalGoals }}</span>
                                <span class="txt">Goals</span>
                            </div>
                        </div>
                    </div>

                    <div class="strategy-card">
                        <h3>Performance Insight</h3>
                        <div class="insight-body">
                            <div v-if="totalGoals > 0" class="insight-msg">
                                <p>You are most active in <strong>{{ categories[0] }}</strong>. This focus represents {{ Math.round((counts[0]/totalGoals)*100) }}% of your 2026 strategy.</p>
                            </div>

                            <div class="quote-box">
                                <p>"The best way to predict the future is to create it."</p>
                            </div>

                            <div class="status-indicator">
                                <div class="pulse-dot"></div>
                                <span>System analyzing your progress...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v26-stats-page { padding: 40px 20px; max-width: 1200px; margin: 0 auto; color: #fff; }

.v26-top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.v26-top-bar h1 { font-size: 2rem; font-weight: 900; letter-spacing: -1px; }
.v26-top-bar h1 span { color: #8b5cf6; }
.subtitle { color: #71717a; font-size: 0.9rem; }

.v26-export-btn {
    background: #fff; color: #000; border: none; padding: 12px 24px;
    border-radius: 14px; font-weight: 800; cursor: pointer; transition: 0.3s;
}
.v26-export-btn:hover { background: #8b5cf6; color: #fff; transform: translateY(-2px); }


.v26-report-wrapper {
    background: #050505; padding: 40px; border-radius: 32px;
    border: 1px solid #18181b; position: relative;
}

.report-brand {
    position: absolute; top: 40px; right: 40px; font-weight: 900;
    font-size: 0.8rem; color: #3f3f46; letter-spacing: 2px;
}
.report-brand span { color: #8b5cf6; }

.v26-summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
.stat-box {
    background: #0e0e11; padding: 30px; border-radius: 24px;
    border: 1px solid #18181b; display: flex; justify-content: space-between; align-items: center;
}
.stat-box.accent { background: linear-gradient(135deg, #0e0e11 0%, #1e152e 100%); border-color: rgba(139, 92, 246, 0.3); }

.label { color: #71717a; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
.number { font-size: 3.5rem; font-weight: 900; margin: 5px 0; letter-spacing: -2px; }

.v26-progress-track { width: 100%; height: 6px; background: #27272a; border-radius: 10px; margin-top: 15px; overflow: hidden; }
.v26-progress-fill { height: 100%; background: #8b5cf6; border-radius: 10px; box-shadow: 0 0 15px rgba(139, 92, 246, 0.5); }


.v26-charts-layout { display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px; }
.chart-card, .strategy-card { background: #0e0e11; padding: 35px; border-radius: 28px; border: 1px solid #18181b; }

h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 25px; color: #e4e4e7; }

.chart-container { height: 320px; position: relative; }
.chart-center-text {
    position: absolute; top: 43%; left: 50%; transform: translate(-50%, -50%);
    text-align: center; display: flex; flex-direction: column;
}
.chart-center-text .total { font-size: 2rem; font-weight: 900; line-height: 1; }
.chart-center-text .txt { font-size: 0.7rem; color: #71717a; text-transform: uppercase; font-weight: 700; }

.insight-body { display: flex; flex-direction: column; height: 100%; justify-content: space-between; }
.insight-msg p { color: #a1a1aa; line-height: 1.6; font-size: 0.95rem; }

.quote-box {
    margin-top: 30px; padding: 20px; background: #18181b;
    border-radius: 16px; border-left: 4px solid #8b5cf6;
}
.quote-box p { font-style: italic; color: #d4d4d8; font-size: 0.9rem; }

.status-indicator { display: flex; align-items: center; gap: 10px; margin-top: 30px; color: #52525b; font-size: 0.8rem; }
.pulse-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981; animation: pulse 2s infinite; }

@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

@media (max-width: 900px) {
    .v26-summary-grid, .v26-charts-layout { grid-template-columns: 1fr; }
}
</style>
