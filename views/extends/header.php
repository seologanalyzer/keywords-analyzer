<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SLA - Keywords Analyzer - <?php echo $controller->getControllerName(); ?> - <?php echo $controller->getActionName(); ?></title>

    <!-- Bootstrap framework -->
    <link rel="stylesheet" href="/cosmo/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css" />
    <!-- tooltips-->
    <link rel="stylesheet" href="/lib/qtip2/jquery.qtip.min.css" />
    <!-- main styles -->
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/lib/sticky/sticky.css" />
    <link rel="stylesheet" href="/img/splashy/splashy.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans" />

    <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/ie.css" />
        <script src="js/ie/html5.js"></script>
                    <script src="js/ie/respond.min.js"></script>
    <![endif]-->

    <script src="/js/jquery.min.js"></script>

  </head>
  <body>
    <div id="maincontainer" class="clearfix">
      <!-- header -->
      <header>
        <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container-fluid" style="margin-top:5px;">
              <a class="brand" href="/"><img src="/sla/logo.png" style="margin-top:-3px;" alt="logo" /></a>
              <ul class="nav user_menu pull-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Bienvenue, <?php echo ucwords($controller->user->user['name']); ?><b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="/index/logout">Déconnexion</a></li>
                  </ul>
                </li>
              </ul>
              <nav>
                <div class="nav-collapse">
                  <ul class="nav">
                    <li>
                      <a href="/parameters/">Paramètres</a>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </header>