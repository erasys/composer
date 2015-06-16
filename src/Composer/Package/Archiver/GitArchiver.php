<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Package\Archiver;

use Composer\Util\Git as GitUtil;

/**
 * @author Till Klampaeckel <till@php.net>
 * @author Nils Adermann <naderman@naderman.de>
 * @author Matthieu Moquet <matthieu@moquet.net>
 * @author Konrad Gibaszewski <konrad@syncube.de>
 */
class GitArchiver implements ArchiverInterface {

  protected static $formats = [
    'tar'     => \Phar::TAR,
    'tar.gz'  => \Phar::GZ,
    'tar.bz2' => \Phar::BZ2,
  ];

  /**
   * {@inheritdoc}
   */
  public function archive($sources, $target, $format, array $excludes = []) {

    $sources = realpath($sources);

    GitUtil::cleanEnv();

    try {
      $tag = shell_exec("cd $sources && git describe --tags");
      shell_exec("cd $sources && git archive -o $target $tag");
      return $target;
    } catch (\UnexpectedValueException $e) {
      $message = sprintf("Could not create archive '%s' from '%s': %s",
        $target,
        $sources,
        $e->getMessage()
      );

      throw new \RuntimeException($message, $e->getCode(), $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function supports($format, $sourceType) {
    return isset(static::$formats[$format]);
  }

}
