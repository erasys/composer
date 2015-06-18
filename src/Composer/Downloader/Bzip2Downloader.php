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

namespace Composer\Downloader;

/**
 * Downloader for tar.bz2 files
 *
 * @author Konrad Gibaszewski <konrad@syncube.de>
 */
class Bzip2Downloader extends ArchiveDownloader {

  /**
   * {@inheritDoc}
   */
  protected function extract($file, $path) {
    try {
      exec('tar xfj ' . ' ' . $file . ' -C ' . $path);
    } catch (\UnexpectedValueException $e) {
      $message = sprintf("Could not extract archive '%s': %s",
        $file,
        $e->getMessage()
      );

      throw new \RuntimeException($message, $e->getCode(), $e);
    }

  }

}
