<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-slate-800">Evolución del Paciente</h3>
    <div class="flex bg-slate-100 p-1 rounded-lg">
        <button onclick="updateChart('bp')" class="chart-btn active-chart px-4 py-1.5 rounded text-xs font-bold bg-white shadow-sm text-indigo-600">Presión Arterial</button>
        <button onclick="updateChart('weight')" class="chart-btn px-4 py-1.5 rounded text-xs font-bold text-slate-500 hover:text-slate-700">Peso</button>
        <button onclick="updateChart('hr')" class="chart-btn px-4 py-1.5 rounded text-xs font-bold text-slate-500 hover:text-slate-700">F. Cardíaca</button>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
    <canvas id="vitalsChart" height="100"></canvas>
</div>