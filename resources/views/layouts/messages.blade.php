<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

<style>
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;

        padding: 12px 18px;
        border-radius: 8px;
        font-weight: 500;

        min-width: 250px;
        z-index: 9999;

        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .alert-success {
        background: linear-gradient(135deg,rgba(118, 240, 122, 0.9), rgba(88, 180, 233, 0.9));
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg,rgba(228, 13, 88, 0.9), rgba(255, 107, 107, 0.9))!important;
        color: #ffffff;
    }

     .alert-warning {
        background: linear-gradient(135deg,rgba(238, 170, 98, 0.9), rgba(255, 250, 107, 0.9));
        color: #5a5444;
    }

    .alert-info {
        background: linear-gradient(135deg,rgba(248, 102, 97, 0.9), rgba(128, 238, 128, 0.9));
        color: #0c5460;
    } 
    </style>

</head>
<body>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <script>
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>