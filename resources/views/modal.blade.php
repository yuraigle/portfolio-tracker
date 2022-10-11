<div class="modal fade" id="add1" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактировать</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" title="Закрыть"></button>
            </div>
            <form method="post" enctype="application/x-www-form-urlencoded">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="portfolio_id" class="form-label">В портфеле</label>
                        <select id="portfolio_id" name="portfolio_id" class="form-select">
                            <option></option>
                            @foreach($portfolios as $p)
                                <option value="{{ $p->ID }}">{{ $p->NAME }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="asset_id" class="form-label">Тикер</label>
                        <select id="asset_id" name="asset_id" class="form-select">
                            <option></option>
                            @foreach($assets as $a)
                                <option value="{{ $a->ID }}">{{ $a->TICKER }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="asset_amount" class="form-label">Количество</label>
                        <input type="text" id="asset_amount" name="asset_amount" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
