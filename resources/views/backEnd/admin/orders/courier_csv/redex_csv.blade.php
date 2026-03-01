<table>
    <thead>
    <tr>
        <th>Invoice</th>
        <th>Customer Name</th>
        <th>Contact No.</th>
        <th>Customer Address</th>
        <th>District</th>
        <th>Area</th>
        <th>Area ID</th>
        <th>Division</th>
        <th>Price</th>
        <th>Product Selling Price</th>
        <th>Weight(g)</th>
        <th>Instruction</th>
        <th>Seller Name</th>
        <th>Seller Phone</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item->invoice_id }}</td>
            <td>{{ $item->customer_name }}</td>
            <td>{{ $item->customer_phone }}</td>
            <td>{{ $item->customer_address }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $item->total }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
