<?php
$list=[
    ['icon'=>'icon-time','value'=>'Start Date:',"time"=>"12 May 2024&nbsp;&nbsp;&nbsp;",'img'=>'static/images/clock-regular.svg'],
    ['icon'=>'icon-time','value'=>'End Date :',"time"=>"12 &nbsp;June 2024 ",'img'=>'static/images/clock-solid.svg'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="static/js/public.js"></script>
    <script src="static/js/jquery-3.2.1.slim.min.js"></script>
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <script src="static/js/jquery.min.js"></script>
    <script src="static/js/popper.min.js"></script>
    <script src="static/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" href="static/css/main.css">
    <script src="static/js/public.js"></script>
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
<style>
    .header{
        background-color: #CBEAD0;
    }
    .header1{
        background-color: #F3CCD6;
    }
    .main{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card{
        width: 100%;
        height: 100%;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .card-top{
        display: flex;
        flex-direction: row;
        justify-content:space-between;
        align-items: center;
        height: 100px;
        background-color: #D3E5FD;
        border-bottom: #DFEDF6 1px solid;
        padding: 10px;
    }
    .card-bottom{
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .card-bottom-visiting{
        width: 50%;
        height: 60%;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color:#D3E5FD;
        border: black 2px solid;
    }
    .bottom-visiting-title{
        width: 100%;
        display: flex;
        justify-content: center;
    }
    button{
        background-color: #040404;
        color: #FFF;
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin: 20px;
    }
    .logout{
        width: 100%;
        position: fixed;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        font-size: 20px;
        font-weight: 400;
        padding-right: 20px;
        color: #595F6D;
        cursor: pointer;
    }
.back{
    font-size: 40px;
    font-weight: bold;
    padding: 10px;
}
.bottom-visiting-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.visiting-body-select {
    display: flex;
    flex-wrap: nowrap;
    font-size: 20px;
    padding: 10px;
    width: 100%;
    justify-content: space-around;
    align-items: center;
    /*text-align: center;*/
}
.bottom-visiting-button{
    display: flex;
    justify-content: center;
    padding: 20px;
}
</style>
</head>
<body>
<div class="main">
    <div class="card shadow-sm bg-white rounded">
        <div class="card-top">
            <h3> View Subscription Page</h3>
            <p style="font-size: 25px;">Business owner</p>
        </div>
        <i class="back fas fa-chevron-left" onclick="navigatorTo('personal.php')"></i>
        <div class="card-bottom">
            <div class="card-bottom-visiting">
                <div class="bottom-visiting-title">
                    <h3>Subscription Details</h3>
                </div>
                <div class="bottom-visiting-body" style="width:100%"; display:>
<!--                    <div class="visiting-body-select">-->
<!--                        <div>-->
<!--                            <i class="icon-calendar"></i>-->
<!--                            <p>Subscription Type:</p>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            <span style="font-weight: bold; margin-left: 170px;">Free Trial</span>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="visiting-body-select">
                        <div style="flex: 1;display: flex;justify-content: flex-end;margin-right: 40px">
                            <i class="icon-calendar"></i>
                            <p>Subscription Type:</p>
                        </div>
                        <div style="flex: 1;display: flex;justify-content: flex-start">
                            <p style="font-weight: bold;">Free Trial</p>
                        </div>
                    </div>
                    <?php foreach ($list as $key): ?>
                    <div class="visiting-body-select">
                        <div style="flex: 1;display: flex;justify-content: flex-end;margin-right: 40px">
<!--                            <img src="--><?php //= $key['img'] ?><!--" alt="--><?php //= $key['value'] ?><!--" style="width: 20px; margin-right: 5px;">-->
                            <p style="display:inline;"><?= $key["value"] ?></p>
                        </div>
                        <div style="flex: 1;display: flex;justify-content: flex-start">
                            <p style="font-weight:bold"><?= $key["time"] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="bottom-visiting-button">
                    <button onclick="navigatorTo('select.php')" style="margin-left: 20px;">Upgrade Subscription</button>
                    <button data-toggle="modal" data-target="#exampleModal" style="margin-right: 20px;">Cancel Subscription</button>
                </div>
                </div>
            </div>
            <div class="logout">
                <p>Logout</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel" style="color:green;">Confirm Cancellation</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Confirm subscription cancellation?
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" style="background-color: #545b62">Cancel</button>
                <button data-dismiss="modal" onclick="navigatorTo('personal.php?isOne=true')" style="background-color: #2F67EF">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header">
                <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Update successfully!
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" style="background-color: #2F67EF">OK</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    (function () {
        let paramValue = getURLParameter('isOne')
        if (paramValue === 'true')$('#exampleModal1').modal()
    })()
</script>

</html>
