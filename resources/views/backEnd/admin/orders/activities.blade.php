<div class="card-header">
    <h5 class="modal-title">All Activities</h5>
    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
        aria-label="Close"></button>

</div>
<div class="card-body p-3">
    <div class="row">
        <div class="col-md-12">
            @if (!empty($order->get_activities))
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Activity</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->get_activities as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->text }}
                                        @if ($item->activity_type == 2)
                                            <a href="{{ route('admin.order.activity.view', $item->id) }}"
                                                class="btn btn-primary btn-sm">View
                                                More</a>
                                        @endif

                                    </td>
                                    <td>{{ $item->created_at }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @else
                <p>No Activities Found</p>
            @endif
        </div>
    </div>
</div>
