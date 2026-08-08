@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')

@push('styles')
<style>
    /* ===== Dashboard moderno & minimalista ===== */
    .dash {
        --dash-bg: #f6f7fb;
        --dash-card: #ffffff;
        --dash-border: #eceef3;
        --dash-text: #1e2432;
        --dash-muted: #8b90a0;
        --dash-accent: #4b7bec;
        --dash-radius: 16px;
        --dash-shadow: 0 1px 2px rgba(16, 24, 40, .04), 0 8px 24px rgba(16, 24, 40, .04);
        font-family: 'Inter', sans-serif;
    }

    .dash-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }
    .dash-head h1 {
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--dash-text);
        margin: 0 0 4px;
    }
    .dash-head .dash-sub {
        font-size: 14px;
        color: var(--dash-muted);
        margin: 0;
    }
    .dash-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--dash-text);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        padding: 11px 18px;
        border-radius: 10px;
        text-decoration: none;
        transition: transform .12s ease, opacity .12s ease;
    }
    .dash-btn:hover { color: #fff; opacity: .9; transform: translateY(-1px); }

    /* ---- Stat cards ---- */
    .dash-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) { .dash-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px) { .dash-stats { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--dash-card);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        padding: 22px;
        box-shadow: var(--dash-shadow);
        transition: transform .14s ease, box-shadow .14s ease;
        display: block;
        text-decoration: none;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(16,24,40,.06), 0 16px 40px rgba(16,24,40,.08);
        text-decoration: none;
    }
    .stat-card .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
    }
    .stat-card .stat-value {
        font-size: 30px;
        font-weight: 700;
        line-height: 1;
        letter-spacing: -0.02em;
        color: var(--dash-text);
    }
    .stat-card .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--dash-muted);
        margin-top: 6px;
    }
    .ic-blue   { background: #eaf0fe; color: #4b7bec; }
    .ic-green  { background: #e7f7ef; color: #23a26d; }
    .ic-amber  { background: #fdf1e3; color: #e08a2b; }
    .ic-purple { background: #f0ebfd; color: #7c5ce0; }

    /* ---- Panels ---- */
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }
    @media (max-width: 992px) { .dash-grid { grid-template-columns: 1fr; } }

    .panel {
        background: var(--dash-card);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius);
        box-shadow: var(--dash-shadow);
        overflow: hidden;
    }
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid var(--dash-border);
    }
    .panel-head h2 {
        font-size: 15px;
        font-weight: 600;
        color: var(--dash-text);
        margin: 0;
    }
    .panel-head .panel-link {
        font-size: 13px;
        font-weight: 500;
        color: var(--dash-accent);
        text-decoration: none;
    }

    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table th {
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--dash-muted);
        padding: 12px 22px;
    }
    .dash-table td {
        padding: 13px 22px;
        font-size: 14px;
        color: var(--dash-text);
        border-top: 1px solid var(--dash-border);
        vertical-align: middle;
    }
    .dash-table tr:hover td { background: #fafbfd; }
    .dash-table a { color: var(--dash-text); font-weight: 500; text-decoration: none; }
    .dash-table a:hover { color: var(--dash-accent); }

    .tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 500;
        color: #5a6072;
        background: #f1f2f6;
        padding: 3px 10px;
        border-radius: 999px;
    }
    .visits {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        color: var(--dash-text);
    }
    .visits i { color: var(--dash-muted); font-size: 13px; }

    .dash-empty {
        padding: 32px 22px;
        text-align: center;
        color: var(--dash-muted);
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="dash">
        <div class="dash-head">
            <div>
                <h1>Dashboard</h1>
                <p class="dash-sub">Resum general del teu blog</p>
            </div>
            <a href="{{ route('admin.addPost') }}" class="dash-btn">
                <i class="bi bi-plus-lg"></i> Nou Article
            </a>
        </div>

        <div class="dash-stats">
            <a href="{{ route('admin.posts') }}" class="stat-card">
                <div class="stat-icon ic-blue"><i class="bi bi-file-earmark-text"></i></div>
                <div class="stat-value">{{ $postsCount }}</div>
                <div class="stat-label">Artícles</div>
            </a>
            <a href="{{ route('admin.categories') }}" class="stat-card">
                <div class="stat-icon ic-green"><i class="bi bi-tags"></i></div>
                <div class="stat-value">{{ $categoriesCount }}</div>
                <div class="stat-label">Categoríes</div>
            </a>
            <a href="{{ route('admin.categories') }}" class="stat-card">
                <div class="stat-icon ic-amber"><i class="bi bi-diagram-3"></i></div>
                <div class="stat-value">{{ $parentCategoriesCount }}</div>
                <div class="stat-label">Categoríes Pare</div>
            </a>
            <a href="{{ route('admin.carrusel') }}" class="stat-card">
                <div class="stat-icon ic-purple"><i class="bi bi-images"></i></div>
                <div class="stat-value">{{ $carouselCount }}</div>
                <div class="stat-label">Imatges Carrusel</div>
            </a>
        </div>

        @livewire('admin.dashboard-chart')

        <div class="dash-grid">
            <div class="panel">
                <div class="panel-head">
                    <h2>Últims Artícles</h2>
                    <a href="{{ route('admin.posts') }}" class="panel-link">Veure tots</a>
                </div>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Títol</th>
                            <th>Categoría</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentPosts as $post)
                            <tr>
                                <td><a href="{{ route('admin.editPost', ['id' => $post->id]) }}">{{ $post->title }}</a></td>
                                <td><span class="tag">{{ $post->post_category->name ?? 'Sense categoría' }}</span></td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="dash-empty">No hi ha artícles!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h2>Artícles més visitats</h2>
                    <a href="{{ route('admin.posts') }}" class="panel-link">Veure tots</a>
                </div>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Títol</th>
                            <th>Categoría</th>
                            <th>Visites</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mostVisitedPosts as $post)
                            <tr>
                                <td><a href="{{ route('admin.editPost', ['id' => $post->id]) }}">{{ $post->title }}</a></td>
                                <td><span class="tag">{{ $post->post_category->name ?? 'Sense categoría' }}</span></td>
                                <td><span class="visits"><i class="bi bi-eye"></i> {{ $post->visitas }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="dash-empty">No hi ha artícles!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
