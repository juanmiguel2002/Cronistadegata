<div class="pd-20 card-box mb-30">
    <div class="dc-head">
        <h4 class="text-blue h4 mb-0">Evolució</h4>
        <div class="dc-seg">
            <button type="button" wire:click="$set('metric', 'posts')"
                class="dc-seg-btn {{ $metric === 'posts' ? 'active' : '' }}">Artícles</button>
            <button type="button" wire:click="$set('metric', 'visits')"
                class="dc-seg-btn {{ $metric === 'visits' ? 'active' : '' }}">Visites</button>
        </div>
    </div>

    <div class="dc-filters">
        <select wire:model.live="period" class="custom-select form-control form-control-sm">
            <option value="6">Últims 6 mesos</option>
            <option value="12">Últims 12 mesos</option>
            <option value="ytd">Aquest any</option>
        </select>
        <select wire:model.live="category" class="custom-select form-control form-control-sm">
            <option value="">Totes les categoríes</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="dc-kpis">
        <div class="dc-kpi">
            <div class="dc-kpi-label">{{ $metric === 'posts' ? 'Total artícles' : 'Total visites' }}</div>
            <div class="dc-kpi-value">{{ number_format($chart['total'], 0, ',', '.') }}</div>
        </div>
        <div class="dc-kpi">
            <div class="dc-kpi-label">Millor mes</div>
            <div class="dc-kpi-value">{{ $chart['best'] }}</div>
        </div>
        <div class="dc-kpi">
            <div class="dc-kpi-label">Mitjana / mes</div>
            <div class="dc-kpi-value">{{ number_format($chart['avg'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div wire:ignore class="dc-chart-wrap">
        <canvas id="dashboardChart" role="img"
            aria-label="Gràfic de barres d'artícles o visites per mes segons els filtres seleccionats."></canvas>
    </div>
</div>

@script
<script>
    const ctx = document.getElementById('dashboardChart');
    let chart;
    const colors = { posts: '#4b7bec', visits: '#23a26d' };

    function draw(d) {
        const color = colors[d.metric] || '#4b7bec';
        if (chart) {
            chart.data.labels = d.labels;
            chart.data.datasets[0].data = d.values;
            chart.data.datasets[0].backgroundColor = color;
            chart.update();
            return;
        }
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{ data: d.values, backgroundColor: color, borderRadius: 6, maxBarThickness: 24 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { displayColors: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#8b90a0', font: { size: 11 } } },
                    y: { beginAtZero: true, ticks: { precision: 0, color: '#8b90a0', font: { size: 11 } }, grid: { color: '#eceef3' } }
                }
            }
        });
    }

    function ready(cb) { window.Chart ? cb() : setTimeout(() => ready(cb), 50); }
    ready(() => {
        draw(@js($chart));
        $wire.on('chartRefresh', (e) => draw(e.chart));
    });
</script>
@endscript
