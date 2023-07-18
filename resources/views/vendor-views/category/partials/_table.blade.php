@foreach($categories as $key=>$category)
<tr>
    <td class="text-center">{{$key+1}}</td>
    <td class="text-center">{{$category->id}}</td>
    <td>
    <span class="d-block font-size-sm text-body text-center">
        {{$category['name']}}
    </span>
    </td>
</tr>
@endforeach
