#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$laminasMvcApplication = \Laminas\Mvc\Application::init(require __DIR__ . '/../config/application.config.php');
$serviceManager = $laminasMvcApplication->getServiceManager();

$consoleApplication = new Application();

// add commands here
// see https://symfony.com/doc/current/console.html
// $consoleApplication->add($serviceManager->get(\Application\Console\ExampleCommand::class));

$consoleApplication->run();
