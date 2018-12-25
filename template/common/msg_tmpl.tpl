{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - Sweet alert</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="stylesheet" href="__PUBLIC_STATIC__/common/bootstrap/css/plugins/sweetalert/sweetalert.css">
</head>
<body onload="fun()">
    <div style="display: none;">
        <?php if(strip_tags($msg)!="window.close()"){ ?>
            <?php switch ($code) {?>
                <?php case 1:?>
                <h1 class="in">:)</h1>
                <p class="msg" data-type="success"><?php echo(strip_tags($msg));?></p>
                <?php break;?>
                <?php case 0:?>
                <h1 class="in">:(</h1>
                    <p class="msg" data-type="error"><?php echo(strip_tags($msg));?></p>
                <?php break;?>
            <?php } ?>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间：
            </p>
            <b class="wait"><?php echo($wait);?></b>
        <?php } ?>
    </div>
    <script src="__PUBLIC_STATIC__/common/js/jquery-2.1.1.min.js"></script>
    <script src="__PUBLIC_STATIC__/common/bootstrap/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript">
            function fun() {
                var msg = $(".msg").html();
                var jump = $(".jump").html();
                var type = $(".msg").attr("data-type");
                var wait = parseInt($(".wait").html());
                var href = $("#href").attr("href");
                console.log(href)
                swal({
                    type: type,
                    title: msg,
                    text: jump+'<b class="wait">'+wait+'</b>',
                    showConfirmButton: false,
                    html:true
                });
                var interval = setInterval(function(){
                        var time = -- wait;
                        $(".wait").text(time);
                        if(time <= 0) {
                            location.href = href;
                            clearInterval(interval);
                        };
                }, 1000);
            }
           
                
    </script>
</body>
</html>
