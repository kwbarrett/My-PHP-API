<?php
if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset( $_GET['id'] )){
    $user = json_decode( file_get_contents( "http://localhost/kens_api/users/{$_GET['id']}" ), true );
    echo "  <div>
                {$user['username']}<br/>
                <a href='mailto:{$user['user_email']}'>{$user['user_email']}</a>
            </div>
            <div><a href='http://localhost/kens_api/list.php'>Back to list</a></div>";
}else{
    
}
