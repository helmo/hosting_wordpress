<?php

/**
 * 
 */
function drush_provision_wordpress_cli_validate() {
}

/**
 * Implements the provision-wordpress-cli command.
 */
function drush_provision_wordpress_cli() {
  drush_log(print_r($GLOBALS['argv'], 1));

  // Normally argv will have:
  // 0 = /path/to/drush.php
  // 1 = --php=foo
  // 2 = --php-options=bar
  // 3 = @fooalias
  // 4 = wp
  // 5 = info (etc)
  //
  // We remove the first arguments until 'wp' is second (the first argument
  // being wp.phar or whatever), which lets us re-write as 0=wp, 1=--path[..],
  // and leave the rest intact.
  do {
    array_shift($GLOBALS['argv']);
  } while ($GLOBALS['argv'][1] != 'wp');

  $GLOBALS['argv'][0] = 'wp';
  $GLOBALS['argv'][1] = '--path=' . d()->root . '/';

  // FIXME: this causes drush to exist with:
  // "Drush command terminated abnormally due to an unrecoverable error."
  define('WP_CLI_ROOT', '/var/aegir/lib/wp-cli');
  require_once '/var/aegir/lib/wp-cli/php/wp-cli.php';
}