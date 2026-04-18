<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PenCart | Help & Support</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #ff6b9d, #ff8a80);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
            color: white;
        }

        /* Your Signature Floating Bubbles */
        .bubble-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
        }
        .bubble {
            position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.05);
            filter: blur(60px); animation: float 15s infinite alternate;
        }
        .bubble-1 { width: 300px; height: 300px; top: -50px; left: -50px; background: rgba(255, 107, 157, 0.3); }
        .bubble-2 { width: 250px; height: 250px; bottom: 10%; right: 5%; background: rgba(118, 75, 162, 0.4); }
        
        @keyframes float {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 50px); }
        }

        /* Glass Container */
        .help-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        h2 { font-weight: 800; letter-spacing: -1px; margin-bottom: 5px; }
        
        .contact-number {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 25px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .contact-number i {
            font-size: 1.5rem;
            color: #ff6b9d;
        }

        label {
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
            opacity: 0.8;
        }

        /* Glass Input */
        .glass-textarea {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: white;
            padding: 15px;
            width: 100%;
            outline: none;
            transition: 0.3s;
        }

        .glass-textarea:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #ff6b9d;
        }

        /* The Submit Button */
        .btn-help-submit {
            background: var(--accent-gradient);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            width: 100%;
            margin-top: 20px;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(255, 107, 157, 0.4);
        }

        .btn-help-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 157, 0.6);
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .back-home a { color: rgba(255,255,255,0.6); text-decoration: none; transition: 0.3s; }
        .back-home a:hover { color: white; }
    </style>
</head>
<body>

    <div class="bubble-bg">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
    </div>

    <div class="help-container text-center">
        <h2>Help & Support</h2>
        <p class="opacity-75">We are here to help you.</p>

        <div class="contact-number">
            <i class="fas fa-headset"></i>
            <div class="text-start">
                <small class="d-block opacity-50">Call our Support</small>
                <strong>+91 98765 43210</strong>
            </div>
        </div>

        <hr style="border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0;">

        <form action="{{ url('/submit-help') }}" method="POST">
            @csrf
            <div class="text-start">
                <label for="question">Ask a Question</label>
                <textarea name="question" id="question" rows="5" class="glass-textarea" placeholder="How can we assist you today?" required></textarea>
            </div>

            <button type="submit" class="btn-help-submit">
                <i class="fas fa-paper-plane me-2"></i> SEND MESSAGE
            </button>
        </form>

        <div class="back-home">
            <a href="{{ url('/Uproducts') }}"><i class="fas fa-arrow-left me-1"></i> Back to Shop</a>
        </div>
    </div>

</body>
</html>