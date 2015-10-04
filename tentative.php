<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/autoload.php';
$user = User::get_current_user();
if ($user instanceof AnonymousUser) {
    header('Location: /fblogin.php');
}
?>
<html lang="en" ng-app="app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>The UoftBaddy Project - Discussion</title>
    <!-- STYLES -->
    <!-- build:css lib/css/main.min.css -->
    <link rel="stylesheet" type="text/css" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/bower_components/rdash-ui/dist/css/rdash.min.css">
    <link rel="stylesheet" type="text/css" href="/css/tentative.css">
    <!-- endbuild -->
    <!-- SCRIPTS -->
    <!-- build:js lib/js/main.min.js -->
    <!-- jQuery -->
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.5/angular.min.js"></script>
    <script type="text/javascript" src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script type="text/javascript" src="/bower_components/angular-cookies/angular-cookies.min.js"></script>
    <script type="text/javascript" src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <!-- endbuild -->
    <link rel="stylesheet" href="bower_components/fullcalendar/dist/fullcalendar.css"/>
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--Calendar-->
    <script type="text/javascript" src="bower_components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="bower_components/angular-ui-calendar/src/calendar.js"></script>
    <script type="text/javascript" src="bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script type="text/javascript" src="bower_components/fullcalendar/dist/gcal.js"></script>
    <script src="bower_components/ngDialog/js/ngDialog.js"></script>
    <!-- Custom Scripts -->

    <!--Scroll-->
    <script src="bower_components/angular-smoothscroll/dist/angular-smooth-scroll.min.js"></script>

    <!--Affix -->
    <script src="/bower_components/ngScrollSpy/dist/ngScrollSpy.js"></script>
    <!-- Angular Moment -->
    <script src="bower_components/angular-moment/angular-moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0-beta.0/angular-sanitize.min.js"></script>  
    <script type="text/javascript" src="/angular/tentative.js"></script>
</head>
<body ng-controller="controller" ng-init="init('<?php echo $user->user_id;?>')">
    <div id="page-wrapper" ng-class="{'open': toggle}" ng-cloak>
    <?php Renderer::get_sidebar();?>
        <div id="content-wrapper">
            <div class="page-content">

                <!-- Header Bar -->
                <div class="row header" style="margin:0px;padding:0px;">    
                    <div class="col-xs-12" style="margin-bottom:0px;">
                        <div class="user pull-right">
                            <div class="item dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <img ng-src="{{user.avatar_link}}"> 
                                </a>
                                <?php Renderer::get_user_dropdown();?>
                            </div>
                            <div class="item dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <i class="fa fa-bell-o"><span class="badge" style="font-size:12px;position:absolute;top:10;color:white;background-color:#D9230F;" ng-show="data.newNotifications > 0">{{data.newNotifications}}</span></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right notification">
                                    <li class="dropdown-header">
                                        Notifications
                                        <span class="badge">{{data.newNotifications}}</span>
                                    </li>
                                    <li class="divider"></li>
                                    <li ng-repeat="notification in data.notifications" ng-style="notification.style">
                                        <a href ng-click="propogateRead(notification)">
                                            {{notification.message}}
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header">
                                        <a href="notifications.php">
                                            See All
                                        </a>
                                    </li>
                                    </ul>
                                </ul>
                            </div>
                        </div>
                        <div class="meta" style="margin:0px;padding:0px;">   
                            <div class="page">
                                UoftBaddy
                            </div>
                            <div class="breadcrumb-links">
                                Home / Discussion
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Header Bar -->
                <div class="col-lg-12 uoftBanner" style="margin-bottom:0px;padding:0px;margin-top:0px;">
                    <div class="uoftTitle">
                        <h1>
                            UoftBaddy - Discussion
                        </h1>
                        <hr style="margin-top:3px;margin-bottom:5px;padding:0px;">
                        <h3 style="margin-top:0px;padding:0px;">
                            {{data.allThreads.length}} posts in total
                        </h3>
                    </div>
                    <div class="stats">
<!--                         <h3 style="margin-right:5px;">
                            {{data.allThreads.length}} players planning to play this week
                        </h3> -->
                        <h3>
                            {{data.UsersWhoWantToPlay.length}} searching to play this week
                        </h3>
                    </div>
                </div>  
                    <div class="col-lg-12" style="padding:0px;">
                    <nav class="navbar navbar-default">
                      <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" class="navbar-toggle" ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
 <!--      <a class="navbar-brand" href="#">UofTBaddy</a> -->
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" ng-class="!navCollapsed && 'in'">
      <ul class="nav navbar-nav">
        <li ng-class="navbar.discussion"><a href="#" ng-click="handleNavbar('discussion')">All <span class="sr-only">(current)</span></a></li>
        <li ng-class="navbar.tentative" ><a href="#" ng-click="handleNavbar('tentative')">Looking To Play</a></li>
        <li ng-class="navbar.schedule"><a href="#" ng-click="handleNavbar('schedule')">Schedule</a></li>
<!--         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li> -->
      </ul>
<!--       <form class="navbar-form navbar-right" role="search" style="padding:0px;">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form> -->
<!--       <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Link</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
 -->    </div>
    </div> 
         </nav>
                    </div>
                <!-- Main Content -->
                <div ui-view>
                    <div class="row">
                        <div class="col-lg-12" ng-show="navbar.schedule">
                            <rd-widget>
                                <rd-widget-header title="Tentative Schedule">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <table class="table">
                                        <tr>
                                            <th>Index</th>
                                            <th>Author</th>
                                            <th>Looking To Play</th>
                                            <th>Posted</th>
                                            <th>View</th>
                                        </tr>
                                        <tbody>
                                            <tr ng-repeat="thread in data.tabular">
                                                <td>{{$index + 1}}</td>
                                                <td><a ng-href="profile.php?id={{thread.author.user_id}}">
                                                    <img ng-src="{{thread.author.avatar_link}}" style="height:30px;display:inline-block;">
                                                    {{thread.author.username}}
                                                    </a>
                                                </td>
                                                <td>{{thread.date_play | date:'medium'}}</td>
                                                <td>
                                                    <span am-time-ago="thread.date_posted">
                                                    </span> 
                                                </td>
                                                <td><a ng-href="thread.php?id={{thread.thread_id}}">View Post</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </rd-widget-body>
                            </rd-widget>
                        </div>
                        <div class="col-lg-8">
                            <div style="margin-bottom:10px;" ng-show="navbar.discussion || navbar.tentative">
                                <rd-widget>
                                    <rd-widget-header icon="fa-users" title="Write Post">
                                    </rd-widget-header>
                                    <rd-widget-body>
                                        <div class="message">
                                            <form class="form-horizontal" ng-show="data.showLookingToPlay">
                                                <div class="form-group">
                                                    <div class="col-lg-12">
                                                        <small style="float:right;">
                                                            Your post will be tagged with the <span class="label label-info">Looking To Play</span> tag
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="form-group">    
                                                    <label class="col-lg-2 control-label">When</label>
                                                    <div class="col-lg-10">
                                                        <p class="input-group">
                                                            <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="data.dt" is-open="datepicker.opened" min-date="minDate" max-date="'2020-06-22'" datepicker-options="dateOptions" date-disabled="disabled(date, mode)" ng-required="true" close-text="Close" />
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
        <!--                                         <div class="form-group">
                                                    <label class="col-lg-2 control-label">Title</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" ng-model="data.thread_title" class="form-control">
                                                    </div>
                                                </div> -->
        <!--                                         <div class="form-group">
                                                    <label class="col-lg-2 control-label">Details</label>
                                                    <div class="col-lg-10">
                                                        <textarea ng-model="data.thread_text" class="form-control" rows="5">
                                                        </textarea>
                                                    </div>
                                                </div> -->
                                            </form>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <img ng-src="{{user.avatar_link}}" class="img-responsive">
                                                </div>
                                                <div class="col-lg-10">
                                                    <textarea placeholder="Looking to play badminton, find a partner, or just talk?" rows="5" class="form-control" ng-model="data.thread_text" style="border:none;overflow: auto;outline: none;-webkit-box-shadow: none;-moz-box-shadow: none;box-shadow: none;" ng-click="data.showOptions = true">
                                                    </textarea>
                                                </div>
                                                <div class="col-lg-12" ng-show="data.showOptions">
                                                    <hr style="margin-top:3px;margin-bottom:3px;">
                                                </div>
                                                <div class="col-lg-12" ng-show="data.showOptions">
                                                    <span class="help-text" ng-show="data.askQuestion" style="display:inline-block;">
                                                        <small>
                                                            Are you looking to play badminton on some specific date? <a href ng-click="data.showLookingToPlay = true; data.asked = true;" style="margin-right:5px;">Yes</a><a href ng-click="data.showLookingToPlay = false; data.askQuestion = false; data.asked = true;">Unrelated</a>
                                                        </small>
                                                    </span>
                                                    <button class="btn btn-primary" style="float:right;" ng-click="postThread()">Post</button>
                                                </div>
                                            </div>
                                        </div>
                                    </rd-widget-body>
                                </rd-widget>
                            </div>
                            <div ng-show="navbar.discussion && !data.allThreads" style="margin-bottom:15px;">
                                <rd-widget>
                                    <rd-widget-body>
                                        <rd-loading></rd-loading>
                                    </rd-widget-body>
                                </rd-widget>
                            </div>
                            <div ng-show="navbar.discussion" ng-show="data.view == 1" ng-repeat="thread in data.allThreads" style="margin-bottom:15px;">
                                <rd-widget>
                                    <rd-widget-body>
                                        <div class="message">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="authorAvatar">
                                                       <img ng-src="{{thread.author.avatar_link}}" class="img-responsive" style="width:auto;height:50px;">
                                                        <span>
                                                            <a ng-href="profile.php?id={{thread.author.user_id}}">
                                                                {{thread.author.username}}
                                                            </a>
                                                            <br/>
                                                            <small ng-show="thread.type == 1" style="font-size:12px;color:#bdc3c7;">
                                                                <span am-time-ago="thread.date_posted"></span>&nbsp;&nbsp;<i class="fa fa-circle" style="font-size:3px;margin-top:2px;"></i>&nbsp;&nbsp;<span class="label label-info">Looking to Play</span>    at {{thread.date_play | date:'MMM d, y'}}
                                                            </small>
                                                            <small ng-show="thread.type == 2" style="font-size:12px;color:#bdc3c7;">
                                                                <span am-time-ago="thread.date_posted"></span>
                                                            </small>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <p ng-bind-html="thread.thread_text">
                                                    </p>
                                                    <hr style="margin-bottom:5px;margin-top:5px;">
                                                </div>
                                                <div ng-repeat="comment in thread.comments" style="margin-left:8%;">
                                                    <div class="authorAvatar">
                                                       <img ng-src="{{comment.author.avatar_link}}" class="img-responsive" style="width:auto;height:40px;">
                                                        <span>
                                                            <a ng-href="profile.php?id={{comment.author.user_id}}">
                                                                {{comment.author.username}}
                                                            </a>
                                                            <br/>
                                                            <small style="font-size:12px;color:#bdc3c7;">
                                                                <span am-time-ago="comment.date_posted"></span>
                                                            </small>
                                                        </span>
                                                    </div>
                                                    <a href ng-click="delete(comment)">
                                                        <span class="glyphicon glyphicon-remove" style="float:right;"></span>
                                                    </a>
                                                    <p>
                                                        {{comment.comment_text}}
                                                    </p>
                                                    <hr style="opacity:0.7;margin:0px;">
                                                </div>
                                                <hr>
                                                <div class="col-lg-12">
                                                    <form>
                                                        <textarea ng-model="thread.possible_comment" class="form-control" placeholder="Your comment..." enter-submit="postComment(thread.thread_id)">
                                                        </textarea>
                                                    </form>
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                    </rd-widget-body>
                                </rd-widget>
                            </div>
                            <div ng-show="navbar.tentative" ng-repeat="threads in data.threadsPartitioned" style="margin-bottom:10px;">
                                <rd-widget>
                                    <rd-widget-body>
                                        <div id="{{$index}}" class="title" style="font-size:20px;">
                                            {{threads[0].date_play | date:'MMM d, y'}}
                                            <small>
                                                ({{threads.length}} looking to play)
                                            </small>
                                        </div>
                                        <small am-time-ago="threads[0].date_play">
                                        </small>
                                        <div class="message">
                                            <hr>
                                            <div ng-repeat="thread in threads" class="message">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="authorAvatar">
                                                           <img ng-src="{{thread.author.avatar_link}}" class="img-responsive" style="width:auto;height:50px;">
                                                            <span>
                                                                <a ng-href="profile.php?id={{thread.author.user_id}}">
                                                                    {{thread.author.username}}
                                                                </a>
                                                                <br/>
                                                                <small ng-show="thread.type == 1" style="font-size:12px;color:#bdc3c7;">
                                                                    {{thread.date_posted | date:'medium'}}&nbsp;&nbsp;<i class="fa fa-circle" style="font-size:3px;margin-top:2px;"></i>&nbsp;&nbsp;<span class="label label-info">Looking to Play</span>    at {{thread.date_play | date:'MMM d, y'}}
                                                                </small>
                                                                <small ng-show="thread.type == 2" style="font-size:12px;color:#bdc3c7;">
                                                                    {{thread.date_posted | date:'medium'}}
                                                                </small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <p>
                                                            {{thread.thread_text}}
                                                        </p>
                                                        <hr style="margin-bottom:5px;margin-top:5px;">
                                                    </div>
                                                    <div ng-repeat="comment in thread.comments" style="margin-left:8%;">
                                                        <div class="authorAvatar">
                                                           <img ng-src="{{comment.author.avatar_link}}" class="img-responsive" style="width:auto;height:40px;">
                                                            <span>
                                                                <a ng-href="profile.php?id={{comment.author.user_id}}">
                                                                    {{comment.author.username}}
                                                                </a>
                                                                <br/>
                                                                <small style="font-size:12px;color:#bdc3c7;">
                                                                    <span am-time-ago="comment.date_posted"></span>
                                                                </small>
                                                            </span>
                                                        </div>
                                                        <a href ng-click="delete(comment)">
                                                            <span class="glyphicon glyphicon-remove" style="float:right;"></span>
                                                        </a>
                                                        <p>
                                                            {{comment.comment_text}}
                                                        </p>
                                                        <hr style="opacity:0.7;margin:0px;">
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <form>
                                                            <textarea ng-model="thread.possible_comment" class="form-control" placeholder="Your comment..." enter-submit="postCommentPartitioned(thread.thread_id, thread.possible_comment)">
                                                            </textarea>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </rd-widget-body>
                                </rd-widget>
                            </div>
                            <div ng-show="data.threads.length == 0">
                                <rd-widget>
                                    <rd-widget-body>
                                        <div class="message">
                                            <p class="text-center">
                                                No open invitations to play in the future
                                            </p>
                                        </div>
                                    </rd-widget-body>
                                </rd-widget>
                            </div>
                        </div>
                        <div class="col-lg-4" style="margin-bottom:20px;" ng-show="navbar.discussion || navbar.tentative">
                            <rd-widget>
                                <rd-widget-header icon="fa-users" title="Date">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <h4>{{data.rightNow}}</h4>
                                </rd-widget-body>
                            </rd-widget>
                        </div>
                        <div class="col-lg-4" style="margin-bottom:20px;" ng-show="navbar.discussion || navbar.tentative">
                            <rd-widget>
                                <rd-widget-header icon="fa-users" title="Info">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <img ng-src="{{user.avatar_link}}" ng-repeat="user in data.userSummary" style="height:50px;margin-right:2px;">
                                    <div style="margin-top:5px;">
                                        <small>{{data.userSummary.length}} active users
                                        <a href="/users.php" style="float:right;">
                                            View all <span class="glyphicon glyphicon-signal"></span>
                                        </a></small>
                                    </div>
                                    <hr>
                                    <img ng-src="{{user.avatar_link}}" ng-repeat="user in data.UsersWhoWantToPlay" style="height:50px;margin-right:2px;">
                                    <div style="margin-top:5px;">
                                        <small>{{data.UsersWhoWantToPlay.length}} user<span ng-show="data.UsersWhoWantToPlay.length != 1">s</span> want to play in the future
                                            <a href ng-click="handleNavbar('tentative')" style="float:right;">
                                                View all requests <span class="glyphicon glyphicon-calendar"></span>
                                            </a>
                                        </small>
                                    </div>
                                </rd-widget-body>
                            </rd-widget>
                        </div>
                        <div class="col-lg-4" style="margin-bottom:20px;" ng-show="navbar.tentative || navbar.discussion">
                            <rd-widget>
                                <rd-widget-header icon="fa-users" title="Looking To Play Dates">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <div class="message" ng-repeat="thread in data.threads">
                                        <a ng-click="navbar.tentative = 'active'; navbar.discussion = null;" scroll-to="{{$index}}">{{thread[0].date_play | date:'MMM d, y'}}</a>
                                    </div>
                                    <div class="message" ng-show="data.threads == {}">
                                        No Threads to show yet
                                    </div>
                                </rd-widget-body>
                            </rd-widget>
                        </div>
    <!--                     <div class="col-lg-4" style="margin-bottom:20px;">
                            <rd-widget>
                                <rd-widget-header icon="fa-users" title="Statistics">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <div class="message">
                                    </div>
                                </rd-widget-body>
                            </rd-widget>
                        </div> -->
                        <div class="col-lg-4" ng-show="navbar.tentative">
                            <rd-widget>
                                <rd-widget-header icon="fa-users" title="Players ({{data.allThreadPlayers.length}} searching)">
                                </rd-widget-header>
                                <rd-widget-body>
                                    <div class="message" ng-repeat="player in data.allThreadPlayers">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <a ng-href="profile.php?id={{player.user_id}}">
                                                    <img ng-src="{{player.avatar_link}}" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="col-lg-8">
                                                <a ng-href="profile.php?id={{player.user_id}}">
                                                    <h5>{{player.username}}</h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </rd-widget-body>
                            </rd-widget>
                        </div>
                    </div>
                </div>  
            </div><!-- End Page Content -->
        </div><!-- End Content Wrapper -->
    </div><!-- End Page Wrapper -->
</body>
</html>