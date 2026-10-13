<x-layout>
    <x-slot:title>
        {{ $product->name }}
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('storage/images/' . $product->photo) }}" class="img-fluid">
            </div>
            <div class="col-md-8">
                <h1>{{ $product->name }}</h1>
                <p><button class="btn btn-outline-danger" id="btn-like" data-product-id="{{ $product->id }}">&hearts;</button> <span class="like-count">{{ $product->likes }}</span> </p>
                <p>{{ $product->price }}</p>
                {!! $product->description !!}

                <input type="text" name="content" id="comment-content">
                <button type="submit" id="btn-comment" data-product-id="{{ $product->id }}">Send</button>

                <div class="comment-list">
                    @foreach($product->comments as $comment)
                        <div class="border rounded p-3 mb-3"><span class="fs-6 text-secondary">{{ $comment->created_at->diffForHumans() }}</span> - {{ $comment->content }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-slot:script>
        <script>
            const basePath = "{{ route('home') }}";
        </script>
        <script src="{{ asset('js/comment.js') }}"></script>
    </x-slot>
</x-layout>