<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="<?php echo $meta_description; ?>">
        <meta name="author" content="<?php echo $meta_author; ?>">
        <link rel="icon" href="ciauth.ico">

        <title><?php echo $title; ?></title>
        
        <!-- Latest compiled and minified jQuery -->
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

        <!-- Latest compiled and minified jQuery UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        
        <!-- Latest compiled and minified SweetAlert -->
        <script src="http://www.ciauth.com/js/sweetalert.min.js"></script>
        
        <!-- Recaptcha api -->
        <script src='https://www.google.com/recaptcha/api.js'></script>
       
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
         
        <!-- Ciauth Styles for demo screens -->
        <link rel="stylesheet" href="http://www.ciauth.com/css/ciauth_demo.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- Optional jQuery UI smoothness styles -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        
        <!-- Ciauth Styles for demo screens -->
        <link rel="stylesheet" href="http://www.ciauth.com/css/sweetalert.css">
        
        <!-- Smart menus Styles for demo screens -->
        <link href='http://www.ciauth.com/css/sm-core-css.css' rel='stylesheet' type='text/css' />

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">CIAUTH</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul id="main-menu" class="nav navbar-nav sm">
                        <?php echo $nav_menu ?>
                    </ul>
                    <ul id="main-menu" class="nav navbar-nav navbar-right">
                        <?php if ($this->ciauth->is_logged_in()){
                            $userdata = $this->ciauth->get_user_data();
                            
                            echo "<li class=\"nav-welcome\"><h5>Welcome " . $userdata->username . "</h5></li>";
                            echo "<li>&nbsp;</li>";
                            echo "<li><a href=\"logout\">Logout</a></li>";
                        }else{
                            echo "<li><a href=\"login\">Login</a></li>";
                            echo "<li><a href=\"register\">Register</a></li>";
                        }?>
                        
                    </ul>
                </div>
            </div>
        </nav>

        <?php echo $body; ?>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
       
        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        
        <!-- jQuery Nested Sortable js for menu creation -->
        <script src="/js/jquery.mjs.nestedSortable.js"></script>
        
        <!-- Ciauth Admin js -->
        <script src="/js/ciauth_admin.js"></script>
    </body>
</html>