<?php

namespace Drupal\split_preview\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Class PreviewContentCommand.
 */
class PreviewContentCommand implements CommandInterface {

  /**
   * A CSS selector string.
   *
   * If the command is a response to a request from an #ajax form element then
   * this value can be NULL.
   *
   * @var string
   */
  protected $selector;

  /**
   * A jQuery method to invoke.
   *
   * @var string
   */
  protected $method;

  /**
   * An optional list of arguments to pass to the method.
   *
   * @var array
   */
  protected $arguments;

  /**
   * Constructs an InvokeCommand object.
   *
   * @param string $selector
   *   A jQuery selector.
   * @param string $method
   *   The name of a jQuery method to invoke.
   * @param array $arguments
   *   An optional array of arguments to pass to the method.
   */
  public function __construct($selector, $method, array $arguments = []) {
    $this->selector = $selector;
    $this->method = $method;
    $this->arguments = $arguments;
  }

  /**
   * Renders Preview Content variables.
   *
   * @return array
   *   Returns Ajax preview content array.
   */
  public function render() {
    return [
      'command' => 'previewContent',
      'selector' => $this->selector,
      'args' => $this->arguments,
    ];
  }

}
