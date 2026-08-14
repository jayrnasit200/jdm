<!--<table>-->
<!--    <thead>-->
<!--        <tr>-->
<!--            <th>Product</th>-->
<!--            <th>Price</th>-->
<!--            <th>Quantity</th>-->
<!--            <th>Total</th>-->
<!--        </tr>-->
<!--    </thead>-->
<!--    <tbody>-->
<!--        @foreach($orderProducts as $item)-->
<!--        <tr>-->
<!--            <td>{{ $item->product->name }}</td>-->
<!--            <td>{{ $item->selling_price }}</td>-->
<!--            <td>{{ $item->quantity }}</td>-->
<!--            <td>{{ $item->selling_price * $item->quantity }}</td>-->
<!--        </tr>-->
<!--        @endforeach-->
<!--    </tbody>-->
<!--</table>-->
@php
    $groupedProducts = $orderProducts->groupBy(function ($item) {
        return explode(' ', $item->product->name)[0];
    });
@endphp

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach($groupedProducts as $group => $products)

            <tr style="background:#f2f2f2;font-weight:bold;">
                <td colspan="4">{{ strtoupper($group) }}</td>
            </tr>

            @foreach($products as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->selling_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->selling_price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach

        @endforeach
    </tbody>
</table>