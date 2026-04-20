<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between mb-4">
        <h3>📂 Category List</h3>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
    </div>

    <div class="card p-4 mb-4 shadow-sm">
        <form action="{{ route('categories.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-9">
                <input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">Add Category</button>
            </div>
        </form>
    </div>

    <table class="table bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->category_name }}</td>
                <td class="text-center">
                    <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline">
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