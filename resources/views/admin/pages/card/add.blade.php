@extends('admin.partials.master')

@section('master')
<?php
    $names = ["John Doe", "Alice Smith", "Robert Brown", "Emily Johnson", "Michael Lee", "Sarah Davis"];
    $randomName = $names[array_rand($names)];
?>

<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Card Generator</p>
        <form action="{{ route("admin.card.add") }}" method="POST">
            @csrf 

            <div class="grid-body">
                <div class="item-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Amazon ID</label>
                                <select name="amazon_id" class="form-control">
                                    <option value="all">All Amazon</option>
                                    @foreach ($data as $index=>$item)
                                        <option value="{{ $index }}">Profile {{ $item+1 }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Name</label>
                                <input type="text" name="name" class="form-control custom-input" value="{{ $randomName }}" placeholder="Card name..." />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Cards</label>
                                <textarea cols="30" rows="10" class="form-control custom-input" name="cards"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputEmail1">Card Month</label>
                                <input type="text" name="month" class="form-control custom-input" placeholder="Card month..." />
                            </div>
                        </div>
    
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputEmail1">Card Year</label>
                                <input type="text" name="year" class="form-control custom-input" placeholder="Card year..." />
                            </div>
                        </div>
                    </div>
    
                    <button type="submit" class="btn btn-sm btn-success" id="card_generate"><i class="mdi mdi mdi-autorenew mr-2"></i> Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
@endpush