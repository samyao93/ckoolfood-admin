<div class="product-card cursor-pointer card" onclick="quickView('{{ $product->id }}')">
    <div class="card-header inline_product clickable p-0 initial-50">
        <div class="d-flex align-items-center justify-content-center d-block">
            <img class="w-100 rounded" src="{{ asset('storage/app/public/product') }}/{{ $product['image'] }}"
                onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'">
        </div>
    </div>

    <div class="card-body inline_product text-center py-2 px-2 clickable">
        <div class="position-relative product-title1 text-dark font-weight-bold text-capitalize">
            {{ Str::limit($product['name'], 12, '...') }}
        </div>
        <div class="justify-content-between text-center">
            <div class="product-price text-center">
                <span class="text-accent font-weight-bold text-yellow">
                    {{ \App\CentralLogics\Helpers::format_currency($product['price'] - \App\CentralLogics\Helpers::product_discount_calculate($product, $product['price'], $restaurant_data)) }}
                </span>
            </div>
        </div>
    </div>
</div>
