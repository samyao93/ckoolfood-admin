<div class="product-card card cursor-pointer" onclick="quickView('{{$product->id}}')" >
    <div class="card-header inline_product clickable p-0 initial-34">
        <div class="d-flex align-items-center justify-content-center d-block">
            <img class="initial-35" src="{{asset('storage/app/public/product')}}/{{$product['image']}}" 
                onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'"
                >
        </div>
    </div>

    <div class="card-body inline_product text-center py-2 px-2 clickable">
        <div class="position-relative product-title1 pt-1 text-dark font-weight-bold line--limit-1">
            {{ Str::limit($product['name'], 30) }}
        </div>
        <div class="justify-content-between text-center">
            <div class="text-center text-yellow">
                @if($product->discount > 0)
                    <strike class="fz-12px text-grey">
                        {{\App\CentralLogics\Helpers::format_currency($product['price'])}}
                    </strike>
                @endif
                <span>
                    {{\App\CentralLogics\Helpers::format_currency($product['price']-\App\CentralLogics\Helpers::product_discount_calculate($product, $product['price'], $restaurant_data))}}
                </span>
            </div>
        </div>
    </div>
</div>
