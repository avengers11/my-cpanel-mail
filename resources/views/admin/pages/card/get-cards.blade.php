@extends('admin.partials.master')

@section('master')


<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Extract Cards</p>
        <form action="" method="post">
            @csrf 

            <div class="grid-body">
                <div class="item-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control custom-input" value="{{ session()->get("bank_name") }}" placeholder="Bankname..." />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Cards, Total: {{ session()->has("total_cards") ? session()->get("total_cards") : 0 }}</label>
                                <textarea cols="30" rows="10" class="form-control custom-input" name="cards">{{ session()->get("cards") }}</textarea>
                            </div>
                        </div>
    
                        @if (session()->has("filteredCards"))
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="inputEmail1">Extraction cards, Total: {{ count(session()->get("filteredCards")) }}</label>
                                    <textarea cols="30" rows="10" class="form-control custom-input" name="cards">
@foreach (session()->get("filteredCards") as $item)
{{ "'".$item }}
@endforeach
                                    </textarea>
                                </div>
                            </div>
                        @endif
                    </div>
    
                    <button class="btn btn-sm btn-success" id="removeid"><i class="mdi mdi mdi-autorenew mr-2"></i> <span>Submit</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
@endpush