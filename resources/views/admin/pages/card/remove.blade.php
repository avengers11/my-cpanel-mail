@extends('admin.partials.master')

@section('master')

@push('css')
    <style>
        #outputbox{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .card-wrapper{
            display: flex;
            align-items: center;
            justify-items: center;
            background: aquamarine;
            padding: 10px;
            border-radius: 15px;
            flex-direction: column
        }
        .card-wrapper .profile-id{
            font-size: 15px;
        }
        .card-wrapper .serial-no{
            font-size: 12px;
        }
        img.apx-wallet-details-payment-method-image {
            height: 50px;
            width: 80px;
        }
        .a-row.a-spacing-medium.a-spacing-top-medium.apx-wallet-payment-method-details-row {
            border: 2px solid black;
            border-radius: 15px;
            padding: 10px;
            width: 100%;
        }
        span.a-size-mini.apx-wallet-card-art-holder-name.apx-wallet-card-art-foreground-color.a-text-bold, span.a-size-mini.apx-wallet-card-art-tail.apx-wallet-card-art-foreground-color.a-text-bold {
            color: black !important;
        }
    </style>
@endpush

<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Get Cards</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="inputEmail1">Amazon ID</label>
                            <select name="amazon_id" id="amazon_id" class="form-control">
                                @foreach ($data as $index=>$item)
                                    <option value="{{ $index + 1 }}">Profile {{ $item+1 }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-sm btn-success" id="removeid"><i class="mdi mdi mdi-autorenew mr-2"></i> <span>Submit</span></button>
                <a onclick="return confirm('Are you sure?')" href="{{ route("admin.card.removeClear") }}" class="btn btn-sm btn-danger"><span class="mdi mdi-close mr-2"></span> <span>Clear</span></a>
                <a href="{{ route("admin.card.getCards") }}" class="btn btn-sm btn-primary"><span class="mdi mdi-check mr-2"></span> <span>GET Cards</span></a>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div id="outputbox">
        @foreach ($cards as $card)
            <div class="card-wrapper">
                {!! $card->details !!}
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('js')
<script>
    let eventSource;

    $("#removeid").click(function(){
        if($("#removeid span").text() == "Submit"){
            $("#removeid span").text("Running...");

            let amazon_id = $("#amazon_id").val();
            eventSource = new EventSource(`/admin/card/remove-card-dynamic?amazon_id=${amazon_id}`);
            eventSource.onmessage = function(event) {
                console.log(event);
                
                $("#outputbox").prepend(`
                <div class="card-wrapper">
                    ${event.data}
                </div>
                `);
            };
            eventSource.onerror = function() {
                console.log("Connection closed or error occurred.");
                eventSource.close();
            };
        }else{
            $("#removeid span").text("Submit");
            if (eventSource) {
                eventSource.close();
                console.log("EventSource connection closed.");
            }
        }
    });
</script>
@endpush