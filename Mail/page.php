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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        .row{
            cursor: pointer;
            width: 100%;
            height: 30px;
            border: 5px solid red;


        }
    </style>
    <script>
        $(document).ready(function(){
        
            toastr.options = { positionClass: 'toast-top-center' }
            var clientId = '395338576181-2t0a1d79oskchco17acbjgddvsc590tc.apps.googleusercontent.com';
            var clientSecret = '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';
            var redirectUri = 'http://localhost/code/page.php'
            var accessToken
            $(document).on('click', '#create',function(){
                createEmail(accessToken)                
            })
            $(document).off('click', 'tr[data-msq-id]')

            $(document).on('click','tr[data-msg-id]' ,function(){
                var messageId = $(this).data('msg-id');
                getMessage(accessToken, messageId)
            })
            $(document).off('click', '.delete-button')
            $(document).on('click', '.delete-button', function(event){
                event.stopPropagation()
                var messageId = $(this).data('msg-id')
                deleteEmail(accessToken, messageId)
                
            })

            getAuthorizationCode()
            function getAuthorizationCode() {
                var urlParams = new URLSearchParams(window.location.search);
                var authCode = urlParams.get('code');
                if (authCode) {
                    

                    getAccessToken(authCode)

                }
            }
            function getAccessToken(authCode){    
                $.ajax({
                    url: 'https://accounts.google.com/o/oauth2/token',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    dataType: 'json',
                    data: {
                        grant_type: 'authorization_code',
                        code: authCode,
                        client_id: clientId,
                        client_secret: clientSecret,
                        redirect_uri: redirectUri
                    },
                    success: function(response){
                        var access_token = response.access_token
                        getResource(access_token)
                        accessToken = access_token
                    },
                    error: function(xhr, status, error){
                        toastr.success('Error Get Access Token' + error, '', { timeOut: 3000 })
                    }
                })
            }



            function getResource(access_token){
                $.ajax({
                    url: 'https://www.googleapis.com/gmail/v1/users/me/messages',
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + access_token
                    },
                    success:function(response){
                        var str = ""
                        var table = '<h1>Mail</h1><br>'
                        table += '<table><tr><th>ID</th><th>Title</th><th></th></tr>'

                        $.each(response.messages, function(index, message) {
                            table += '<tr class="row" data-msg-id="' + message.id + '">'
                            table += '<td>' + message.id + '</td>'
                            table += '<td class="email-subject"></td>'
                            
                            table += '<td>' + '<button class="delete-button" data-msg-id="' + message.id + '"><i class="fa-regular fa-trash-can" style="color: #00040a;"></i></button>' + '</td>'
                            table += '</tr>'
                            getTitle(access_token, message.id)
                           
                        });

                        table += '</table>'

                        $('#load_data').html(table)
                    },
                    error: function(xhr, status, error){
                        toastr.success('Lỗi get mail' + error, '', { timeOut: 3000 })
                    }
                })
            }
            

            function getMessage(access_token, messageId){
                $.ajax({
                    url: 'https://www.googleapis.com/gmail/v1/users/me/messages/' + messageId,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + access_token
                    },
                    success: function(response){
                        alert(response.snippet)
                    },
                    error: function(xhr, status, error){
                        toastr.success('Lỗi email' + error, '', { timeOut: 3000 })
                    }
                    
                    
                })
            }
            
            
            function deleteEmail(access_token, messageId){
                $.ajax({
                    url: 'https://www.googleapis.com/gmail/v1/users/me/messages/' + messageId,
                    method: 'DELETE',
                    headers: {
                        Authorization: 'Bearer ' + access_token
                    },
                    success: function(response) {
                        console.log('Email đã bị xóa thành công:', response)
                    },
                    error: function(xhr, status, error) {
                        alert('Lỗi khi xóa email: ' + error);
                    },
                    complete: function(xhr, status){
                        toastr.success('Xoá email thành công', '', { timeOut: 3000 })
                        getResource(accessToken)

                    }
                })
            }

            function createEmail(access_token) {
                var url = 'https://www.googleapis.com/gmail/v1/users/me/messages/send';

                var email = {
                    to: 'viethoaduc.11@gmail.com',
                    subject: 'Test Email',
                    body: 'Hello, this is a test email!'
                }

                
                var rawEmail = btoa(
                    'From: YOUR_EMAIL\n' +
                    'To: ' + email.to + '\n' +
                    'Subject: ' + email.subject + '\n\n' +
                    email.body
                );
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        Authorization: 'Bearer ' + access_token,
                        'Content-Type': 'application/json'
                    },
                    dataType: 'json',
                    data: JSON.stringify({
                        raw: rawEmail
                    }),
                    success: function(response) {
                        console.log('Email đã được tạo và gửi thành công:', response)
                    },
                    error: function(xhr, status, error) {
                        toastr.success('Lỗi khi gửi email' + error, '', { timeOut: 3000 })
                    },
                    complete: function(xhr, status){
                        toastr.success('Email đã được gửi thành công', '', { timeOut: 3000 })
                        getResource(accessToken)
                    }
                })
            }
            function getTitle(access_token, messageId) {
                $.ajax({
                    url: 'https://www.googleapis.com/gmail/v1/users/me/messages/' + messageId,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + access_token
                    },
                    success: function(email) {
                        var subject = getEmailSubject(email);
                        $('tr[data-msg-id="' + messageId + '"] td.email-subject').text(subject);
                    },
                    error: function(xhr, status, error) {
                        alert('Error get mail: ' + error);
                    }
                });

                function getEmailSubject(email) {
                    var headers = email.payload.headers;
                    for (var i = 0; i < headers.length; i++) {
                        if (headers[i].name === 'Subject') {
                            return headers[i].value;
                        }
                    }
                    return '';
                }
            }

        })
        
    </script>
</head>

<body>
   
    <button id="create">Create Email</button>
    <p id="this"></p>
    <p id="ok"></p>
    
    <div id="load_data"></div>
</body>

</html>
