@foreach($categories as $key=>$category)
<tr>
    <td class="text-center">{{$key+1}}</td>
    <td class="text-center">{{$category->id}}</td>
    <td>
        <span class="d-block font-size-sm text-body">
            {{Str::limit($category->parent['name'],20,'...')}}
        </span>
    </td>
    <td>
        <span class="d-block font-size-sm text-body">
            {{Str::limit($category->name,20,'...')}}
        </span>
    </td>
</tr>
@endforeach
