<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <h3 class="text-lg font-bold text-slate-800">Crecimiento OMS (Percentiles)</h3>
    <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
        <button onclick="changePediatricMetric('weight')" id="btn-p-weight" class="p-metric-btn active-p-metric px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-white shadow-sm text-indigo-600">Peso</button>
        <button onclick="changePediatricMetric('height')" id="btn-p-height" class="p-metric-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-500">Talla</button>
        <button onclick="changePediatricMetric('head')" id="btn-p-head" class="p-metric-btn px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-slate-500">Cabeza</button>
    </div>
</div>
<div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm relative h-[550px]">
    <canvas id="growthChartCanvas"></canvas>
</div>