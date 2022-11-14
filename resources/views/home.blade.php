@extends('_base')

@section('content')
    <div style="max-width: 650px; margin: 0 auto">
        @if($errors)
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" title="Закрыть"></button>
                <ul class="m-0">
                    @foreach($errors as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-sm table-bordered" style="width: 650px">
                <thead class="text-center">
                <tr class="table-dark">
                    <th>Актив</th>
                    <th>Тип</th>
                    <th style="cursor: help" title="По состоянию на {{ $lastUpd }}">Цена *</th>
                    @foreach ($portfolios as $p)
                        <th>{{ $p->NAME }}</th>
                    @endforeach
                    <th>Стоим.</th>
                </tr>
                </thead>
                <tbody>
                @foreach($assets as $a)
                    @if(empty($sumA[$a->ID])) @continue @endif
                    <tr>
                        <td class="asset_type_{{ $a->TYPE_ID }}">{{ $a->TICKER }}</td>
                        <td class="asset_type_{{ $a->TYPE_ID }}">{{ $a->TYPE }}</td>
                        <td class="text-end">{!! ( $a->CURRENCY == 'RUB' ? '&#x20bd;' : '$' ) .
                        number_format($a->PRICE, $a->PRICE > 1000 ? 0 : ($a->PRICE > 1 ? 2 : 4)) !!}</td>

                        @foreach ($portfolios as $p)
                            <td onclick="show_add_modal({{ $p->ID }}, {{ $a->ID }}, {{ $mine[$a->ID][$p->ID] ?? 0 }})"
                                class="editable"
                                @if(isset($sumC[$a->ID][$p->ID]))
                                    data-sumusd="${{ number_format($sumC[$a->ID][$p->ID]) }}"
                                    data-sumrub="&#x20bd;{{ number_format($sumC[$a->ID][$p->ID] * $usdRub) }}"
                                @endif
                            >
                                @if(isset($mine[$a->ID][$p->ID]) && $mine[$a->ID][$p->ID] > 0)
                                    @if($a->TYPE_ID == 3)
                                        {{ number_format($mine[$a->ID][$p->ID], $a->PRICE > 1000 ? 4 : ($a->PRICE > 1 ? 2 : 0)) }}
                                    @else
                                        {{ number_format($mine[$a->ID][$p->ID]) }}
                                    @endif
                                @endif
                            </td>
                        @endforeach

                        <td class="text-end">
                            <span class="sum_usd">${{ number_format($sumA[$a->ID]) }}</span>
                            <span class="sum_rub"
                                  style="display: none">&#x20bd;{{ number_format($sumA[$a->ID] * $usdRub) }}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="table-secondary">
                    <td colspan="3" class="fw-bold">
                        Итого
                        <a class="link-dark p-2" href="#" onclick="set_currency('USD'); return false">$</a>
                        |
                        <a class="link-dark p-2" href="#" onclick="set_currency('RUB'); return false">&#x20bd;</a>
                    </td>
                    @foreach ($portfolios as $p)
                        <td class="text-end">
                            <span class="sum_usd">${{ number_format($sumP[$p->ID]) }}</span>
                            <span class="sum_rub" style="display: none">
                        &#x20bd;{{ number_format($sumP[$p->ID] * $usdRub) }}
                    </span>
                        </td>
                    @endforeach
                    <td class="text-end fw-bold">
                        <span class="sum_usd">${{ number_format(array_sum($sumT)) }}</span>
                        <span class="sum_rub" style="display: none">
                    &#x20bd;{{ number_format(array_sum($sumT) * $usdRub) }}
                </span>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div style="width: 250px; max-width: 100%; margin: 0 auto">
            <canvas id="myChart1"></canvas>
        </div>

        <div class="mt-2">
            <img src="/logo1.webp" alt="logo" style="width: 100%">
        </div>
    </div>

    @include("modal")
@endsection

@section('inline_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        const myAddModal = new bootstrap.Modal('#add1');

        function set_currency(currency = 'USD') {
            if (currency === 'USD') {
                document.querySelectorAll('.sum_rub').forEach(e => e.style.display = 'none')
                document.querySelectorAll('.sum_usd').forEach(e => e.style.display = 'block')
                document.querySelectorAll('td.editable').forEach(e => e.title = e.dataset.sumusd)
            } else if (currency === 'RUB') {
                document.querySelectorAll('.sum_usd').forEach(e => e.style.display = 'none')
                document.querySelectorAll('.sum_rub').forEach(e => e.style.display = 'block')
                document.querySelectorAll('td.editable').forEach(e => e.title = e.dataset.sumrub)
            }
        }

        set_currency('USD');

        function show_add_modal(portfolio_id, asset_id, asset_amount = 0) {
            document.getElementById('portfolio_id').value = portfolio_id;
            document.getElementById('asset_id').value = asset_id;
            document.getElementById('asset_amount').value = asset_amount;
            myAddModal.show();
        }

        document.getElementById('add1')
            .addEventListener('shown.bs.modal', () => {
                document.getElementById('asset_amount').focus();
                document.getElementById('asset_amount').select();
            })

        const data1 = {!! json_encode(array_values($sumT)) !!};
        const ttl = data1.reduce((x, y) => x + y);

        const data = {
            labels: {!! json_encode(array_keys($sumT)) !!},
            datasets: [{
                data: data1,
                backgroundColor: ['#6FA8DC', '#6AA84F', '#C4B2E6', '#FFD966'],
                hoverOffset: 5
            }]
        };

        const myChart1 = new Chart(
            document.getElementById('myChart1'),
            {
                type: 'doughnut',
                data: data,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Распределение по классам активов'
                        },
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    return ctx.label + ': ' + Math.round(ctx.parsed / ttl * 1000) / 10 + '%';
                                }
                            }
                        },
                    }
                }
            }
        );
    </script>
@endsection
