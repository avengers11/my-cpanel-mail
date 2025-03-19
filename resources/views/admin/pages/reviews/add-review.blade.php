@extends('admin.partials.master')
@section('master')
<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Add New Email</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf 

                    <div class="form-group">
                        <label for="inputQuota1">Book Name</label>
                        <input type="text" name="book_name" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Book Image</label>
                        <input type="file" name="book_image" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Book URL</label>
                        <input type="text" name="book_url" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Per day</label>
                        <input type="text" name="frequency" value="2" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Total Review</label>
                        <input type="text" name="total_review" class="form-control custom-input" />
                    </div>
                    <div class="form-group">
                        <label for="inputQuota1">Type</label>
                        <select name="type" class="form-control">
                            <option value="Verified">Verified</option>
                            <option value="Unverified">Unverified</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Confirmed</button>
                    <a type="submit" class="btn btn-sm btn-danger" href="{{ route("admin.email.index") }}">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection