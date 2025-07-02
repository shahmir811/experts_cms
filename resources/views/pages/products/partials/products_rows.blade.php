@foreach ($products as $product)
<tr>
    <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
    <td>
        <img src="{{ asset($product->image) }}" alt="Product Image" width="100">
    </td>
    <td>{{ $product->store->name }}</td>
    <td>{{ $product->title }}</td>
    <td>
        <a href="https://amazon.com/dp/{{ $product->asin }}" target="_blank">{{ $product->asin }}</a>
    </td>
    <td>{{ $product->amazon_current_price }}</td>
    <td>{{ $product->amazon_stock }}</td>
    <td>
        <button class="btn btn-info btn-sm view-detail" data-product-id="{{ $product->id }}">
            <i class="bi bi-eye"></i> Detail
        </button>
    </td>
</tr>
@endforeach