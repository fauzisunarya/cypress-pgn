<?php

namespace Drupal\restapi_telkom\Command;

use Drupal;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Core\Command\Command;

/**
 * Class TokenFlushCommand.
 *
 * Drupal\Console\Annotations\DrupalCommand (
 *     extension="restapi_telkom",
 *     extensionType="module"
 * )
 */
class TokenFlushCommand extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('restapi_telkom:tokenflush')
      ->setDescription($this->trans('commands.restapi_telkom.tokenflush.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    Drupal::database()->delete('auth_tokens')->condition('expired_at', date('Y-m-d H:i:s'), '<')->execute();
    $this->getIo()->info($this->trans('Expired token successfully deleted'));
  }

}
