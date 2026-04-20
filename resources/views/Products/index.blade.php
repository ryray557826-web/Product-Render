<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Product Inventory</h2>
        <a href="{{ route('categories.index') }}" class="btn btn-primary">Manage Categories</a>
    </div>

    <div class="card p-4 mb-4 shadow-sm">
        <form action="{{ route('products.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>  
            <div class="col-md-3">
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Add</button>
            </div>
        </form>
    </div>

    <table class="table bg-white shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td><span class="badge bg-info text-dark">{{ $product->category->category_name ?? 'N/A' }}</span></td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td class="text-center">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>