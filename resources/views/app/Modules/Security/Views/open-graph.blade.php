<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Social Ghost OpenGraph Engine -->
    <title>{{ $isSocialBot ? 'File Shared | HOA Cloud' : '404 Not Found' }}</title>
    
    @if($isSocialBot)
    <meta property="og:title" content="Secure File Share - HOA Cloud" />
    <meta property="og:description" content="A file has been shared with you via HOA Cloud's encrypted network. Click to view or download securely." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('images/secure-share-preview.png') }}" />
    <meta property="og:site_name" content="HOA Cloud" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Secure File Share" />
    <meta name="twitter:description" content="View this encrypted content securely on HOA Cloud." />
    @endif

    <style>
        body { background: #0a0a0a; color: #333; font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .ghost-msg { text-align: center; }
        .ghost-msg h1 { font-size: 14px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.5; }
    </style>
</head>
<body>
    <div class="ghost-msg">
        <h1>{{ $isSocialBot ? 'Encrypted Gateway Active' : '404 - Node Not Found' }}</h1>
    </div>
</body>
</html>
