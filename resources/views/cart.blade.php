@extends('layout')

@section('content')
    <table id="cart" class="table table-hover table-condensed">
        <thead>
            <tr>
                <th style="width:50%">Product</th>
                <th style="width:10%">Price</th>
                <th style="width:8%">Quantity</th>
                <th style="width:22%" class="text-center">Subtotal</th>
                <th style="width:10%"></th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0 @endphp
            @if (session('cart'))
                @foreach (session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <tr data-id="{{ $id }}">
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 hidden-xs">
                                    <img src="{{ $details['image'] }}" width="100" height="100"
                                        class="img-responsive" />
                                </div>
                                <div class="col-sm-9">
                                    <h4 class="nomargin">{{ $details['name'] }}</h4>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">${{ $details['price'] }}</td>
                        <td data-th="Quantity">
                            <input type="number" value="{{ $details['quantity'] }}"
                                class="form-control quantity update-cart" min="1" />
                        </td>
                        <td data-th="Subtotal" class="text-center subtotal">${{ $details['price'] * $details['quantity'] }}
                        </td>
                        <td class="actions" data-th="">
                            <button class="btn btn-danger btn-sm remove-from-cart"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">
                    <h3><strong>Total $<span id="total">{{ $total }}</span></strong></h3>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-right">
                    <a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue
                        Shopping</a>
                    <button class="btn btn-success">Checkout</button>
                </td>
            </tr>
        </tfoot>
    </table>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Update Cart Quantity
        $(".update-cart").change(function(e) {
            e.preventDefault();
            let ele = $(this);
            let quantity = ele.val();
            let id = ele.closest('tr').data('id');

            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: quantity
                },
                success: function(response) {
                    let price = parseFloat(ele.closest('tr').find('td[data-th="Price"]').text().replace(
                        '$', ''));
                    let subtotal = price * quantity;
                    ele.closest('tr').find('.subtotal').text('$' + subtotal.toFixed(2));
                    updateTotal();
                }
            });
        });

        // Remove From Cart
        $(".remove-from-cart").click(function(e) {
            e.preventDefault();
            let ele = $(this);

            if (confirm("Are you sure you want to remove this item?")) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: ele.closest('tr').data('id')
                    },
                    success: function(response) {
                        ele.closest('tr').remove();
                        updateTotal();
                    }
                });
            }
        });

        // Update Total Amount
        function updateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).text().replace('$', ''));
            });
            $('#total').text(total.toFixed(2));
        }
    </script>
@endsection
