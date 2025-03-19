@extends('admin.partials.master')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.css" />
@endpush

@section('master')
    <div class="row">

        <div class="col-12 mb-4">
            <div class="grid-body bg-light">
                <h2 class="grid-title">Add New Review</h2>
                <div class="item-wrapper">
                    <div class="demo-wrapper">
                        <a class="btn btn-success btn-sm has-icon" href="{{ route("admin.review.addReview") }}">
                            <i class="mdi mdi-account-plus-outline"></i>Add
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="grid">
                <div class="grid-body py-3">
                    <p class="card-title ml-n1">Review History</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="all-review">
                        <thead>
                            <tr class="solid-header">
                                <th style="width: 0%;"></th>
                                <th class="text-start">Name</th>
                                <th style="width: 15%;" class="text-center">Image</th>
                                <th style="width: 10%;">Available IDs</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $index=>$item)
                                <form action="" method="post">
                                    <tr>
                                        <td>
                                            {{ $index+1 }}
                                        </td>
                                        <td class="text-start"><a href="{{ $item->review->book_url }}" target="_BLANK">{{ $item->review->book_name }}</a></td>
                                        <td class="text-center"><img style="height: 60px" src="{{ Storage::url($item->review->book_image) }}" alt=""></td>
                                        <td class="text-center">0</td>
                                        <td>
                                            <div class="actions">
                                                <a href="" class="btn btn-outline-primary btn-rounded">Submit</a>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    

@endsection



@push('js')
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(()=>{
        // tabel
        $('#all-review').DataTable({
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