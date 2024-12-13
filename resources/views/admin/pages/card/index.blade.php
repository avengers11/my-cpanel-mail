@extends('admin.partials.master')

@section('master')
<div class="col-12 equel-grid">
    <div class="grid">
        <p class="grid-header">Card Generator</p>
        <div class="grid-body">
            <div class="item-wrapper">
                <div class="form-group">
                    <label for="inputEmail1">Card Number</label>
                    <input type="text" name="number" class="form-control custom-input" placeholder="Card number..." id="card_number" />
                </div>
                <div class="form-group">
                    <label for="inputEmail1">Generated Cards</label>
                    <textarea id="generated_card" cols="30" rows="10" class="form-control custom-input"></textarea>
                    <p id="card_length">Total cards: <span>0</span></p>
                </div>
                
                <button type="submit" class="btn btn-sm btn-success" id="card_generate"><i class="mdi mdi mdi-autorenew mr-2"></i> Generate</button>
                <button type="submit" class="btn btn-sm btn-primary" onclick="copyToClipboard()"><i class="mdi mdi-content-copy mr-2"></i> Copy</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    {{-- <script src="{{ asset("build/assets/app-e2ba4acb.js") }}"></script> --}}
    @vite('resources/js/app.js')
    <script>
        // Copy to clipboard function
        function copyToClipboard() {
            const outputArea = document.getElementById('generated_card');
            outputArea.select(); // Select the content in the textarea
            document.execCommand('copy'); // Copy the selected content
            alert('BINs copied to clipboard!'); // Confirmation message
        }
    </script>
@endpush