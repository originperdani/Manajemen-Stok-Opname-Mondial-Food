<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Test</title>
</head>
<body>
    <h1>Midtrans Test Payment</h1>
    
    <button id="pay-button">Bayar!</button>

    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){ console.log('success'); },
                onPending: function(result){ console.log('pending'); },
                onError: function(result){ console.log('error'); },
                onClose: function(){ console.log('closed'); }
            });
        };
    </script>
</body>
</html>
