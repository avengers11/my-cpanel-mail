@extends('admin.partials.master')

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
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr class="solid-header">
                                @if ($user->role == "admin")
                                    <th >User</th>
                                @endif
                                <th>Email</th>
                                <th>Forward Mail</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emails as $email)
                                <tr>
                                    @if ($user->role == "admin")
                                        <td>
                                            <small class="text-black font-weight-medium d-block">{{ $email->user->name }}</small>
                                            <span class="text-gray">
                                                <span class="status-indicator rounded-indicator small bg-primary"></span>
                                                {{ $email->user->email }} 
                                            </span> 
                                        </td>
                                    @endif
    
                                    <td>
                                        <small>{{ $email->email }}</small>
                                    </td>
                                    <td>{{ $email->forward_email }}</td>
                                    <td>{{ $email->created_at }}</td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route("admin.email.delete", $email) }}" class="btn btn-outline-danger btn-rounded" onclick="return confirm('Are you sure?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination mt-3">
                    {{ $emails->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection