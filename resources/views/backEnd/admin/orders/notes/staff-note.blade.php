<div class="card-header">
    <h5 class="modal-title">Staff Note</h5>
    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
<div class="card-body p-3">
    <form action="{{ route('admin.orders.staff.note.update') }}" method="post">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="form-group mb-1">
            <label for="one">
                <input type="radio" class="form-check-input me-1" name="note_type" id="one"
                    value="Number Not Reachable" checked> Number Not Reachable
            </label>
        </div>
        <div class="form-group mb-1">
            <label for="two">
                <input type="radio" class="form-check-input me-1" name="note_type" id="two" value="Call Later">
                Call Later
            </label>
        </div>
        <div class="form-group mb-1">
            <label for="three">
                <input type="radio" class="form-check-input me-1" name="note_type" id="three" value="Call Back">
                Call Back
            </label>
        </div>
        <div class="form-group mb-2">
            <label for="others">
                <input type="radio" class="form-check-input me-1" name="note_type" id="others" value="1">
                Others
            </label>
        </div>

        <div class="form-group  d-none text-area mb-2">
            <textarea name="note" class="form-control note" rows=""></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $(document).on('change', 'input[type=radio]', function() {
            var note_type = $(this).val();
            if (note_type == 1) {
                $('.text-area').removeClass('d-none');
            } else {
                $('.text-area').addClass('d-none');
                $('.note').val(note_type);
            }
        });
    });
</script>
