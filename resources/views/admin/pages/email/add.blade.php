@extends('admin.partials.master')

@section('master')
<?php
function generateRandomEmail() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $textLength = rand(5, 10); // Random length for the text part
    $textPart = '';
    for ($i = 0; $i < $textLength; $i++) {
        $textPart .= $characters[rand(0, strlen($characters) - 1)];
    }
    $numberPart = rand(100, 999);
    $randomEmail = $textPart . $numberPart;
    return strtolower($randomEmail);
}
?>

<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Add New Email</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <form action="{{ route("admin.email.addSubmit") }}" method="POST">
                    @csrf 

                    <div class="form-group">
                        <label for="inputEmail1">Email</label>
                        <input type="text" id="email" name="email" class="form-control custom-input" placeholder="Enter email" value="{{ generateRandomEmail() }}" />
                    </div>
                    <div class="form-group">
                        <label for="inputPassword1">Password</label>
                        <input type="text" id="forward-email" name="forward_email" class="form-control custom-input" placeholder="Enter forward email" value="{{ auth()->user()->forward_email }}" />
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Confirmed</button>
                    <a type="submit" class="btn btn-sm btn-danger" href="{{ route("admin.email.index") }}">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection