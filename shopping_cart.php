<?php

session_start();

$mysqli = new mysqli("localhost", 'root', '', 'biags_store');

$result = $mysqli->query('SELECT * FROM products;');

if(isset($_SESSION['cart'])){
    $cart = $_SESSION['cart'];

    foreach ($cart as $cart_item) {
        if(isset($cart_item->id)){
            if($cart_item->id != 0){
                $result = $mysqli->query('SELECT * FROM products WHERE id='.$cart_item->id.';');
                $row = $result->fetch_assoc();
                $cart_item->name = $row['name'];
                $cart_item->price = $row['price'];
            }
        }
    }

}

?>

<html>
<head>
    <style id="stndz-style"></style>
    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>Shopping Cart</title>
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
    <div class="ui segment" style="margin: 3em">
        <table class="ui striped table">
            <thead>
            <tr>
                <th>name</th>
                <th>price</th>
                <th>Quantity</th>
                <th>Remove From Cart</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $total_price = 0;
                if(isset($_SESSION['cart'])){
                    foreach ($cart as $cart_item){
                        if(isset($cart_item->id)){
                            if($cart_item->id != 0){
                                $total_price = $total_price + $cart_item->price;
                                echo '<tr id="'.$cart_item->id.'" ><td>'.$cart_item->name.'</td><td>$'.number_format($cart_item->price, 2, '.', '').'</td><td>'.$cart_item->quantity.'</td><td><div class="ui fluid large submit button"><i class="trash alternate icon"></div></i></td><tr>';
                            }
                        }
                    }

                }
            ?>
            </tbody>
        </table>
        <?php
            echo '<div> The total price is: $'.$total_price.'</div>';
        ?>
        <div id="hidden_form" style="visibility: hidden;">
            <div id="is_adding" value="false">
            </div>
        </div>
    </div>
</form>

<script>

    $(document).ready(function(){
        $('.button').click(function(){
            /*let parent = $(this).parent();
            let name = $(parent).find('td')[0];

            let add_item_to_shopping_cart = {
                id: 0,
                quantity: 0
            };

            let json_object = JSON.stringify(add_item_to_shopping_cart);

            $.post('cart_session.php', json_object, function(response){
                console.log(response);
            });*/
            console.log('clicked trash can');
            let row = $(this).parent().parent();
            console.log('row id: ' + $(row).attr('id'));


            let delete_object = {
                id: Number($(row).attr('id'))
            };

            let json_object = JSON.stringify(delete_object);

            $.ajax({
                url: 'cart_session.php',
                type: 'DELETE',
                data: json_object,
                success: function(response) {

                    $('#' + delete_object.id).remove();
                    console.log('response: ' + response);
                    console.log('deleted object: ' + delete_object.id);
                }
            });
        });

    })
</script>
<!--
<script>

    $(document).ready(function(){
        <?php

        if($is_logged_in){

        ?>

        var edit_function = function() {
            $('.edit.large.icon').each( function () {
                $(this).click( function () {

                    var item = {
                        name: "",
                        description: "",
                        price: "",
                        id: ""
                    };

                    var element_id = $(this).parent().parent().attr('id');


                    //go from text to input box
                    console.log('editing: ' + $(this).parent().parent().attr('id'));


                    $('#' + element_id + ' td').each(function (i) {
                        if(i == 0){
                            item.name = $(this).text();
                        } else if(i == 1){
                            item.description = $(this).text();
                        } else if(i == 2){
                            item.price = $(this).text();
                        } else if(i == 3){
                            item.id = $(this).text();
                        }
                    });
                    var table_row_edit_template = '<tr id="submit_' + item.id + '" ><td> <input type="text" ></td><td> <input type="text" ></td><td><input type="text" ></td><td>' + item.id + '</td><td><div id="submit" class="ui button">Submit</div></td></tr>';
                    var table_row_commit_template = '<tr id="' + item.id + '" ><td>' + item.name + '</td><td>' + item.description + '</td><td>' + item.price + '</td><td>' + item.id + '</td><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td></tr>';

                    $('#' + element_id).replaceWith(table_row_edit_template);

                    $('#submit_' + item.id + ' input').each(function (i) {
                        if(i == 0){
                            $(this).val(item.name);
                        } else if(i == 1){
                            $(this).val(item.description);
                        } else if(i == 2){
                            $(this).val(item.price);
                        } else if(i == 3){
                            $(this).val(item.id);
                        }
                    });

                    //Creating our editor is now done

                    $('#submit').click( function () {

                        $('#submit_' + element_id + ' input').each(function (i) {
                            if(i == 0){
                                item.name = $(this).val();
                            } else if(i == 1){
                                item.description = $(this).val();
                            } else if(i == 2){
                                item.price = $(this).val();
                            } else if(i == 3){
                                item.id = $(this).val();
                            }
                        });

                        var succeeds = false;
                        var json_item = JSON.stringify(item);

                        console.log('posting data: ' + json_item);

                        $.ajax({
                            url: 'backend.php',
                            type: 'PUT',
                            data: json_item,
                            success: function(response) {
                                succeeds = true;
                                console.log('PUT Information: ' + json_item);
                                console.log('PUT Response: ' + response);
                            }
                        });

                        $('#submit_' + element_id).replaceWith(table_row_commit_template);

                        $('#' + element_id + ' td').each(function (i) {
                            if(i == 0){
                                $(this).text(item.name);
                            } else if(i == 1){
                                $(this).text(item.description );
                            } else if(i == 2){
                                $(this).text(item.price);
                            } else if(i == 3){
                                $(this).text(item.id );
                            }
                        });

                        edit_function();
                        erase_function();
                    });
                });
            });
        };
        var erase_function = function() {
            $('.eraser.large.icon').each(function () {

                $(this).click( function () {

                    var item = {
                        name: "",
                        description: "",
                        price: "",
                        id: ""
                    };

                    var current_id = $(this).parent().parent().attr('id');
                    $('#' + current_id + ' td ').each( function(i){
                        if(i == 0){
                            item.name = $(this).text();
                        } else if(i == 1){
                            item.description = $(this).text();
                        } else if(i == 2){
                            item.price = $(this).text();
                        } else if(i == 3){
                            item.id = $(this).text();
                        }
                        console.log('td: ' + $(this).text());
                    });
                    var json_item = JSON.stringify(item);
                    console.log(json_item);

                    $.ajax({
                        url: 'backend.php',
                        type: 'DELETE',
                        data: json_item,
                        success: function(response) {

                            $('#' + item.id).remove();
                            console.log(response);

                            console.log('item json: ' + json_item);

                        }
                    });
                });
            });
        };

        //add a selection
        $('#add').click( function () {
            console.log('clicked add');
            if($('#is_adding').val() == 'true'){

            } else {
                $('#is_adding').val('true');

                var table_row_template = '<tr id="submit_row" ><td> <input type="text" ></td><td> <input type="text" ></td><td><input type="text" ></td><td>###</td><td><div id="submit_add" class="ui button">Submit</div></td></tr>';
                $('tbody').append( table_row_template );

                $('#submit_add').click( function () {
                    var item = {
                        name: "",
                        description: "",
                        price: "",
                    };
                    let succeeds_typecheck = true;
                    if($('#is_adding').val() == 'false'){

                    } else {
                        $('#submit_row input').each( function(i){
                            if(i == 0){
                                item.name = $(this).val();
                            } else if(i == 1){
                                item.description = $(this).val();
                            } else if(i == 2){
                                item.price = $(this).val();
                                if(isNaN(item.price)){
                                    alert('your price isn\'t a number');
                                    succeeds_typecheck = false;
                                } else {
                                    item.price = Number(item.price);
                                }
                            }
                        });
                    }
                    if(!succeeds_typecheck){
                    } else {
                        var item_json = JSON.stringify(item);
                        $.post( 'backend.php', item_json , function(response){
                            console.log(response);
                            console.log('item json: ' + item_json);
                        });
                        var table_row_template = '<tr id="' + item.id + '" ><td>' + item.name + '</td><td>' + item.description + '</td><td>' + item.price + '</td><td>' + item.id + '</td><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td></tr>';
                        $('tbody tr').last().remove();
                        $('tbody').append( table_row_template );

                        edit_function();
                        erase_function();
                    }
                });
            }
        });

        $.get( 'backend.php', function ( data ) {
            let item = JSON.parse(data);
            if( item.length != 0 ) {
                for(var i = 0;i<item.length;i++){
                    var table_row_template = '<tr id="' + item[i].id + '" ><td>' + item[i].name + '</td><td>' + item[i].description + '</td><td>' + item[i].price + '</td><td>' + item[i].id + '</td><?php if($is_logged_in){ ?><td><i class="edit large icon"></i>&nbsp;<i class="eraser large icon"></i></td><?php } ?></tr>';
                    $('tbody').append( table_row_template );

                }
            }



            //edit element
            edit_function();
            //delete element
            erase_function();

        });

        <?php
        } else {
        ?>
        //get database in JSON format
        $.get('backend.php', function (data) {
            let item = JSON.parse(data);
            if (item.length != 0) {
                for (var i = 0; i < item.length; i++) {
                    var table_row_template = '<tr id="' + item[i].id + '" ><td>' + item[i].name + '</td><td>' + item[i].description + '</td><td>$' + Number(item[i].price).toFixed(2) + '</td><td><div class="ui fluid large submit button" id="shopping_cart"><i class="shopping cart icon"></i></div></td><td><input type="number" value="0" ></td></tr>';
                    $('tbody').append(table_row_template);
                }
            }
            $('#shopping_cart').click(function(){
                let parent = $(this).parent();
                let name = $(parent).find('td')[0];

                let add_item_to_shopping_cart = {
                    id: 0,
                    quantity: 0
                };

                let json_object = JSON.stringify(add_item_to_shopping_cart);

                $.post('cart_session.php', json_object, function(response){
                    console.log(response);
                });
            });
        });


        <?php
        }
        ?>
    });
</script>
</body>
</html>-->