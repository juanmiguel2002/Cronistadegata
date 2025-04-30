<div>
    <article class="card">
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form id="filter-form" class="filter-form" action="{{ route('posts.filter') }}" method="GET">
            <div class="form-group">
                <label for="year">Seleccione un any:</label>
                <select id="year" name="year" class="form-control">
                    <option value="">Seleccione un any</option>
                    @for ($i = date('Y'); $i >= 2010; $i--)
                        <option value="{{ $i }}" {{ old('year', request('year')) == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="month">Seleccione un mes:</label>
                <select id="month" name="month" class="form-control">
                    <option value="">Seleccione un mes</option>
                    @php
                        $monthsValencian = [
                            1 => 'gener',
                            2 => 'febrer',
                            3 => 'març',
                            4 => 'abril',
                            5 => 'maig',
                            6 => 'juny',
                            7 => 'juliol',
                            8 => 'agost',
                            9 => 'setembre',
                            10 => 'octubre',
                            11 => 'novembre',
                            12 => 'desembre',
                        ];
                    @endphp

                    @foreach ($monthsValencian as $number => $name)
                        <option value="{{ $number }}" {{ old('month', request('month')) == $number ? 'selected' : '' }}>
                            {{ ucfirst($name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
        {{-- <h2>Quí soc?</h2>
        <div class="about">
            <img src="front/img/portada.jpg" alt="logo" class="portada" />
            <cite>Estimant el nostre poble i a la nostra gent</cite>
        </div> --}}
    </article>
</div>
