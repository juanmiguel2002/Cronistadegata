<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;
use Livewire\Component;

class DashboardChart extends Component
{
    public string $metric = 'posts';   // posts | visits
    public string $period = '6';       // 6 | 12 | ytd
    public string $category = '';      // '' = totes | id categoria

    /**
     * Quan canvia qualsevol filtre, torna a calcular les dades i
     * les envia al gràfic (Chart.js) via esdeveniment de navegador.
     */
    public function updated(): void
    {
        $this->dispatch('chartRefresh', chart: $this->buildData());
    }

    /**
     * Rang de mesos a mostrar segons el període seleccionat.
     *
     * @return array{0: \Illuminate\Support\Carbon, 1: \Illuminate\Support\Collection}
     */
    private function range(): array
    {
        $now = now()->startOfMonth();

        if ($this->period === 'ytd') {
            $start = now()->startOfYear()->startOfMonth();
        } else {
            $span = max(1, (int) $this->period);
            $start = $now->copy()->subMonths($span - 1);
        }

        $months = collect();
        $cursor = $start->copy();
        while ($cursor <= $now) {
            $months->push($cursor->copy());
            $cursor->addMonth();
        }

        return [$start, $months];
    }

    /**
     * Calcula etiquetes, valors i KPIs. S'agrupa en PHP per ser
     * agnòstic del motor de base de dades.
     */
    private function buildData(): array
    {
        [$start, $months] = $this->range();

        if ($this->metric === 'visits') {
            $query = PostView::where('created_at', '>=', $start);
            if ($this->category !== '') {
                $query->whereHas('post', fn ($p) => $p->where('category', $this->category));
            }
            $rows = $query->get(['created_at']);
        } else {
            $query = Post::where('created_at', '>=', $start);
            if ($this->category !== '') {
                $query->where('category', $this->category);
            }
            $rows = $query->get(['created_at']);
        }

        $byMonth = $rows
            ->groupBy(fn ($row) => $row->created_at->format('Y-m'))
            ->map->count();

        $labels = [];
        $values = [];
        foreach ($months as $month) {
            $labels[] = ucfirst($month->translatedFormat('M'));
            $values[] = (int) ($byMonth[$month->format('Y-m')] ?? 0);
        }

        $total = array_sum($values);
        $max = $values ? max($values) : 0;
        $best = $max > 0 ? $labels[array_search($max, $values)] : '—';
        $avg = count($values) ? (int) round($total / count($values)) : 0;

        return [
            'labels' => $labels,
            'values' => $values,
            'total'  => $total,
            'best'   => $best . ($max > 0 ? ' · ' . $max : ''),
            'avg'    => $avg,
            'metric' => $this->metric,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard-chart', [
            'chart' => $this->buildData(),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
