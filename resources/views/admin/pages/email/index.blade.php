@extends('admin.partials.master')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css" />
@endpush

@section('master')
    <div class="row">

        <div class="col-12 mb-4">
            <div class="grid-body bg-light">
                <h2 class="grid-title">Email account</h2>
                <div class="item-wrapper">
                    <div class="demo-wrapper">
                        <a class="btn btn-success btn-sm has-icon" href="{{ route("admin.email.add") }}">
                            <i class="mdi mdi-account-plus-outline"></i>Add
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="grid">
                <div class="grid-body py-3">
                    <p class="card-title ml-n1">Order History</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="email-table">
                        <thead>
                            <tr class="solid-header">
                                <th style="width: 0%; text:start"></th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Forward Mail</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emails as $email)
                                @php
                                    $createdAt = $email['created_at'];

                                    // Calculate the time differences
                                    $now = \Carbon\Carbon::now();
                                    $diffInMinutes = $now->diffInMinutes($createdAt);
                                    $diffInHours = $now->diffInHours($createdAt);
                                    $diffInDays = $now->diffInDays($createdAt);
                                    $diffInYears = $now->diffInYears($createdAt);

                                    // Determine the display logic
                                    if ($diffInMinutes < 60) {
                                        $display = "{$diffInMinutes}m ago";
                                    } elseif ($diffInHours < 24) {
                                        $display = "{$diffInHours}h " . ($diffInMinutes % 60) . "m ago";
                                    } elseif ($diffInDays < 30) {
                                        $display = "{$diffInDays}d " . ($diffInHours % 24) . "h ago";
                                    } else {
                                        $display = "{$diffInDays}d {$diffInYears}y ago";
                                    }
                                @endphp
                                <tr>
                                    <td><span class="d-none">{{ $email['mtime'] }}</span></td>
                                    <td class="email">{{ $email['email'] }}</td>
                                    <td>{{ $email['password'] }}</td>
                                    <td>
                                        <button data-toggle="modal" data-target="#addNewForwardEmail" class="btn btn-outline-success btn-rounded add-new-forward-email">Add New</button>
                                    </td>
                                    <td>{{ $display }}</td>
                                    <td>
                                        <div class="actions">
                                            {{-- <a target="_BLANK" href="{{ route("admin.email.generate", ["email" => $email['email']]) }}" class="btn btn-outline-primary btn-rounded">New Password</a>

                                            @if ($email['password'] != null)
                                                <a target="_BLANK" href="https://mail.masudrana.top?email={{ $email['email'] }}&password={{ $email['password'] }}" class="btn btn-outline-success btn-rounded">Inbox</a>
                                            @else
                                                <a target="_BLANK" href="{{ route("admin.email.generate", ["email" => $email['email']]) }}" class="btn btn-outline-success btn-rounded">Generate</a>
                                            @endif --}}

                                            {{-- new  --}}
                                            <a target="_BLANK" href="{{ route("admin.email.generate", ["email" => $email['email']]) }}" class="btn btn-outline-primary btn-rounded">New Password</a>

                                            @if ($email['password'] != null)
                                                <a target="_BLANK" href="{{ route('admin.email.fetchEmails', ["email" => $email['email'], "password" => $email['password']]) }}" class="btn btn-outline-success btn-rounded">Inbox</a>
                                            @else
                                                <a target="_BLANK" href="{{ route("admin.email.generate", ["email" => $email['email']]) }}" class="btn btn-outline-success btn-rounded">Generate</a>
                                            @endif

                                            <a href="{{ route("admin.email.delete", ["id" => $email['id'], "email" => $email['email']]) }}" class="btn btn-outline-danger btn-rounded" onclick="return confirm('Are you sure?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if (count($emails) < 1)
                                <tr>
                                    <td colspan="4">
                                        <p class="text-danger text-center">No data found!</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


<!-- Modal -->
<div class="modal fade" id="addNewForwardEmail" tabindex="-1" aria-labelledby="addNewForwardEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.email.addForwardSubmit') }}" method="post">
                @csrf 
                <input type="hidden" name="email" id="forward-mail">

                <div class="modal-header">
                    <h5 class="modal-title" id="addNewForwardEmailLabel">Add New Forward Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Forward email...</label>
                        <input type="email" name="email_forward" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('js')
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(()=>{
        // tabel
        $('#email-table').DataTable({
            "pageLength": 10,
            "order": [[0, "desc"]]
        });

        $(".add-new-forward-email").click(function(){
            let email = $(this).closest("tr").find(".email").text().trim();
            $("#forward-mail").val(email);
        });
    });
</script>
@endpush