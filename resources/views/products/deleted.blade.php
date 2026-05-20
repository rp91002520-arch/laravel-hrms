<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit">Logout</button>
</form>
<button onclick="history.back()">Back</button>
<h2>Deleted Products</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
<th>Description</th>
<th>Image</th>

<th>Action</th>
</tr>

@foreach($products as $product)

<tr>

<td>{{$product->id}}</td>
<td>{{$product->name}}</td>
<td>{{$product->price}}</td>
<td>{{$product->stock}}</td>
<td>{{$product->description}}</td>
<td><img src="{{ asset('storage/'.$product->image) }}" width="50"></td>




<td>

<a href="/restore/{{$product->id}}">Restore</a>

<a href="/force-delete/{{$product->id}}">Delete Permanently</a>

</td>

</tr>

@endforeach

</table>
