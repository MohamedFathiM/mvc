<?php

// white list routes

use System\Application;

$app = Application::getInstance();
$app->route->add('/', 'Main/HomeController');

$app->route->add('/posts/:text/:id', 'Posts/Post');
$app->route->add('/404', 'Error/NotFound');

$app->route->notFound('/404');


pre($app);
