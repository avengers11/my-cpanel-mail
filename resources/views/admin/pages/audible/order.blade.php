@extends('admin.partials.master')

@section('master')

@push('css')
    <style>
        div#output p {
            border: 1px solid #dddd;
            border-radius: 7px;
            padding: 10px;
            font-size: 12px;
            font-family: auto;
            margin-bottom: 10px;
        }
    </style>
@endpush

<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Address Cart Add</p>
        <form action="{{ route("admin.audible.orderSave") }}" method="post">
            @csrf 

            <div class="grid-body">
                <div class="item-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <label for="" class="mb-5 ">Total account: <span id="total-email">{{ count($accountEmails) }}</span></label>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Amazon Emails</label>
                                <textarea name="emails" class="form-control" style="height: 200px" id="accountEmails" required>@foreach ($accountEmails as $item){{ $item."\n" }}@endforeach</textarea>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Amazon Passwords</label>
                                <textarea name="passwords" class="form-control" style="height: 200px" id="accountPasswords" required>@foreach ($accountPasswords as $item){{ $item."\n" }}@endforeach</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Amazon Names</label>
                                <textarea name="names" class="form-control" style="height: 100px" id="accountNames" required>@foreach ($accountNames as $item){{ $item."\n" }}@endforeach</textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Amazon Address</label>
                                <textarea name="address" class="form-control" style="height: 100px" required>
305 W 7th Ave | Eugene | OR | 97401 | (541) 343-3477
1150 S Colony Way | Palmer | AK | 99645 | (907) 333-8000
1261 S Seward Meridian Pkwy | Wasilla | AK | 99654 | (907) 376-6468
101 E 28th St | Chanute | KS | 66720 | (620) 431-6006
1261 S Seward Meridian Pkwy | Wasilla | AK | 99654 | (907) 376-6468
1650 John F Kennedy Rd | Dubuque | IA | 52002 |	(563) 690-0213
4 World Financial Ctr | New York | NY | 10080 |	(646) 555-4099


                                </textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">AudioBook Books</label>
                                <input type="text" name="audible_book" class="form-control custom-input" value="The rocket and the reformer" placeholder="keyword..." required/>
                            </div>
                        </div>

                        <div class="col-12 d-none">
                            <div class="form-group">
                                <label for="inputEmail1">Amazon add to cart</label>
                                <textarea style="height: 100px" class="form-control custom-input" name="keyword" required>
MacBook
Samsung Galaxy
PlayStation 5
Xbox Series X
Beats Headphones
Kindle Paperwhite
Oculus Quest
Dyson Vacuum
Instant Pot
KitchenAid Mixer
Stanley Cup (Trending tumbler)
YETI Cooler
Fitbit
GoPro Camera
Echo Dot
Ring Doorbell
Fire TV Stick
Roku Streaming Stick
                                </textarea>
                            </div>
                        </div>

                        <div class="col-12 d-none">
                            <div class="form-group">
                                <label for="inputEmail1">Audible add to cart</label>
                                <textarea style="height: 100px" class="form-control custom-input" name="audible_keywords" required>
Top Audible books
Bestselling audiobooks
Popular Audible books
New releases on Audible
Must-listen audiobooks
Award-winning audiobooks
Trending audiobooks on Audible
Science fiction audiobooks
Mystery thriller audiobooks
Romance audiobooks trending
Business audiobooks top-rated
Biography audiobooks Audible
History audiobooks best
Stephen King audiobooks
Free audiobooks on Audible
Audible free trial best books
Discounted Audible books
Best books with Audible credits
James Clear Atomic Habits audiobook
Cheap audiobooks on Audible
                                </textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="inputEmail1">Cards</label>
                                <textarea style="height: 100px" class="form-control custom-input" name="cards" id="accountCards" required>@foreach ($accountCards as $item){{ $item."\n" }}@endforeach</textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputEmail1">Card Month</label>
                                <input type="text" name="month" class="form-control custom-input" value="" placeholder="Card month..." required/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="inputEmail1">Card Year</label>
                                <input type="text" name="year" class="form-control custom-input" value="" placeholder="Card year..." required/>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-sm btn-primary" type="submit" id="submit_order"><i class="mdi mdi mdi-autorenew mr-2"></i> <span>Save Account</span></button>
                    <button class="btn btn-sm btn-success" type="button" id="order_now"><i class="mdi mdi-check mr-2"></i> <span>Start Order</span></button>
                    <a href="{{ route("admin.audible.orderClear") }}" class="btn btn-sm btn-danger text-light"><span class="mdi mdi-close mr-2"></span> <span>Clear Disable Account</span></a>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div id="output">
                                @foreach ($disables as $item)
                                    <p style="color: red">{{ $item->email }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
let eventSource;

// get latest 
const getLatestData = () => {
    $("#order_now span").text("Next...");
    $.ajax({
        url: "/admin/audible",
        headers: {
            "X-Csrf-Token": '{{ csrf_token() }}'
        },
        method: "POST",
        success:function(response){
            let totalData = response.emails.length;
            if(totalData > 0){
                runEvent();
                $("#accountEmails").val(response.emails.map(item => item).join("\n"));
                $("#accountPasswords").val(response.passwords.map(item => item).join("\n"));
                $("#accountNames").val(response.names.map(item => item).join("\n"));
                $("#accountCards").val(response.cards.map(item => item).join("\n"));
                
                // total-email
                $("#total-email").text(response.emails.length);
            }
        }
    });
}

// runEvent
const runEvent = () => {
    $("#order_now span").text("Running...");

    eventSource = new EventSource(`/admin/audible/process`);
    eventSource.onmessage = function(event) {
        if(event.data == "[COMPLETE]"){
            $("#output").prepend(`<p style="border:1px solid red">✅ ALL DONE</p>`);
        }else if(event.data == "[DONE]"){
            getLatestData();
            $("#output").prepend(`<p style="border:1px solid green">${event.data}</p>`);
        }else{
            $("#output").prepend(`<p>${event.data}</p>`);
        }
    };
    eventSource.onerror = function() {
        console.log("Turn Off");
        eventSource.close();
        $("#order_now span").text("Start Order");
    };
}

// order now 
$("#order_now").click(function(){
    // click & run 
    if($("#order_now span").text() == "Start Order"){
        $("#order_now span").text("Running...");
        runEvent();
    }else{
        $("#order_now span").text("Start Order");
        if (eventSource) {
            eventSource.close();
            console.log("EventSource connection closed.");
        }
    }
});
</script>
@endpush