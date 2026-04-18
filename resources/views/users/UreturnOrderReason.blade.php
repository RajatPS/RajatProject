<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Order {{ $order->product->product_name }}</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Your signature floating circles */
        .floating-elements {
            position: fixed; width: 100%; height: 100%; top: 0; left: 0; pointer-events: none; z-index: 0;
        }
        .floating-circle {
            position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.05); animation: float 20s infinite ease-in-out;
        }
        .floating-circle:nth-child(1) { width: 300px; height: 300px; top: 10%; left: 10%; }
        .floating-circle:nth-child(2) { width: 200px; height: 200px; top: 60%; right: 15%; animation-delay: 5s; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        /* Glass Container */
        .return-container { 
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.18);
            position: relative;
            color: white;
            z-index: 1;
        }

        /* Top Accent Line */
        .return-container::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, #fd79a8, #fdcb6e, #6c5ce7);
            border-radius: 24px 24px 0 0;
        }

        h2 { font-weight: 800; letter-spacing: -0.5px; margin-bottom: 10px; }
        
        .expiry-info {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid rgba(231, 76, 60, 0.3);
            padding: 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #ff9f89;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        label {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Glass Input Styles */
        .glass-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 12px;
            width: 100%;
            outline: none;
            transition: all 0.3s;
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #a29bfe;
            box-shadow: 0 0 15px rgba(162, 155, 254, 0.3);
        }

        option { background: #6c5ce7; color: white; }

        /* Button Actions */
        .btn-glass-custom {
            padding: 14px 20px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            cursor: pointer;
        }

        .btn-confirm {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            margin-top: 20px;
        }
        .btn-confirm:hover {
            background: rgba(46, 204, 113, 0.4);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link:hover { color: white; }

    </style>
</head>
<body>

    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="return-container">
        <h2><i class="fas fa-undo-alt me-2" style="color: #fd79a8;"></i> Return Order</h2>
        <p class="text-white-50">Requesting return for <strong>#ORD-{{ $order->id }}</strong></p>

        <div class="expiry-info">
            <i class="fas fa-clock"></i>
            @php
                $delivered = \Carbon\Carbon::parse($order->delivered_at);
                $expiry = $delivered->addHours(24);
            @endphp
            <span>Window expires: {{ $expiry->format('g:i A') }} ({{ $expiry->diffForHumans() }})</span>
        </div>

        <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">

        {{-- <form action="/orders/{{ $order->id }}/return" method="POST"> --}}
        <form action="/orders/returnReason" method="POST">
            @csrf

            <div class="mb-4">
                <label for="reason">Why are you returning this?</label>
                <select name="reason" id="reason" class="glass-input" required>
                    <option value="" disabled selected>Select a reason</option>
                    <option value="defective">Defective / Not working</option>
                    <option value="wrong_size">Wrong size/color</option>
                    <option value="not_needed">No longer needed</option>
                    <option value="damaged">Damaged during shipping</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="comment">Additional details (Optional)</label>
                <textarea name="comment" id="comment" rows="4" class="glass-input" placeholder="Give us a bit more info..."></textarea>
            </div>

            <button type="submit" class="btn-glass-custom btn-confirm">
                <i class="fas fa-check-circle"></i>
                Submit Return Request
            </button>

            <a href="/orders" class="back-link">
                <i class="fas fa-chevron-left me-1"></i> Back to History
            </a>
            <input type="hidden" name="order_id" value="{{ $order->id }}">
        </form>
    </div>

</body>
</html>