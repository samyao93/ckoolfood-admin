<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Tag;
use App\Models\Food;
use App\Models\Review;
use App\Models\Translation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{

    public function store(Request $request)
    {
        if(!$request?->vendor?->restaurants[0]?->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ],403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'price' => 'required|numeric|min:0.01',
            'discount' => 'required|numeric|min:0',
            'veg' => 'required|boolean',
            'translations'=>'required',
            'image' => 'nullable|max:2048',

        ], [
            'category_id.required' => translate('messages.category_required'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        $data = json_decode($request?->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($request['price'] <= $dis || count($data) < 1 || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }


        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids,$tag->id);
            }
        }


        $food = new Food;
        $food->name = $data[0]['value'];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $food->category_id = $request?->sub_category_id ?? $request?->category_id;
        $food->category_ids = json_encode($category);
        $food->description = $data[1]['value'];

        // $choice_options = [];
        // if ($request->has('choice')) {
        //     foreach (json_decode($request->choice_no) as $key => $no) {
        //         $str = 'choice_options_' . $no;
        //         if ($request[$str][0] == null) {
        //             $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
        //             return response()->json(['errors' => Helpers::error_processor($validator)]);
        //         }
        //         $item['name'] = 'choice_' . $no;
        //         $item['title'] = json_decode($request->choice)[$key];
        //         $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', json_decode($request[$str]))));
        //         array_push($choice_options, $item);
        //     }
        // }
        $food->choice_options = json_encode([]);
        $variations = [];
        if(isset($request->options))
        {
            foreach(json_decode($request->options, true) as $option)
            {
                $temp_variation['name']= $option['name'];
                $temp_variation['type']= $option['type'];
                $temp_variation['min']= $option['min'] ?? 0;
                $temp_variation['max']= $option['max'] ?? 0;
                $temp_variation['required']= $option['required']??'off';
                $temp_value = [];
                foreach($option['values'] as $value)
                {
                    if(isset($value['label'])){
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    array_push($temp_value,$temp_option);
                }
                $temp_variation['values']= $temp_value;
                array_push($variations,$temp_variation);
            }
        }
        //combinations end
        $food->variations = json_encode($variations);
        $food->price = $request->price;
        $food->image = Helpers::upload(dir:'product/', format:'png', image: $request->file('image'));
        $food->available_time_starts = $request->available_time_starts;
        $food->available_time_ends = $request->available_time_ends;
        $food->discount = $request->discount ?? 0;
        $food->discount_type = $request->discount_type;
        $food->attributes = $request->has('attribute_id') ? $request->attribute_id : json_encode([]);
        $food->add_ons = $request->has('addon_ids') ? json_encode(explode(',',$request->addon_ids)) : json_encode([]);
        $food->restaurant_id = $request['vendor']->restaurants[0]->id;
        $food->veg = $request->veg;

        $restaurant=$request['vendor']->restaurants[0];
        if (  $restaurant->restaurant_model == 'subscription' ) {

            $rest_sub = $restaurant?->restaurant_sub;
            if (isset($rest_sub)) {
                if ($rest_sub?->max_product != "unlimited" && $rest_sub?->max_product > 0 ) {
                    $total_food= Food::where('restaurant_id', $restaurant->id)->count()+1;
                    if ( $total_food >= $rest_sub->max_product  ){
                        $restaurant->update(['food_section' => 0]);
                    }
                }
            } else{
                return response()->json([
                    'unsubscribed'=>[
                        ['code'=>'unsubscribed', 'message'=>translate('messages.you_are_not_subscribed_to_any_package')]
                    ]
                ]);
            }
        } elseif($restaurant->restaurant_model == 'unsubscribed'){
            return response()->json([
                'unsubscribed'=>[
                    ['code'=>'unsubscribed', 'message'=>translate('messages.you_are_not_subscribed_to_any_package')]
                ]
            ]);
        }

        $food->save();
        $food?->tags()?->sync($tag_ids);

        foreach ($data as $key=>$item) {
            $data[$key]['translationable_type'] = 'App\Models\Food';
            $data[$key]['translationable_id'] = $food->id;
        }
        Translation::insert($data);

        return response()->json(['message'=>translate('messages.product_added_successfully')], 200);
    }

    public function status(Request $request)
    {
        if(!$request?->vendor?->restaurants[0]?->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ],403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $product = Food::find($request->id);
        $product->status = $request->status;
        $product?->save();

        return response()->json(['message' => translate('messages.product_status_updated')], 200);
    }

    public function get_product($id)
    {

        // try {
            $item = Food::withoutGlobalScope('translate')->with('tags')->where('id',$id)
            ->first();
            $item = Helpers::product_data_formatting_translate($item, false, false, app()->getLocale());
            return response()->json($item, 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'errors' => ['code' => 'product-001', 'message' => translate('messages.not_found')]
        //     ], 404);
        // }
    }

    public function recommended(Request $request)
    {
        if(!$request?->vendor?->restaurants[0]?->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ],403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $product = Food::find($request->id);
        $product->recommended = $request->status;
        $product?->save();

        return response()->json(['message' => translate('messages.product_recommended_status_updated')], 200);

    }




    public function update(Request $request)
    {
        if(!$request?->vendor?->restaurants[0]?->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ],403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0.01',
            'discount' => 'required|numeric|min:0',
            'veg' => 'required|boolean',
            'image' => 'nullable|max:2048',

        ], [
            'category_id.required' => translate('messages.category_required'),
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }
        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($request['price'] <= $dis || count($data) < 1 || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }


        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(
                    ['tag' => $value]
                );
                $tag->save();
                array_push($tag_ids,$tag->id);
            }
        }

        $p = Food::findOrFail($request->id);

        $p->name = $data[0]['value'];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $p->category_id = $request?->sub_category_id ?? $request->category_id;
        $p->category_ids = json_encode($category);
        $p->description = $data[1]['value'];

        // $choice_options = [];
        // if ($request->has('choice')) {
        //     foreach (json_decode($request->choice_no) as $key => $no) {
        //         $str = 'choice_options_' . $no;
        //         if (json_decode($request[$str])[0] == null) {
        //             $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
        //             return response()->json(['errors' => Helpers::error_processor($validator)]);
        //         }
        //         $item['name'] = 'choice_' . $no;
        //         $item['title'] = json_decode($request->choice)[$key];
        //         $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', json_decode($request[$str]))));
        //         array_push($choice_options, $item);
        //     }
        // }
        $p->choice_options = json_encode([]);
        $variations = [];
        if(isset($request->options))
        {
            foreach(json_decode($request->options,true) as $key=>$option)
            {
                $temp_variation['name']= $option['name'];
                $temp_variation['type']= $option['type'];
                $temp_variation['min']= $option['min'] ?? 0;
                $temp_variation['max']= $option['max'] ?? 0;
                $temp_variation['required']= $option['required']??'off';
                $temp_value = [];
                foreach($option['values'] as $value)
                {
                    if(isset($value['label'])){
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    array_push($temp_value,$temp_option);
                }
                $temp_variation['values']= $temp_value;
                array_push($variations,$temp_variation);
            }
        }

        $slug = Str::slug($p->name);
        $p->slug = $p->slug? $p->slug :"{$slug}-{$p->id}";

        $p->variations = json_encode($variations);
        $p->price = $request->price;
        $p->image = $request->has('image') ? Helpers::update(dir:'product/', old_image:$p->image,  format:'png', image: $request->file('image')) : $p->image;
        $p->available_time_starts = $request->available_time_starts;
        $p->available_time_ends = $request->available_time_ends;
        $p->discount = $request->discount ?? 0;
        $p->discount_type = $request->discount_type;
        $p->attributes = $request->has('attribute_id') ? $request->attribute_id : json_encode([]);
        $p->add_ons = $request->has('addon_ids') ? json_encode(explode(',',$request->addon_ids)) : json_encode([]);
        $p->veg = $request->veg;
        $p?->save();
        $p?->tags()?->sync($tag_ids);

        foreach ($data as $key=>$item) {
            Translation::updateOrInsert(
                ['translationable_type' => 'App\Models\Food',
                    'translationable_id' => $p->id,
                    'locale' => $item['locale'],
                    'key' => $item['key']],
                ['value' => $item['value']]
            );
        }

        return response()->json(['message'=>translate('messages.product_updated_successfully')], 200);
    }

    public function delete(Request $request)
    {
        if(!$request?->vendor?->restaurants[0]?->food_section)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'unauthorized', 'message'=>translate('messages.permission_denied')]
                ]
            ],403);
        }
        $product = Food::findOrFail($request->id);

        if($product?->image)
        {
            if (Storage::disk('public')->exists('product/' . $product['image'])) {
                Storage::disk('public')->delete('product/' . $product['image']);
            }
        }
        $product?->translations()?->delete();
        $product?->delete();

        return response()->json(['message'=>translate('messages.product_deleted_successfully')], 200);
    }

    public function search(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $key = explode(' ', $request['name']);

        $products = Food::active()
        ->with(['rating'])
        ->where('restaurant_id', $request['vendor']?->restaurants[0]?->id)
        ->when($request->category_id, function($query)use($request){
            $query->whereHas('category',function($q)use($request){
                return $q->whereId($request->category_id)->orWhere('parent_id', $request->category_id);
            });
        })
        ->when($request->restaurant_id, function($query) use($request){
            return $query->where('restaurant_id', $request->restaurant_id);
        })
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
            $q->orWhereHas('tags',function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->where('tag', 'like', "%{$value}%");
                    };
                });
            });
        })
        ->limit(50)
        ->get();

        $data = Helpers::product_data_formatting(data:$products,multi_data: true,trans: false, local:app()->getLocale());
        return response()->json($data, 200);
    }

    public function reviews(Request $request)
    {
        $id = $request['vendor']?->restaurants[0]?->id;;

        $reviews = Review::with(['customer', 'food'])
        ->whereHas('food', function($query)use($id){
            return $query->where('restaurant_id', $id);
        })
        ->latest()->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['food_name'] = null;
            $item['food_image'] = null;
            $item['customer_name'] = null;
            if($item->food)
            {
                $item['food_name'] = $item?->food?->name;
                $item['food_image'] = $item?->food?->image;
                if(count($item?->food?->translations)>0)
                {
                    $translate = array_column($item?->food?->translations?->toArray(), 'value', 'key');
                    $item['food_name'] = $translate['name'];
                }
            }

            if($item->customer)
            {
                $item['customer_name'] = $item?->customer?->f_name.' '.$item?->customer?->l_name;
            }

            unset($item['food']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        return response()->json($storage, 200);
    }
}
