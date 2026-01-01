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
                            <div class="bar-bg"><div class="bar-fill" :style="{ width: avgProgress + '%' }"></div></div>
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
                            <div class="insight-pill">
                                Primary focus detected in <span>{{ categories[0] }}</span>
                            </div>
                        </div>
                        <div class="insight-details">
                            <p class="desc">
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
.v26-stats-page {
    padding: 60px 24px;
    max-width: 1000px;
    margin: 0 auto;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* 1. Header: ضبط المسافات والمحاذاة لتشبه فرح بورد */
.v26-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
}

.system-tag {
    font-size: 10px;
    letter-spacing: 3px;
    color: #8b5cf6;
    font-weight: 800;
    text-transform: uppercase;
}

.v26-top-bar h1 {
    font-size: 1.8rem;
    font-weight: 800;
    margin-top: 5px;
    letter-spacing: -1.2px;
}

.v26-top-bar h1 span { color: #8b5cf6; }

.v26-export-btn {
    background: #ffffff;
    color: #000;
    padding: 12px 28px;
    border-radius: 100px; /* جعل الزر بيضاوي تماماً مثل زر Create Vision */
    font-weight: 700;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* 2. Report Canvas: الحاوية الكبرى */
.v26-report-wrapper {
    background: #000000; /* خلفية سوداء صريحة لتعميق التباين */
    border: 1px solid #111;
    border-radius: 32px;
    padding: 40px;
}

.report-header-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.brand-box {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 2px;
}

.brand-box .dot {
    width: 6px;
    height: 6px;
    background: #8b5cf6;
    border-radius: 1px; /* جعل النقطة مربعة قليلاً لتعطي طابع هندسي */
}

/* 3. Metrics: توحيد الارتفاع والعرض */
.v26-metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.metric-item {
    background: #0a0a0a;
    border: 1px solid #161616;
    padding: 30px;
    border-radius: 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.label {
    font-size: 11px;
    color: #444;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.val-group {
    display: flex;
    align-items: baseline;
    gap: 12px;
    margin-top: 10px;
}

.num { font-size: 2.4rem; font-weight: 800; color: #fff; }
.unit { font-size: 12px; color: #333; font-weight: 700; }

/* 4. Analysis Section: الحل النهائي لمشكلة التناسق */
.v26-analysis-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.analysis-card {
    background: #0a0a0a;
    border: 1px solid #161616;
    border-radius: 24px;
    padding: 35px; /* مساحة داخلية أوسع لراحة العين */
    display: flex;
    flex-direction: column;
}

/* تنسيق الشارت: وضعه داخل حاوية مربعة تماماً لتوسيطه */
.chart-box {
    width: 200px; /* حجم ثابت للتحكم الكامل */
    height: 200px;
    margin: 40px auto; /* توسيط أفقي وعمودي */
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-center {
    position: absolute;
    font-size: 2rem;
    font-weight: 800;
}

/* تنسيق الـ Insights ليكون موازياً لمركز الشارت */
.insight-side {
    justify-content: space-between;
}

.card-info h3 {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
}

.insight-pill {
    background: rgba(139, 92, 246, 0.08);
    color: #8b5cf6;
    padding: 10px 18px;
    border-radius: 100px; /* توحيد مع الأزرار */
    font-size: 12px;
    font-weight: 700;
    margin: 15px 0;
    width: fit-content;
}

.desc {
    font-size: 14px;
    line-height: 1.8;
    color: #71717a; /* رمادي خفيف لتقليل الزحمة البصرية */
}

.desc strong { color: #fff; }

.quote-minimal {
    margin-top: 30px;
    padding: 20px;
    background: rgba(255,255,255,0.02);
    border-radius: 20px;
    font-style: italic;
    font-size: 13px;
    color: #555;
    border-left: 3px solid #8b5cf6;
}

/* 5. Footer: التذييل الهادئ */
.report-footer {
    margin-top: 25px;
    display: flex;
    justify-content: space-between;
    padding: 20px 10px 0;
    font-size: 10px;
    font-weight: 700;
    color: #222;
    border-top: 1px solid rgba(255,255,255,0.02);
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pulse {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    animation: simple-pulse 2s infinite;
}

@keyframes simple-pulse {
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
}

/* Mobile Adjustments */
@media (max-width: 768px) {
    .v26-metrics-grid, .v26-analysis-section { grid-template-columns: 1fr; }
    .analysis-card { padding: 30px; text-align: center; }
    .insight-pill { margin: 15px auto; }
    .chart-box { margin: 30px auto; }
}
</style>
