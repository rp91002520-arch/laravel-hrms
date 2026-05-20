<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div style="text-align:right; margin-bottom:15px;">
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>

<body>

<div id="message" style="background:#d4edda;color:#155724;padding:8px;display:none;"></div>

<form method="GET" action="/products">
    <input type="text" name="search" placeholder="Search product by name" value="{{ request('search') }}">
    <button type="submit">Search</button>
</form>

<h2>Add Product</h2>

<form id="productForm" enctype="multipart/form-data">
    <input type="hidden" name="id" id="product_id">

    <input type="text" id="name" name="name" placeholder="Name"><br>
    <span id="name_error" style="color:red"></span><br>

    <input type="text" id="price" name="price" placeholder="Price"><br>
    <span id="price_error" style="color:red"></span><br>

    <input type="text" id="stock" name="stock" placeholder="Stock"><br>
    <span id="stock_error" style="color:red"></span><br>

    <textarea id="description" name="description" placeholder="Description"></textarea><br>
    <span id="description_error" style="color:red"></span><br>

    <input type="file" name="image" id="image"><br>
    <span id="image_error" style="color:red"></span><br>

    <img id="imagePreview" src="" width="100" style="display:none;"><br>

    <button type="submit" id="saveBtn">Save</button>
</form>

<hr>

@if(auth()->user()->role == 'admin')
    <a href="/admin/deleted-products">Deleted Products</a>
@endif

<br><br>

<table border="1" id="productTable">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Description</th>
    <th>Image</th>
    <th>Action</th>
    @if(auth()->user()->role == 'admin')
        <th>Product Created By</th>
    @endif</tr>

@foreach($products as $product)
<tr id="row{{$product->id}}">
    <td>{{$product->id}}</td>
    <td class="name">{{$product->name}}</td>
    <td class="price">{{$product->price}}</td>
    <td class="stock">{{$product->stock}}</td>
    <td class="description">{{$product->description}}</td>

    <td class="image" data-image="{{$product->image}}">
        <img src="{{ asset('storage/'.$product->image) }}" width="50">
    </td>

    <td>
        @if(auth()->user()->role == 'admin' || auth()->id() == $product->user_id)
            <button class="editBtn" data-id="{{$product->id}}">Edit</button>
            <button class="deleteBtn" data-id="{{$product->id}}">Delete</button>
        @endif
    </td>

    @if(auth()->user()->role == 'admin')
<td class="user">
    {{ $product->user->name ?? 'N/A' }} (ID: {{ $product->user_id }})
</td>
@endif
</tr>
@endforeach
</table>

{{ $products->links() }}

<script>
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// CREATE + UPDATE
$("#productForm").submit(function(e){
    e.preventDefault();

    $("#saveBtn").prop("disabled", true).text("Saving...");

    $("#name_error, #price_error, #stock_error, #description_error, #image_error").text('');

    let id = $("#product_id").val();
    let url = "/products";
    let formData = new FormData(this);

    if(id != ""){
        url = "/products/" + id;
        formData.append('_method','PUT');
    }

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function(res){
            let product = res.data.data ? res.data.data : res.data;

            $("#message").text(id == "" ? "Product created successfully!" : "Product updated successfully!").show();
            setTimeout(()=> $("#message").fadeOut(),3000);

            // CREATE
            if(id == ""){
                $("#productTable").append(
                    "<tr id='row"+product.id+"'>"+
                    "<td>"+product.id+"</td>"+
                    "<td class='name'>"+product.name+"</td>"+
                    "<td class='price'>"+product.price+"</td>"+
                    "<td class='stock'>"+product.stock+"</td>"+
                    "<td class='description'>"+product.description+"</td>"+
                    "<td class='image' data-image='"+product.image+"'>"+
                        "<img src='/storage/"+product.image+"' width='50'>"+
                    "</td>"+
                    "<td>"+
                        "<button class='editBtn' data-id='"+product.id+"'>Edit</button>"+
                        "<button class='deleteBtn' data-id='"+product.id+"'>Delete</button>"+
                    "</td>"+
                    "<td class='user'>"+
                        (product.user ? product.user.name : 'N/A')+
                        " (ID: "+product.user_id+")"+
                    "</td>"+
                    "</tr>"
                );
            }
            // UPDATE
            else{
                let row = $("#row"+product.id);

                row.find(".name").text(product.name);
                row.find(".price").text(product.price);
                row.find(".stock").text(product.stock);
                row.find(".description").text(product.description);

                row.find(".user").text(
                    (product.user ? product.user.name : 'N/A') +
                    " (ID: " + product.user_id + ")"
                );

                if(product.image){
                    row.find(".image img").attr("src","/storage/"+product.image);
                    row.find(".image").attr("data-image",product.image);
                }
            }

            $("#productForm")[0].reset();
            $("#product_id").val('');
            $("#imagePreview").hide();
            $("h2").text("Add Product");
        },

        error: function(xhr){
            if(xhr.status == 422){
                let errors = xhr.responseJSON.errors;
                if(errors.name) $("#name_error").text(errors.name[0]);
                if(errors.price) $("#price_error").text(errors.price[0]);
                if(errors.stock) $("#stock_error").text(errors.stock[0]);
                if(errors.description) $("#description_error").text(errors.description[0]);
                if(errors.image) $("#image_error").text(errors.image[0]);
            }
        },

        complete: function(){
            $("#saveBtn").prop("disabled", false).text("Save");
        }
    });
});

// EDIT
$(document).on("click",".editBtn",function(){
    let row = $(this).closest("tr");

    $("#product_id").val($(this).data("id"));
    $("#name").val(row.find(".name").text());
    $("#price").val(row.find(".price").text());
    $("#stock").val(row.find(".stock").text());
    $("#description").val(row.find(".description").text());

    let image = row.find(".image").data("image");
    if(image){
        $("#imagePreview").attr("src","/storage/"+image).show();
    }

    $("h2").text("Edit Product");
});

// DELETE
$(document).on("click",".deleteBtn",function(){
    if(!confirm("Delete this product?")) return;

    let id = $(this).data("id");

    $.ajax({
        url: '/products/' + id,
        type: 'DELETE',
        success: function(){
            $("#row"+id).remove();
        }
    });
});

// LOGOUT
$('#logoutForm').submit(function(e){
    e.preventDefault();

    $.post($(this).attr('action'), $(this).serialize(), function(){
        window.location.href = '/login';
    });
});
</script>

</body>
</html>