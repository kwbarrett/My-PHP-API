<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
<?php
if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['id'] )){
    $user = json_decode( file_get_contents( "http://localhost/kens_api/users/{$_GET['id']}" ), true );
    echo "
    <form id='userForm' action='/kens_api/users/{$user['user_id']}' method='post'>
        <input type='hidden' id='user_id' name='user_id' value='{$user['user_id']}'>
        
        <div>
            <label for='username'>Name</label>
            <input type='text' name='username' id='username' value='{$user['username']}'>
        </div>
        <div>
            <label for='user_email'>Email</label>
            <input type='text' name='user_email' id='user_email' value='{$user['user_email']}'>
        </div>
        <div>
            <input type='hidden' name='user_status' value='1'>
            <button id='edit'>Update user</button>
        </div>
    </form>
    ";
}
?>
<script>
    let editButton = document.getElementById('edit');
    editButton.addEventListener("click", function(event){
        event.preventDefault();
        let userForm = document.getElementById( 'userForm' );
        let user_id = userForm.user_id.value;
        let username = userForm.username.value;
        let user_email = userForm.user_email.value;
        const user = {
            user_id: user_id,
            username: username,
            user_email: user_email,
        }
        const options = {
            method: 'PUT',
            body: JSON.stringify(user),
            redirect: 'follow',
            headers: {
                'Content-Type': 'application/json'
            }
        }

        // fetch('http://localhost/kens_api/users/' + user_id, options)
        //     .then(response => response.json())
        //     .then(data => console.log(data))

        fetch( 'http://localhost/kens_api/users/' + user_id, options )
            .then( function( response ){
                console.log( response );
                if( response.status == '200' ){
                    window.location.href="list.php"
                }
                return response.json();
            })
            .then( function( data ){
                console.log( data );
            })
    } );
</script>
</body>
</html>