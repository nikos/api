#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$laminasMvcApplication = \Laminas\Mvc\Application::init(require __DIR__ . '/../config/application.config.php');
$serviceManager = $laminasMvcApplication->getServiceManager();

$consoleApplication = new Application();
$consoleApplication->add($serviceManager->get(\Application\Console\ImportFromCsvCommand::class));

$consoleApplication->run();