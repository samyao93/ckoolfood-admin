<div class="product-card card cursor-pointer" onclick="quickView('{{$product->id}}')">
    <div class="card-header inline_product clickable p-0 initial-34">
        <img class="w-100 rounded" src="{{asset('storage/app/public/product')}}/{{$product['image']}}" onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'">
    </div>

    <div class="card-body inline_product text-center px-2 py-2 clickable">
        <div class="product-title1 position-relative text-dark font-weight-bold text-capitalize">
            {{ Str::limit($product['name'], 12,'...') }}
        </div>
        <div class="justify-content-between text-center">
            <div class="product-price text-center">
                <div class="justify-content-between text-center">
                    <div class="product-price text-center">
                        <span class="text-accent font-weight-bold color-f8923b">
                            {{--@if($product->discount > 0)
                                <strike class="fz-12px text-grey">
                                    {{\App\CentralLogics\Helpers::format_currency($product['price'])}}
                                </strike><br>
                            @endif--}}
                            {{\App\CentralLogics\Helpers::format_currency($product['price']-\App\CentralLogics\Helpers::product_discount_calculate($product, $product['price'], $restaurant_data))}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
