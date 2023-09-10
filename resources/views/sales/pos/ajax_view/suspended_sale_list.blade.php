<div class="suspends_area">
    <div class="row">
        @foreach ($sales as $sale)
            <div class="col-md-3 mt-1">
                <div class="card bg-primary text-white pt-1">
                    <div class="card-title text-center">
                        <h6>{{ $sale->invoice_id }}</h6>
                        <h6>{{ date('d/m/Y', strtotime($sale->date)) }}</h6>
                        <h6><i class="fas fa-user"></i> {{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}</h6>
                    </div>
                    <div class="card-body text-center">
                        <h6><i class="fas fa-cubes"></i> Items : {{ $sale->total_item }}</h6>
                        <h6><i class="far fa-money-bill-alt"></i> Total : {{ $sale->total_payable_amount }}</h6>

                        <div class="row mt-1">
                            <div class="col-md-3 offset-3">
                                <a href="{{ route('sales.pos.edit', $sale->id) }}" class="a btn btn-sm btn-primary" tabindex="-1">Edit</a>
                            </div>

                            <div class="col-md-3">
                                <a id="delete" href="{{ route('sales.delete', $sale->id) }}" class="a btn btn-sm btn-danger" tabindex="-1">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
