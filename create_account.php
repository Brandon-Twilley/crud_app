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

if($is_logged_in) {
    ?>

    <html>
    <head>
        <style id="stndz-style"></style>
        <!-- Standard Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properties -->
        <title>Login Example - Semantic</title>
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
        <link rel="stylesheet" type="text/css"
              href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/table.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <style type="text/css">
            body {
                background-color: #DADADA;
            }

            body > .grid {
                height: 100%;
            }

            .column {
                max-width: 450px;
            }
        </style>
    </head>
    <body>
    <form class="ui large form">
        <div id="table-holder" class="ui segment" style="margin: 3em">
            <table class="ui striped unstackable table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <?php
                    if ($is_logged_in) {
                        echo '<th>Update / View</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="hidden_form" style="visibility: hidden;">
                <div id="is_adding" value="false">
                </div>
            </div>
            <?php
            if ($is_logged_in) {
                echo '<i id="add" class="plus square outline large icon" style="padding-left: 84%; font-size: 2em;"></i>';
            }
            ?>
        </div>
    </form>

    <?php
    if ($attempted_login && !$is_logged_in) {
        unset($_COOKIE['username']);
        unset($_COOKIE['password']);
        setcookie('password', null, -1, '/');
        setcookie('username', null, -1, '/');
        echo '<div class="ui red message" style="margin-left: 10em; margin-right: 10em; text-align: center;">Failed login</div>';
    }
    if (!$is_logged_in) {
        unset($_COOKIE['username']);
        unset($_COOKIE['password']);
        echo '<div class="ui message" style="margin-left: 10em; margin-right: 10em; text-align: center;"><a href="signin.php">Sign In</a></div>';
    } else {
        echo '<div class="ui message" style="margin-left: 10em; margin-right: 10em; text-align: center;"><a href="signin.php">Log Out</a></div>';
    }
    ?>

    <script>

        $(document).ready(function () {
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                console.log('is a device');
                var cnt = $("#table-holder").contents();
                $("#table-holder").replaceWith(cnt);
            } else {
                console.log('is not a device.');
            }

            <?php

            if($is_logged_in){

            ?>

            var edit_function = function () {
                $('.edit.large.icon').each(function () {
                    $(this).click(function () {

                        var login_information = {
                            id: 0,
                            username: "",
                            password: ""
                        };

                        var element_id = $(this).parent().parent().attr('id');
                        login_information.id = element_id;


                        //go from text to input box
                        console.log('editing: ' + $(this).parent().parent().attr('id'));


                        $('#' + element_id + ' td').each(function (i) {
                            if (i == 0) {
                                login_information.username = $(this).text();
                            } else if (i == 1) {
                                login_information.password = $(this).text();
                            }
                        });
                        var table_row_edit_template = '<tr id="submit_' + login_information.id + '" ><td> <input type="text" ></td><td> <input type="text" ></td><td><div id="submit" class="ui button">Submit</div></td></tr>';
                        var table_row_commit_template = '<tr id="' + login_information.id + '" ><td>' + login_information.username + '</td><td>' + login_information.password + '</td><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td></tr>';

                        $('#' + element_id).replaceWith(table_row_edit_template);

                        $('#submit_' + login_information.id + ' input').each(function (i) {
                            if (i == 0) {
                                $(this).val(login_information.username);
                            } else if (i == 1) {
                                $(this).val(login_information.password);
                            }
                        });

                        //Creating our editor is now done

                        $('#submit').click(function () {

                            $('#submit_' + element_id + ' input').each(function (i) {
                                if (i == 0) {
                                    login_information.username = $(this).val();
                                } else if (i == 1) {
                                    login_information.password = $(this).val();
                                }
                            });

                            var succeeds = false;
                            var json_login_info = JSON.stringify(login_information);

                            console.log('posting data: ' + json_login_info);

                            $.ajax({
                                url: 'admin_backend.php ',
                                type: 'PUT',
                                data: json_login_info,
                                success: function (response) {
                                    succeeds = true;
                                    console.log('PUT Information: ' + json_login_info);
                                    console.log('PUT Response: ' + response);
                                }
                            });

                            $('#submit_' + element_id).replaceWith(table_row_commit_template);

                            $('#' + element_id + ' td').each(function (i) {
                                if (i == 0) {
                                    $(this).text(login_information.username);
                                } else if (i == 1) {
                                    $(this).text(login_information.password);
                                }
                            });

                            edit_function();
                            erase_function();
                        });
                    });
                });
            };
            var erase_function = function () {
                $('.eraser.large.icon').each(function () {

                    $(this).click(function () {

                        var login_information = {
                            id: 0,
                            username: "",
                            password: ""
                        };

                        var current_id = $(this).parent().parent().attr('id');
                        login_information.id = current_id;
                        $('#' + current_id + ' td ').each(function (i) {
                            if (i == 0) {
                                login_information.username = $(this).text();
                            } else if (i == 1) {
                                login_information.password = $(this).text();
                            }
                            console.log('td: ' + $(this).text());
                        });
                        var json_login_info = JSON.stringify(login_information);
                        console.log(json_login_info);

                        $.ajax({
                            url: 'admin_backend.php',
                            type: 'DELETE',
                            data: json_login_info,
                            success: function (response) {

                                $('#' + login_information.id).remove();
                                console.log(response);

                            }
                        });
                    });
                });
            };

            //add a selection
            $('#add').click(function () {
                console.log('clicked add');
                if ($('#is_adding').val() == 'true') {

                } else {
                    $('#is_adding').val('true');

                    var table_row_template = '<tr id="submit_row" ><td> <input type="text" ></td><td> <input type="text" ></td><td><div id="submit_add" class="ui button">Submit</div></td></tr>';
                    $('tbody').append(table_row_template);

                    $('#submit_add').click(function () {
                        var login_information = {
                            username: "",
                            password: ""
                        };
                        if ($('#is_adding').val() == 'false') {

                        } else {
                            $('#submit_row input').each(function (i) {
                                if (i == 0) {
                                    login_information.username = $(this).val();
                                } else if (i == 1) {
                                    login_information.password = $(this).val();
                                }
                            });
                        }
                        if (login_information.username.includes(' ') || login_information.username == '') {
                            alert("can't have a space in your ISBN number");
                        } else {
                            var book_info_json = JSON.stringify(login_information);
                            $.post('admin_backend.php', book_info_json).done(function () {
                            });
                            var table_row_template = '<tr id="' + login_information.username + '" ><td>' + login_information.username + '</td><td>' + login_information.password + '</td><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td></tr>';
                            $('tbody tr').last().remove();
                            $('tbody').append(table_row_template);

                            edit_function();
                            erase_function();
                        }
                    });
                }
            });
            <?php
            }
            ?>
            //get database in JSON format
            $.get('admin_backend.php', function (data) {
                let login_list = JSON.parse(data);
                if (login_list.length != 0) {
                    for (var i = 0; i < login_list.length; i++) {
                        var table_row_template = '<tr id="' + login_list[i].id + '" ><td>' + login_list[i].username + '</td><td>' + login_list[i].password + '</td><?php if($is_logged_in){ ?><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td><?php } ?></tr>';
                        $('tbody').append(table_row_template);
                    }
                }


                //edit element
                <?php
                if($is_logged_in){

                ?>
                edit_function();
                //delete element
                erase_function();

                <?php
                }
                ?>
            });
        });
    </script>
    </body>
    </html>
    <?php
}
    ?>