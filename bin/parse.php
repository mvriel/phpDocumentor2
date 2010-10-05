#!/usr/bin/env php
<?php
// set path to add lib folder, load the Zend Autoloader and include the symfony timer
set_include_path(get_include_path().PATH_SEPARATOR.'./lib');
require_once('Zend/Loader/Autoloader.php');
require_once('symfony/sfTimer.class.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('DocBlox_');

// output greeting text
echo 'DocBlox parser version ' . DocBlox_Abstract::VERSION . PHP_EOL . PHP_EOL;

// gather and parse arguments
try
{
  $opts = new DocBlox_Arguments();
  $opts->parse();

  // getTarget throws an exception if an incorrect path was given; in which case we want to show the help
  $path = $opts->getTarget();

  // the user has indicated that he would like help
  if ($opts->getOption('h'))
  {
    throw new Zend_Console_Getopt_Exception('');
  }

  if (count($opts->getFiles()) < 1)
  {
    throw new Zend_Console_Getopt_Exception('No parsable files were found, did you specify any using the -f or -d parameter?');
  }

} catch (Zend_Console_Getopt_Exception $e)
{
  // if the message actually contains anything, show it.
  if ($e->getMessage())
  {
    echo $e->getMessage() . PHP_EOL . PHP_EOL;
  }

  // show help message and exit the application
  echo $opts->getUsageMessage();
  exit;
}

// initialize the parser and pass the options as provided by the user or defaults
$docblox = new DocBlox_Parser();
$docblox->setLogLevel($opts->getOption('verbose') ? Zend_Log::DEBUG : $docblox->getLogLevel());
$docblox->setExistingXml(is_readable($path.'/structure.xml') ? file_get_contents($path.'/structure.xml') : null);
$docblox->setIgnorePatterns($opts->getIgnorePatterns());
$docblox->setForced($opts->getOption('force'));
$docblox->setMarkers($opts->getMarkers());

// save the generate file to the path given as the 'target' option
file_put_contents($path.'/structure.xml', $docblox->parseFiles($opts->getFiles()));
