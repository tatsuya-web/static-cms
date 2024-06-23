@foreach($items as $key => $item)
    <tr @if($item->hasItems() || (!empty($group_key) && !empty($group_name))) class="-has_item" @endif>
        <td>
            @if(isset($group_key))
                {{ $group_key + 1 }}.{{ $key + 1 }}
            @else
                {{ $key + 1 }}
            @endif
        </td>
        <td>{{ $item->getLabel() }}</td>
        <td>
            @if(isset($group_name))
                {{ $group_name }}.{{ $item->getName() }}
            @else
                {{ $item->getName() }}
            @endif
        </td>
        <td>{{ $item->getType() }}</td>
        <td>{{ $item->isRequired() ? '○' : '' }}</td>
        <td>
            @if($item->hasOptions())
                @foreach($item->getOptions() as $option)
                <span>{{ $option->label }}:{{ $option->value }} </span>
                @endforeach
            @endif
        </td>
        <td>
            @if($item->hasAccept())
            {{ $item->getAcceptString() }}
            @endif
            @if($item->hasMin())
            最小値:{{ $item->getMin() }}
            @endif
            @if($item->hasMax())
            最大値:{{ $item->getMax() }}
            @endif
        </td>
        <td>{{ $item->isIndex() ? '○' : '' }}</td>
    </tr>
    @if($item->hasItems())
        @include('app.template.page._items', ['items' => $item->getItems(), 'group_key' => $key, 'group_name' => $item->getName()])
    @endif
@endforeach