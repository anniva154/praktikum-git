@extends('frontend.user.layouts.master')

@section('main-content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Transaction History</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($transactions && $transactions->count() > 0)
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Gross Amount</th>
                        <th>Payment Type</th>
                        <th>Courier</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{$counter}}</td>
                        <td>{{$transaction->order_id}}</td>
                        <td>{{$transaction->customer_name}}</td>
                        <td>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                        <td>{{$transaction->payment_type}}</td>
                        <td>{{$transaction->courier}}</td>
                        <td>{{$transaction->transaction_status}}</td>
                        <td>{{$transaction->transaction_time}}</td>
                        <td>
                            <a href="{{route('user.transaction.show', $transaction->id)}}" class="btn btn-warning btn-sm" title="View">View</a>
                            <form method="POST" action="{{route('user.transaction.delete', $transaction->id)}}" style="display:inline-block;">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm" title="Delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 10px; text-align: center;">
    {{ $transactions->links('pagination::simple-default') }}
</div>

            @else
            <h6 class="text-center">No transactions found!</h6>
            @endif
        </div>
    </div>
</div>
@endsection
<style>
   .pagination {
    display: flex;
    justify-content: space-between; /* Align the buttons to both ends */
    align-items: center;
    margin: 20px 0;
    padding-bottom: 10px;
}

.pagination li {
    list-style: none;
    margin: 0 5px; /* Less margin for tighter spacing */
    border: 1px solid #ccc;
    border-radius: 5px;
    white-space: nowrap; /* Prevents text from wrapping */
}

.pagination li a {
    text-decoration: none;
    color: #007bff;
    padding: 6px 12px; /* Adjust padding to fit text neatly */
    display: block;
    text-align: center;
}

.pagination .disabled a {
    color: #ccc;
}

.pagination .active a {
    background-color: #007bff;
    color: white;
}

.pagination .previous a,
.pagination .next a {
    padding: 6px 12px;
}

.pagination .previous a:hover, 
.pagination .next a:hover {
    background-color: #f1f1f1;
    color: #0056b3;
    text-decoration: underline;
}

.pagination .previous,
.pagination .next {
    display: inline-block;
}

</style>

