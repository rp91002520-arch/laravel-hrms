<h2>Products</h2>

@foreach($products as $id => $product)
    <div>
        <h3>{{ $product['name'] }}</h3>
        <p>₹{{ $product['price'] }}</p>

        <form action="{{ route('add.to.cart') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="1">
    <button type="submit">Test Add</button>
</form>
    </div>
@endforeach