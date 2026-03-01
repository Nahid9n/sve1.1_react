<div class="card-header">
    <h5 class="modal-title">All Notes</h5>
    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
        aria-label="Close"></button>
</div>
<div class="card-body p-3">
    <div class="row">
        <div class="col-md-12">
            @if (!empty($order->get_notes))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Note</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->get_notes as $key => $note)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $note->note . ' (' . ucfirst($note->user_type) . ')' }}</td>
                                    <td>{{ $note->created_at }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @else
                <p>No Notes Found</p>
            @endif

        </div>
    </div>
</div>
