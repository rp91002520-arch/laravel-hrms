<h2>Your Cart</h2>

@if(session('cart'))
    @php $total = 0; @endphp

    @foreach(session('cart') as $item)
        <div>
            <h3>{{ $item['name'] }}</h3>
            <p>Price: ₹{{ $item['price'] }}</p>
            <p>Quantity: {{ $item['quantity'] }}</p>

            @php $total += $item['price'] * $item['quantity']; @endphp
        </div>
    @endforeach

    <h3>Total: ₹{{ $total }}</h3>
@else
    <p>Cart is empty</p>
@endif

<br>
<a href="/">Back to Products</a>