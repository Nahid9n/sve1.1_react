<div class="card modern-card hover-scale flex-fill">
    <div class="card-header">
        <h5 class="card-title">Top Selling Products</h5>
    </div>
    <div class="card-body p-0">
        <!-- FIXED HEIGHT + SCROLL -->
        <div class="table-responsive" style="max-height: 531px; overflow-y: auto;">
            <table class="table table-striped table-bordered zero-configuration">
                <thead class="sticky-top bg-white">
                    <tr>
                        <th>Product Name</th>
                        {{-- <th>Product Code</th> --}}
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_selling_products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            {{-- <td>{{ $product->sku }}</td> --}}
                            <td>{{ $product->total_sold }} Sold</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END -->
    </div>
</div>
