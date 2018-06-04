<?php

/** @var string $title */
/** @var string $mainHtml */
/** @var string $configHttpNodeModules */

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo htmlspecialchars($title); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/bootstrap/dist/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/ionicons/dist/css/ionicons.css">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/jvectormap/jquery-jvectormap.css">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/admin-lte/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($configHttpNodeModules); ?>/admin-lte/dist/css/skins/_all-skins.css">
        <link rel="shortcut icon" href="/favicon.ico"/>

        <!--[if lt IE 9]>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/html5shiv/dist/html5shiv.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/respond/main.js"></script>
        <![endif]-->

        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
        >
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php /*TOP NAV BAR*/ ?>
            <header class="main-header">
                <a href="/" class="logo">
                    <span class="logo-mini"><b>AB</b></span>
                    <span class="logo-lg"><b>Address Book</b></span>
                </a>
                <nav class="navbar navbar-static-top">
                </nav>
            </header>

            <?php /*LEFT NAV BAR*/ ?>
            <aside class="main-sidebar">
                <section class="sidebar">

                    <?php /*CONTACT SEARCH*/ ?>
                    <?php /*MAIN MENU*/ ?>
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">ADDRESS SEARCH</li>
                    </ul>
                    <form action="/search" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input name="q" class="form-control" placeholder="Search..." type="text">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>

                    <?php /*MAIN MENU*/ ?>
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MAIN NAVIGATION</li>

                        <li>
                            <a href="/contacts">
                                <i class="fa fa-address-card"></i> <span>Contacts</span>
                            </a>
                        </li>

                        <li>
                            <a href="/populate-test-data">
                                <i class="fa fa-bug"></i> <span>Set To Test Data</span>
                            </a>
                        </li>
                    </ul>
                </section>
            </aside>

            <div class="content-wrapper">
                <?php echo $mainHtml; ?>
            </div>

            <footer class="main-footer">
                <strong>Copyright &copy; 2018 <a href="https://www.jeremysells.info">Jeremy Sells</a>.</strong> All rights reserved.
            </footer>
        </div>

        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/jquery/dist/jquery.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/fastclick/lib/fastclick.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/admin-lte/dist/js/adminlte.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/jquery-sparkline/jquery.sparkline.js"></script>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/jvectormap/jquery-jvectormap.js"></script>
        <?php /*<script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/slimscroll/lib/slimscroll.js"></script>*/ ?>
        <script src="<?php echo htmlspecialchars($configHttpNodeModules); ?>/chart.js/Chart.js"></script>
    </body>
</html>
