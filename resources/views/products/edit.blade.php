<x-admin-layout>
    <h1 class="h2">Edit Product</h1>
    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}">
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}">
            @error('price')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <img src="{{ asset('storage/images/' . $product->photo) }}" alt="" width="200">
            <input type="file" class="form-control" id="photo" name="photo" value="{{ old('photo', $product->photo) }}">
            @error('photo')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
            @foreach($categories as $category)
            <input type="checkbox" class="btn-check" id="cate-{{ $category->id }}" autocomplete="off" name="categories[]" value="{{ $category->id }}" {{ (in_array($category->id, $product->categories->pluck('id')->toArray() )) ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="cate-{{ $category->id }}">{{ $category->name }}</label>
            @endforeach
            @error('categories')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</x-admin-layout>