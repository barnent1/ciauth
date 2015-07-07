<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Ciauth - Authorization, Navigation, and Template libraries for CodeIgniter.">
        <meta name="author" content="Glen Barnhardt, CEO Barnhardt Enterprises, Inc.">
        <link rel="icon" href="../../favicon.ico">

        <title>Starter Template for Ciauth using Bootstrap</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
        
        <!-- Optional jQuery UI smoothness styles -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Project name</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">

            <div class="starter-template">
                <h1>Bootstrap starter template</h1>
                <div class="m-login-area">
                    <?php
                    if ($this->ciauth->is_logged_in()) {
                        echo "<a href=\"https://www.deepswamp.com/logout/\" id=\"logout\" class=\"button-small\">LOGOUT</a>";
                        echo "<a href=\"https://www.deepswamp.com/my_account/\" id=\"my_account\" class=\"button-small\">Account</a>";
                        if ($this->ciauth->is_admin()) {
                            echo "<a href=\"https://www.deepswamp.com/ds_admin\" class=\"button-small\">ADMIN</a>";
                        }
                    } else {
                        echo "<a href=\"login\" class=\"button-small\">LOGIN</a>";
                        echo "<a href=\"register\" class=\"button-small\">REGISTER</a>";
                    }
                    ?>
                </div>

                <nav>
                    <ul class="main-menu">
                        <?php echo $nav_menu ?>
                    </ul>
                </nav>
                <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
            </div>

        </div><!-- /.container -->

        <?php echo $body; ?>
        

        <?php if ($this->uri->segment(1) !== "ds_admin" && $this->uri->segment(1) !== "my_account") { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/deepswamp.css" />
        <?php } else { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/deepswamp_admin.css" />
        <?php } ?>
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        
        <!-- Latest compiled and minified jQuery UI -->
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
        <script src="<?php echo base_url(); ?>js/sweetalert.min.js"></script>

    </body>
</html>