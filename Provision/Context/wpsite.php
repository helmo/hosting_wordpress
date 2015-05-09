<?php

/**
 * @file WP Provision named context site class.
 */

class Provision_Context_wpsite extends Provision_Context {
  public $parent_key = 'wpplatform';

  static function option_documentation() {
    return array(
      'wpplatform' => 'site: the platform the wpsite is run on',
      'db_server' => 'site: the db server the wpsite is run on',
      'uri' => 'site: example.com URI, no http:// or trailing /',
      'language' => 'site: site language; default en',
      'aliases' => 'site: comma-separated URIs',
      'redirection' => 'site: boolean for whether --aliases should redirect; default false',
      'client_name' => 'site: machine name of the client that owns this site',
      // 'profile' => 'site: Drupal profile to use; default default',
      'drush_aliases' => 'site: Comma-separated list of additional Drush aliases through which this site can be accessed.',
    );
  }

  function init_site() {
    $this->setProperty('uri');

     // we need to set the alias root to the platform root, otherwise drush will cause problems.
    $this->root = $this->wpplatform->root;

    // set this because this path is accessed a lot in the code, especially in config files.
    $this->site_path = $this->root . '/sites/' . $this->uri;

    $this->setProperty('site_enabled', true);
    $this->setProperty('language', 'en');
    $this->setProperty('client_name');
    $this->setProperty('aliases', array(), TRUE);
    $this->setProperty('redirection', FALSE);
    $this->setProperty('cron_key', '');
    $this->setProperty('drush_aliases', array(), TRUE);

    // this can potentially be handled by a Drupal sub class
    $this->setProperty('profile', 'default');
  }

  /**
   * Write out this named context to an alias file.
   */
  function write_alias() {
    $config = new Provision_Config_Drushrc_Alias($this->name, $this->properties);
    $config->write();
    foreach ($this->drush_aliases as $drush_alias) {
      $config = new Provision_Config_Drushrc_Alias($drush_alias, $this->properties);
      $config->write();
    }
  }
}
