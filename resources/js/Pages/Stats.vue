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
        hoverOffset: 20,
        borderWidth: 0,
        borderRadius: 12,
        cutout: '78%',
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
                pointStyle: 'circle',
                font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#18181b',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 13 },
            padding: 12,
            cornerRadius: 12,
            displayColors: true,
        }
    }
};

const exportReport = async () => {
    isExporting.value = true;
    const element = document.getElementById('report-area');

    setTimeout(async () => {
        const canvas = await html2canvas(element, {
            backgroundColor: '#050505',
            scale: 2,
            logging: false,
            useCORS: true,
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
                    <span class="system-tag">Intelligence Dashboard</span>
                    <h1>Growth <span>Analytics</span></h1>
                </div>
                <button @click="exportReport" class="v26-export-btn" :disabled="isExporting">
                    <span v-if="!isExporting">Download Report</span>
                    <span v-else>Processing...</span>
                </button>
            </header>

            <main class="v26-report-wrapper" id="report-area">
                <div class="report-header-meta">
                    <div class="brand-box">
                        <span class="dot"></span>
                        <span class="brand-text">V.ARC ENGINE</span>
                    </div>
                    <span class="date-tag">SESSION: 2026_ANL</span>
                </div>

                <div class="v26-metrics-grid">
                    <div class="metric-item">
                        <span class="label">Total Visions</span>
                        <div class="val-group">
                            <span class="num">{{ totalGoals }}</span>
                            <span class="unit">Goals</span>
                        </div>
                    </div>
                    <div class="metric-item accent">
                        <span class="label">Efficiency Rate</span>
                        <div class="val-group">
                            <span class="num">{{ avgProgress }}%</span>
                            <div class="bar-bg">
                                <div class="bar-fill" :style="{ width: avgProgress + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="v26-analysis-section">
                    <div class="analysis-card chart-side">
                        <div class="card-info">
                            <h3>Vision Breakdown</h3>
                            <p>Sector distribution based on 2026 activity.</p>
                        </div>
                        <div class="chart-box">
                            <Doughnut :data="chartData" :options="chartOptions" />
                            <div class="chart-center">{{ totalGoals }}</div>
                        </div>
                    </div>

                    <div class="analysis-card insight-side">
                        <div class="card-info">
                            <h3>Performance Insight</h3>
                            <div class="insight-pill" v-if="categories && categories.length">
                                Primary focus detected in <span>{{ categories[0] }}</span>
                            </div>
                        </div>
                        <div class="insight-details">
                            <p class="desc" v-if="totalGoals > 0">
                                Your strategy is currently leaning <strong>{{ Math.round((counts[0]/totalGoals)*100) }}%</strong> towards this sector.
                                System suggests maintaining this momentum.
                            </p>
                            <div class="quote-minimal">
                                "The best way to predict the future is to create it."
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="report-footer">
                    <div class="status-indicator">
                        <span class="pulse"></span>
                        Neural Analysis Complete
                    </div>
                    <div class="timestamp">REF_ID: {{ new Date().getTime().toString().slice(-8) }}</div>
                </footer>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Main Page Container */
.v26-stats-page {
    padding: clamp(30px, 5vw, 60px) 16px;
    max-width: 1000px;
    margin: 0 auto;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Header Section */
.v26-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.system-tag {
    font-size: 10px;
    letter-spacing: 3px;
    color: #8b5cf6;
    font-weight: 800;
    text-transform: uppercase;
}

.v26-top-bar h1 {
    font-size: clamp(1.5rem, 5vw, 1.8rem);
    font-weight: 800;
    margin-top: 5px;
    letter-spacing: -1.2px;
}

.v26-top-bar h1 span { color: #8b5cf6; }

.v26-export-btn {
    background: #ffffff;
    color: #000;
    padding: 12px 28px;
    border-radius: 100px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
    white-space: nowrap;
}

/* Report Canvas */
.v26-report-wrapper {
    background: #000000;
    border: 1px solid #111;
    border-radius: clamp(20px, 4vw, 32px);
    padding: clamp(20px, 5vw, 40px);
}

.report-header-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    flex-wrap: wrap;
    gap: 15px;
}

/* Metrics Grid - Responsive with Auto-fit */
.v26-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.metric-item {
    background: #0a0a0a;
    border: 1px solid #161616;
    padding: 30px;
    border-radius: 24px;
}

.num { font-size: clamp(2rem, 5vw, 2.4rem); font-weight: 800; }

/* Analysis Cards */
.v26-analysis-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
}

.analysis-card {
    background: #0a0a0a;
    border: 1px solid #161616;
    border-radius: 24px;
    padding: clamp(20px, 5vw, 35px);
    display: flex;
    flex-direction: column;
    min-height: 400px;
}

/* Chart Container */
.chart-box {
    width: 100%;
    max-width: 200px;
    aspect-ratio: 1/1;
    margin: auto;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-center {
    position: absolute;
    font-size: 1.8rem;
    font-weight: 800;
}

.insight-pill {
    background: rgba(139, 92, 246, 0.08);
    color: #8b5cf6;
    padding: 10px 18px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 700;
    margin: 15px 0;
    width: fit-content;
}

.desc { font-size: 14px; line-height: 1.8; color: #71717a; }

.quote-minimal {
    margin-top: auto;
    padding: 20px;
    background: rgba(255,255,255,0.02);
    border-radius: 20px;
    font-style: italic;
    font-size: 13px;
    color: #555;
    border-left: 3px solid #8b5cf6;
}

/* Footer */
.report-footer {
    margin-top: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 10px;
    color: #222;
}

.pulse {
    width: 6px; height: 6px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    animation: simple-pulse 2s infinite;
}

@keyframes simple-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Mobile Specific Tweaks */
@media (max-width: 480px) {
    .v26-top-bar { flex-direction: column; align-items: flex-start; }
    .v26-export-btn { width: 100%; text-align: center; }
    .v26-analysis-section { grid-template-columns: 1fr; }
}
</style>
