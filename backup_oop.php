#!/usr/bin/php
<?php

define('ROOT_DIR', dirname(__FILE__));
require_once "includes/autoload.inc";
require_once "includes/bootstrap.inc";

try {
  $application->run();
}
catch (Exception $e) {
  throw $e;
}
