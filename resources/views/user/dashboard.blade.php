<div style="display:flex; justify-content:space-between; align-items:center;">

<h2>
@if(auth()->user()->role == 'admin')
Admin Dashboard<br/>
Hello {{auth()->user()->name}}
@else
User Dashboard<br/>
Hello {{auth()->user()->name}}
@endif
</h2>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit">Logout</button>
</form>

</div>

@if(auth()->user()->role == 'admin')

<a href="/admin/products">Manage Products</a>

@else

<a href="/products">View Products</a>

@endif