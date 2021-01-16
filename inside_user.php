<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurants</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

</head>
<body>


<form name="readReviews">
    <div><b>Read reviews</b></div>
    <div class="mar10-tb"><label> Enter restaurant name&nbsp&nbsp&nbsp</label>
        <input name="name" size="30"></div>
    <div><button type="submit" id="restName_send">OK</button></div>
</form>
<script>
    var i = 0;
    $( document ).ready(function() {
        $("#restName_send").click(
            function(){
                let obj = {
                    "jsonrpc": "2.0",
                    "method": "readReviews",
                    "params": {"restName": document.forms.readReviews.name.value},
                    "id": i
                }
                let myJson = JSON.stringify(obj);
                i++;
                $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: myJson,
                    cache: false,
                    contentType: 'application/json',
                    success: function(data){
                        for(let i = 0; i < data.length; i++) {
                            let div = document.createElement('div');
                            div.className = "review";
                            div.innerHTML = data[i];
                        }
                    },
                    error:function(data){
                        console.log('error');
                    }
                });
                return false;
            }
        );
    });

</script>


<?php
    $redis = new Redis();
    $redis->connect("127.0.0.1","6379");
    $restaurantsNumber = $redis->hGet('restaurants', "restaurantsNumber");
    for($i = 1; $i <= $restaurantsNumber; $i++) {
        $restaurant = $redis->hGet('restaurants', $i);
        echo '<div><b>Restaurant:'.$restaurant.'</b></div>';
        echo '<div><b>Address:'.$redis->hGet($restaurant, 'address').'</b></div>';
        echo '<div><b>Phone number:'.$redis->hGet($restaurant, 'phoneNumber').'</b></div>';
        echo '<div><b>Information:'.$redis->hGet($restaurant, 'info').'</b></div>';
    }





?>
</body>
</html>