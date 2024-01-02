<?php
    $users = json_decode( file_get_contents( 'http://localhost/kens_api/users' ), true );

    foreach( $users as $user ){
        echo "<a href='view.php?id={$user['user_id']}'>{$user['username']}</a> ";
        echo "<a href='edit.php?id={$user['user_id']}'>Edit</a><br/>";
    }