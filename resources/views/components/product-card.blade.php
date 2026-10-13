<div class="row row-cols-1 row-cols-md-4 g-4">
    @foreach($products as $product)
    <div class="col">
        <div class="card h-100">
            <img src="{{ asset('storage/images/' . $product->photo) }}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">{{ $product->price }}</p>
                <a href="{{ route('products.user.show', $product->id) }}">View more</a>
                <p>
                    @foreach($product->categories as $category)
                    <span class="badge text-bg-warning">{{ $category->name }}</span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>