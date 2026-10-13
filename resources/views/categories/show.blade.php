<x-layout>
    <x-slot:title>
        {{ $category->name }}
        </x-slot>

        <div class="container">
            <x-product-card :products="$category->products" />
        </div>
</x-layout>