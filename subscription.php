<?php
$list=[
   
    ['icon'=>'icon-time','value'=>'Start Date:',"time"=>"12 May 2024",'img'=>'static/images/clock-regular.svg'],
    ['icon'=>'icon-time','value'=>'End Date:',"time"=>"12 June 2024",'img'=>'static/images/clock-solid.svg'],
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
<!--    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css">-->
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
        /*border: #777777 2px solid;*/
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
    /*width: 100%;*/
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%; /* 确保容器的高度占满父容器 */
}

.visiting-body-select {
    display: flex;
    flex-wrap: nowrap;
    font-size: 20px;
    padding: 10px;
    width: 100%; /* 修改为 100%，以占满父容器的宽度 */
    justify-content: space-around; /* 水平居中 */
    align-items: center; /* 垂直居中 */
    text-align: center;
}

/*.visiting-body-select div {*/
/*    flex: 1; !* 使内部元素占据同样的宽度 *!*/
/*    display: flex;*/
/*    justify-content: center; !* 水平居中 *!*/
/*    align-items: center; !* 垂直居中 *!*/
/*}*/

/*.visiting-body-select select,*/
/*.visiting-body-select p {*/
/*    text-align: center;*/
/*    width: 100%;*/
/*}*/
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
                    <h3>Subscription details</h3>
                </div>
                <div class="bottom-visiting-body">
                    <div class="visiting-body-select">
                        <div>
                            <i class="icon-calendar"></i>
                            
                            <p>Subscription Type: <span style="font-weight: bold; margin-left: 170px;">Free Trial</span></p>
                        
                        </div>
                        <div>
                            <!-- <select>
                                <option value="1">Free trial</option>
                                <option value="2">Small Business</option>
                                <option value="3">Medium-Sized Business</option>
                                <option value="4">Large Enterprise</option>
                            </select> -->
                            <p style="font-weight: bold"><?= $key["value"] ?></p>
                        </div>
                    </div>
                    <?php foreach ($list as $key): ?>
                    <div class="visiting-body-select">
                        <div>
                            <i class="<?= $key["icon"] ?>"></i>
                            <p><?= $key["value"] ?></p>
                        </div>
                        <div>
                            <p style="font-weight: bold"><?= $key["time"] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="bottom-visiting-button">
                    <button data-toggle="modal" data-target="#exampleModal">Cancel</button>
                    <button onclick="navigatorTo('select.php')">Upgrade</button>
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
                Are you confirm the cancellation?
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
    // const getURLParameter=(name)=>{
    //     name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    //     var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    //         results = regex.exec(location.search);
    //     return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    // }
    (function () {
        let paramValue = getURLParameter('isOne')
        if (paramValue === 'true')$('#exampleModal1').modal()
    })()
</script>

</html>
