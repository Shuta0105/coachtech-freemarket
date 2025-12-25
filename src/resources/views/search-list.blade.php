@foreach ($items as $item)
<a class="product-list__item" href="/item/{{ $item->id }}">
    <div class="product-list__item-inner">
        <div class="product-list__item-img">
            @if ($item->order_count > 0)
            <span class="product-list__item-sold">Sold</span>
            @endif
            <img src="{{ file_exists(public_path('img/' . $item->img)) ? asset('img/' . $item->img) : asset('storage/' . $item->img) }}">
        </div>
        <div class="product-list__item-name">{{ $item->name }}</div>
    </div>
</a>
@endforeach