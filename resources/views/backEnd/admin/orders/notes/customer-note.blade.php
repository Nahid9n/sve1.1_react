<div class="card-header">
    <h5 class="modal-title">Customer Note</h5>
    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
<div class="card-body">
    <form action="{{ route('admin.orders.customer.note.update') }}" method="post">
        @csrf
        <input type="hidden" name="order_id" value="{{ $orderNote->order_id }}">
        <input type="hidden" name="note_id" value="{{ $orderNote->id }}">
        <input type="hidden" name="user_id" value="{{ $orderNote->user_id }}">
        <input type="hidden" name="type" value="customer">

        <div class="form-group mb-2">
            <textarea name="note" class="form-control" rows="3">{{ $orderNote->note }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
    </form>
</div>
