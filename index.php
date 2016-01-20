<?php
/*
* define path to json and project dir 
*/
define("JSONPATH", 'json'.DIRECTORY_SEPARATOR);
define("DIR", __DIR__);

/*
* include messages class
*/
require('app/Messages.class.php');

/*
* include model
*/
require('app/ViewModel.php');

/*
* include controller
*/
require('app/ViewController.php');

/*
* set view data
*/
$MyController = new ViewController();
$view_data = $MyController->getView();

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NetCoDev JsonCreator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <style type="text/css">
        .jumbotron * {font-size: 11px !important;}
        .jumbotron h2 {font-size: 13px !important;font-weight: bold;padding-bottom:30px;}
        input[type=text] {width: 300px;}
        .tap-data {display: block;padding: 0 0 30px 0;}
        .row.tap-btn:hover .tap-data {display: block;}
        .formrow {padding: 3px 0;}
        .formfooter {padding-top: 30px;margin-top: 30px;border-top: 1px solid #ccc;}
        .tab-content {padding-top: 10px;}
    </style>
</head>
<body>

    <?php include('views/nav_top.php'); ?>
    
    <div class="container">
        
        <?php include('views/nav_tabs.php'); ?>
        
        <div class="tab-content">
        <!-- START CONTENT NEW //-->
          <div role="tabpanel" class="tab-pane fade<?php if($view_data['activelayer'] === 'new'){echo('  in active');} ?>" id="new">
          
            <?php if ($view_data['activelayer'] === 'new') { include('views/alerts.php');}  ?>
            
            <?php include('views/json_add.php'); ?>
            
          </div>
        <!-- END CONTENT NEW //-->
        <!-- START CONTENT ALL //-->
          <div role="tabpanel" class="tab-pane fade<?php if($view_data['activelayer'] === 'all'){echo('  in active');} ?>" id="all">
        
            <?php include('views/resource_select.php'); ?>
            
            <?php include('views/resource_edit.php'); ?>
            
            <?php if ($view_data['activelayer'] === 'all') {include('views/alerts.php');}  ?>
            
            <?php include('views/resource_data.php'); ?>
            
            </div>
        <!-- END CONTENT ALL //-->
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript">
    
    
    $(document).ready(function () {
        
        /*
        
        @ToDO implement request handling
        
        $('form').on('submit', function () {
            switch(this.name){
                case'newJsonForm':
                    break;
            }
            return false;
        })
        
        */
        
    });
    </script>
</body>
</html>
