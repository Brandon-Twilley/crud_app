<?php
    $is_logged_in = false;
    $is_superadmin = false;
    $attempted_login = false;
    $mysqli = new mysqli("localhost", 'root', '', 'biags_store');

    $result = $mysqli->query('SELECT * FROM users;');
    $query_response = array();

    while(true){
        $row = $result->fetch_assoc();
        if($row == null){
            break;
        }
        array_push($query_response, $row);
    }


    if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
        $attempted_login = true;
        foreach($query_response as $query_row){
            if($_COOKIE['username'] == $query_row['username'] && $_COOKIE['password'] == $query_row['password']){
                $is_logged_in = true;
                if($query_row['is_admin'] == 1){
                    $is_superadmin = true;
                }
            }
        }
    }


?>

<html><head><style id="stndz-style"></style>
    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/reset.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/site.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/container.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/grid.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/header.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/image.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/menu.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/divider.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/segment.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/form.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/input.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/button.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/message.css">
    <link rel="stylesheet" type="text/css" href="https://semantic-ui.com/dist/components/icon.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/table.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <?php
        if(!$is_logged_in) {
    ?>
    <script>
        $(document).ready(function () {
            $('#login').click(function () {
                let username = $('#username').val();
                let password = $('#password').val();
                console.log('username: ' + username);
                console.log('password: ' + password);
                document.cookie = 'username=' + username + ';';
                document.cookie = 'password=' + password + ';';
                window.location.href = '';
            });
        });
    </script>
    <?php
        } elseif(!$is_logged_in && $attempted_login) {
    ?>
    <script>
        $(document).ready(function () {
            $('#login').click(function () {
                let username = $('#username').val();
                let password = $('#password').val();
                console.log('username: ' + username);
                console.log('password: ' + password);
                document.cookie = 'username=' + username + ';';
                document.cookie = 'password=' + password + ';';
                window.location.href = '';
            });
        });
    </script>
    <?php
        } elseif($is_logged_in) {
    ?>
    <script>
        var delete_cookie = function(name) {
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };

        $(document).ready(function () {
            $('#logout').click(function () {
                delete_cookie('username');
                delete_cookie('password');
                window.location.href = 'index.php';
            });

            <?php
                if($is_superadmin){
            ?>
            $('#admin').click(function(){
                window.location.href = 'admin_backend.php';
            });
            <?php
            }
            ?>
        });
    </script>

    <?php
        }
    ?>

    <style type="text/css">
        input {
            background-color: transparent;
            border-color: rgba(0,0,0,.3);
        }
        body {
            background-color: #DADADA;
        }
        body > .grid {
            height: 100%;
        }
        .image {
            margin-top: -100px;
        }
        .column {
            max-width: 450px;
        }
    </style>
</head><style id="stylish-1" class="stylish" type="text/css">
    @namespace url(http://www.w3.org/1999/xhtml);</style>
<body>
        <?php
            if(!$is_logged_in) {
        ?>
        <div class="ui middle aligned center aligned grid">
            <div class="column">

                <form class="ui large form">
                    <div class="ui stacked segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" id="username">
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" id="password">
                            </div>
                        </div>
                        <div class="ui fluid large red submit button" id="login">Login</div>
                    </div>

                    <div class="ui error message"></div>

                </form>


            </div>
        </div>
        <?php
            } else {
        ?>

        <div class="ui middle aligned center aligned grid">
            <div class="column">

                <form class="ui large form">
                    <div class="ui segment">
                        <div class="ui fluid large red submit button" id="logout">Logout</div>
                    </div>
                </form>


            </div>
        </div>
        <?php
            }

        if($is_superadmin) {
        ?>

            <div class="ui middle aligned center aligned grid">
                <div class="column">

                    <form class="ui large form">
                        <div class="ui segment">
                            <div class="ui fluid large red submit button" id="admin">Create New Admin Account</div>
                        </div>
                    </form>


                </div>
            </div>

        <?php
        }
        ?>




</body></html>
