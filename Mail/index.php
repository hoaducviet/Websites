<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mail</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#button').click(function(){
                
                var clientId = '395338576181-2t0a1d79oskchco17acbjgddvsc590tc.apps.googleusercontent.com';
                var clientSecret = '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';
                var redirectUri = 'http://localhost/code/page.php'
                
                var authUrl = 'https://accounts.google.com/o/oauth2/auth';
                authUrl += '?redirect_uri=' + encodeURIComponent(redirectUri);
                authUrl += '&response_type=code';
                authUrl += '&client_id=' + encodeURIComponent(clientId);
                authUrl += '&scope=' + encodeURIComponent('https://mail.google.com');
                authUrl += '&approval_prompt=force';
                window.location.href = authUrl;
                
            })
        })
    </script>
</head>

<body>
    
    <button id="button">ClickHere</button>

</body>

</html>
