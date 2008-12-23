<?php
/*
 * This file is part of the sfRequest plugin
 * (c) 2008 Lee Bolding <lee@php.uk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfHardenedRequest extends sfRequest, adding the *error functionality that was
 * present in Symfony 1.0 but removed in Symfony 1.1 and Symfony 1.2
 *
 * @package    sfRequestPlugin
 * @author     Lee Bolding <lee@php.uk.com>
 * @version    SVN: $Id$
 */
abstract class sfHardenedRequest extends sfRequest {

  /**
   * Retrieves an error message.
   *
   * @param string An error name
   *
   * @return string An error message, if the error exists, otherwise null
   */
  public function getError($name, $catalogue = 'messages')
  {
    $retval = null;

    if (isset($this->errors[$name]))
    {
      $retval = $this->errors[$name];

      // translate error message if needed
      if (sfConfig::get('sf_i18n'))
      {
        $retval = $this->context->getI18N()->__($retval, null, $catalogue);
      }
    }

    return $retval;
  }

  /**
   * Retrieves an array of error names.
   *
   * @return array An indexed array of error names
   */
  public function getErrorNames()
  {
    return array_keys($this->errors);
  }

  /**
   * Retrieves an array of errors.
   *
   * @return array An associative array of errors
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Indicates whether or not an error exists.
   *
   * @param string An error name
   *
   * @return boolean true, if the error exists, otherwise false
   */
  public function hasError($name)
  {
    return array_key_exists($name, $this->errors);
  }

  /**
   * Indicates whether or not any errors exist.
   *
   * @return boolean true, if any error exist, otherwise false
   */
  public function hasErrors()
  {
    return (count($this->errors) > 0);
  }

  /**
   * Removes an error.
   *
   * @param string An error name
   *
   * @return string An error message, if the error was removed, otherwise null
   */
  public function & removeError($name)
  {
    $retval = null;

    if (isset($this->errors[$name]))
    {
      $retval =& $this->errors[$name];

      unset($this->errors[$name]);
    }

    return $retval;
  }

  /**
   * Sets an error.
   *
   * @param string An error name
   * @param string An error message
   *
   */
  public function setError($name, $message)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->info('{sfHardenedRequest} error in form for parameter "'.$name.'" (with message "'.$message.'")');
    }

    $this->errors[$name] = $message;
  }

  /**
   * Sets an array of errors
   *
   * If an existing error name matches any of the keys in the supplied
   * array, the associated message will be overridden.
   *
   * @param array An associative array of errors and their associated messages
   *
   */
  public function setErrors($errors)
  {
    $this->errors = array_merge($this->errors, $errors);
  }

}
