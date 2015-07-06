<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="<?php echo $meta_description; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico">
        <title><?php echo $title; ?></title>
        <script src="//use.typekit.net/jvj0wgk.js"></script>
        <script>try {
                Typekit.load();
            } catch (e) {
            }</script>

        <!-- Chat Script -->
        <!-- ClickDesk Live Chat Service for websites -->
        <script type='text/javascript'>
            var _glc = _glc || [];
            _glc.push('all_ag9zfmNsaWNrZGVza2NoYXRyDwsSBXVzZXJzGKzxpcQLDA');
            var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' :
                    'http://my.clickdesk.com/clickdesk-ui/browser/');
            var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
            var glcspt = document.createElement('script');
            glcspt.type = 'text/javascript';
            glcspt.async = true;
            glcspt.src = glcpath + 'livechat-new.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(glcspt, s);
        </script>
        <!-- End of ClickDesk -->


        <!-- jQuery UI Styles Smoothness -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/darkness/jquery-ui.css" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

        <!-- Site CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/template.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/ciauth_nav.css" />

        <?php if ($this->uri->segment(1) !== "ds_admin" && $this->uri->segment(1) !== "my_account") { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/deepswamp.css" />
        <?php } else { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/deepswamp_admin.css" />
        <?php } ?>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/datatables.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/sweetalert.css">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/ciauth.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/animate.css" />


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head> 
    <body>
        <div class="wrap">
            <header>
                <div class="content">
                    <div class="container">
                        <div class="grid">

                            <div class="grid-unit six-fifteenths tablet-one-third small-tablet-whole">
                                <a href="/"><img src="<?php echo base_url(); ?>images/logo-deep-swamp.png" alt="Deep Swamp" class="logo" /></a>
                            </div>

                            <div class="grid-unit nine-fifteenths tablet-two-thirds">

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
                            </div>

                        </div>
                    </div>
                </div>
            </header> 
            <?php echo $body; ?>
        </div>

        <section class="black-background">

            <article class="container social">
                <div class="grid">
                    <div class="grid-unit three-fifteenths push-left-four-fifteenths text-center">
                        <h3>Find us socializing here</h3>

                        <ul class="social-media">
                            <li><a href="https://www.facebook.com/pages/Deep-Swamp-Inc/1655056028061604"><span class="icon-facebook"> </span></a></li>
                            <li><a href="https://twitter.com/Deep_SwampInc"><span class="icon-twitter"> </span></a></li>
                            <li><a href="https://linkedin.com/profile/view?id=109331299"><span class="icon-linkedin"> </span></a></li>
                            <li><a href="https://plus.google.com/100406621048923619421"><span class="icon-googleplus"></span></a></li>
                        </ul>
                    </div>

                    <div class="grid-unit four-fifteenths push-left-one-fifteenth text-center foot_links">
                        <h3><a href="/contact-us" style="color: #8b8b8b !important;">Contact Us</a></h3>
                        <p><a href="/contact-us" style="color: #8b8b8b !important;">support@deepswamp.com</a></p> // &nbsp;&nbsp;|&nbsp;&nbsp;813.440.7575
                    </div>
                </div>  
            </article>
        </section>

        <footer class="footer">
            <p class="text-center"><a href="/terms-of-service">Terms</a>  |  <a href="/privacy-policy">Privacy Policy</a> </p>
            <p class="text-center">&copy; 2015 Deep Swamp. All rights reserved.</p>
        </footer>


        <?php echo (!empty($modal_login_template) ? $modal_login_template : ""); ?>

        <!-- Bootstrap core JavaScript
       ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

        <!-- TinyMCE Editor core JavaScript
       ================================================== -->
        <script type="text/javascript" src="<?php echo base_url(); ?>js/tinymce/tinymce.min.js"></script>

        <!-- DataTables core JavaScript
       ================================================== -->
        <script type="text/javascript" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

        <!-- Ciauth core JavaScript
        ================================================== -->
        <script src="<?php echo base_url(); ?>js/ciauth.js" ></script>
        <script src="<?php echo base_url(); ?>js/ciauth_nav.js"></script>
        <!-- Touch Swipe for slideshow
        ================================================== -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.js"
        type="text/javascript"></script> 

        <!-- Other JavaScript Libraries
      ================================================== -->
        <script src="<?php echo base_url(); ?>js/sweetalert.min.js"></script>
        <script src="<?php echo base_url(); ?>js/accordion.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>js/ds.js"></script>
        <script src="<?php echo base_url(); ?>js/slideshow.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.selection.js"></script>

    </body>
</html>
