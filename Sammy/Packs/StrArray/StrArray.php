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
  use Saml;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   * It ensures that the script flux is not interrupted
   * when trying to run the current command by the cli
   * API.
   */
  if (!class_exists ('Sammy\Packs\StrArray\StrArray')) {
  /**
   * @class StrArray
   * Base internal class for the
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
  class StrArray {
    /**
     * @var array $datas
     *
     * A map of whole the stream datas.
     *
     */
    private $datas = [];

    public function __construct ($array, $datas = null) {

      if (is_string ($datas)) {
        $datas = ['filePath' => $datas];
      }

      $this->setDatas ($datas);
    }

    public function setDatas ($datas) {
      if (is_array ($datas)) {
        foreach ($datas as $key => $val) {
          $this->setData ($key, $val);
        }
      }
    }

    public function __get ($key) {
      return $this->get ($key);
    }

    public function __set ($key, $val) {
      return $this->set ($key, $val);
    }

    public function __toString () {
      return $this->to_s ();
    }

    /**
     * Array generic methods
     */
    public function push ($data = null) {
      $filePath = $this->getData('filePath');

      if ($filePath) {

        $fArray = $this->getFArray ($filePath);

        array_push ($fArray, is_array ($data) ? $data : self::Stringify ($data));
        file_put_contents ($filePath, json_encode ($fArray));
      }

      return false;
    }

    public function merge ($with = null) {

      if (func_num_args () >= 1) {
        $filePath = $this->getData ('filePath');

        if (!$filePath) {
          return;
        }

        $fArray = $this->getFArray ($filePath);

        $args = func_get_args ();

        foreach ($args as $i => $arg) {
          if (is_array ($arg) && $arg) {
            $fArray = array_merge ($fArray, $arg);
          }
        }

        file_put_contents ($filePath, json_encode ($fArray));
        return $this;
      }
    }

    public function set ($key, $val = null) {

      $filePath = $this->getData ('filePath');

      if ($filePath && file_exists ($filePath)) {

        $file_content = $this->getFArray ($filePath);

        if (is_string ($key)) {
          $file_content [$key] = is_array ($val) ? $val : self::Stringify ($val);
        } else {
          foreach ($key as $key => $val) {
            $file_content [self::Stringify ($key)] = self::Stringify ($val);
          }
        }

        $new_file_content = json_encode ($file_content);
        file_put_contents ($filePath, $new_file_content);
        return $this;
      }

      return false;
    }

    public function get ($key) {

      if (is_string ($key) && $fArray = $this->getFArray ()) {
        return isset ($fArray [$key]) ? $fArray [$key] : null;
      }

      return null;
    }

    public function to_s () {
      return $this->FileContent ();
    }

    public function to_a () {
      return $this->getFArray ();
    }

    private function setData ($key, $val) {
      $this->datas [ $key ] = $val;
    }

    private function FileContent () {
      $fs = requires ('fs');

      return $fs->readFile ($this->getData ('filePath'));
    }

    private function getData ($key = null) {
      if (is_string ($key) && isset ($this->datas [$key])) {
        return $this->datas [$key];
      }
    }

    private function getFArray ($filePath = null) {
      if (!(is_string ($filePath) && $filePath)) {
        $filePath = $this->getData ('filePath');
      }

      $fs = requires ('fs');

      $fileData = json_decode ($fs->readFile ($filePath));

      if ($fileData) {
        return self::_object2array ($fileData);
      }
    }

    private static function _object2array ($object) {
      if (!(is_object ($object))) {
        return $object;
      }

      $object = ((array)($object));
      $newObject = [];

      foreach ($object as $key => $value) {
        $newObject [$key] = self::_object2array ($value);
      }

      return is_array ($newObject) ? $newObject : ((array)($newObject));
    }

    private static function Stringify ($data) {
      if (in_array (gettype($data), ['object', 'array'])) {
        return json_encode ($data);
      }

      return (string)($data);
    }

  }}
}
