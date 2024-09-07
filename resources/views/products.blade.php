@extends('layout')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($products as $product)
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">
                                {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                            </p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            <button class="btn btn-warning mt-auto text-center add-to-cart" data-id="{{ $product->id }}">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Add to Cart button click handler
            $('.add-to-cart').click(function(e) {
                e.preventDefault();

                let productId = $(this).data('id');

                $.ajax({
                    url: '{{ route('add.to.cart', '') }}/' +
                    productId, // Constructing the URL dynamically
                    method: 'GET',
                    success: function(response) {
                        alert(response.message); // Alert the success message
                        // Optionally, update the cart UI dynamically here
                    },
                    error: function(xhr) {
                        alert('Error adding product to cart');
                    }
                });
            });
        });
    </script>
@endsection
