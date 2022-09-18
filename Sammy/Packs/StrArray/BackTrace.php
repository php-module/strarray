<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\StrArray
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\StrArray {
  /**
   * Make sure the module base internal trait is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!trait_exists ('Sammy\Packs\StrArray\BackTrace')) {
  /**
   * @trait BackTrace
   * Base internal trait for the
   * StrArray module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   */
  trait BackTrace {
    /**
     * @method boolean validTrace
     *
     * verify if a given data is or not
     * a valid array containing stack trace
     * informations.
     *
     * this should just verify the settings about
     * some common keys in that array.
     * Such as: file, line
     *
     * @param mixed $trace
     *
     * The given data to verify if is or not a valid
     * stack trace array.
     *
     */
    protected static function validTrace ($trace = null) {
      return ( boolean ) (
        is_array ($trace) && $trace &&
        isset ($trace [ 0 ]) &&
        is_array ($trace [ 0 ]) &&
        isset ($trace [ 0 ][ 'file' ]) &&
        is_string ($trace [ 0 ][ 'file' ]) &&
        isset ($trace [ 0 ][ 'line' ])
      );
    }

    /**
     * @method string traceFile
     *
     * Get the 'file' property inside a given
     * stack trace array.
     *
     * @param array $trace
     *
     * The stack trace array.
     *
     */
    protected static function traceFile ($trace) {
      if (self::validTrace ($trace)) {
        return $trace [ 0 ][ 'file' ];
      }
    }
  }}
}
