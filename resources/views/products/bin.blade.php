<x-admin-layout>
    <h1 class="h2">Products Bin</h1>
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
        Back
    </a>
    <form action="{{ route('products.bulkaction') }}" method="post">
        @csrf
        <button class="btn btn-outline-danger" type="submit" name="action" value="delete">Delete Selected</button>
        <button class="btn btn-outline-success" type="submit" name="action" value="restore">Restore Selected</button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" onchange="changeAll(this)"></th>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $product->id }}" class="product-checkbox"></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <img src="{{ asset('storage/images/' . $product->photo) }}" alt="" width="80">
                    </td>
                    <td>
                        <button type="submit" formaction="{{ route('products.restore', $product->id) }}" class="btn btn-outline-success" name="_method" value="patch">Restore</button>
                        <button type="submit" formaction="{{ route('products.forcedelete', $product->id) }}" class="btn btn-outline-danger" name="_method" value="delete">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    <script>
        function changeAll(cb) {
            const productChecbox = document.querySelectorAll('.product-checkbox');
            if(cb.checked) {
                productChecbox.forEach(e => {
                    e.checked = true;
                });
            }
            else {
                productChecbox.forEach(e => {
                    e.checked = false;
                });
            }
        }
    </script>
</x-admin-layout>