<?php
/**
 * @filesource modules/ar/views/creditor.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Creditor;

use \Kotchasan\Html;

/**
 * module=ar-authorized
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{

  /**
   * จัดการการตั้งค่า
   *
   * @param object $index
   * @return string
   */
  public function render($index)
  {
    // form
    $form = Html::create('form', array(
        'id' => 'setup_frm',
        'class' => 'setup_frm',
        'autocomplete' => 'off',
        'action' => 'index.php/ar/model/creditor/submit',
        'onsubmit' => 'doFormSubmit',
        'ajax' => true,
        'token' => true
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_The details of the creditor are used for entering into the contract}'
    ));
    $groups = $fieldset->add('groups');
    // authority
    $groups->add('text', array(
      'id' => 'authority',
      'labelClass' => 'g-input icon-user',
      'itemClass' => 'width50',
      'label' => '{LNG_Name}',
      'comment' => '%AUTHORITY%',
      'value' => isset(self::$cfg->authority) ? self::$cfg->authority : ''
    ));
    // idcard
    $groups->add('text', array(
      'id' => 'idcard',
      'labelClass' => 'g-input icon-profile',
      'itemClass' => 'width50',
      'label' => '{LNG_Identification number}',
      'pattern' => '[0-9]+',
      'maxlength' => 13,
      'comment' => '%AUTHORITYIDCARD%',
      'value' => isset(self::$cfg->idcard) ? self::$cfg->idcard : ''
    ));
    $groups = $fieldset->add('groups');
    // address
    $groups->add('text', array(
      'id' => 'address',
      'labelClass' => 'g-input icon-location',
      'itemClass' => 'width70',
      'label' => '{LNG_Address}',
      'comment' => '%AUTHORITYADDRESS%',
      'value' => isset(self::$cfg->address) ? self::$cfg->address : ''
    ));
    // phone
    $groups->add('text', array(
      'id' => 'phone',
      'labelClass' => 'g-input icon-phone',
      'itemClass' => 'width30',
      'label' => '{LNG_Phone}',
      'maxlength' => 32,
      'comment' => '%AUTHORITYPHONE%',
      'value' => isset(self::$cfg->phone) ? self::$cfg->phone : ''
    ));
    $groups = $fieldset->add('groups');
    // provinceID
    $groups->add('select', array(
      'id' => 'provinceID',
      'labelClass' => 'g-input icon-location',
      'itemClass' => 'width33',
      'label' => '{LNG_Province}',
      'comment' => '%AUTHORITYPROVINCE%',
      'options' => \Kotchasan\Province::all(),
      'value' => isset(self::$cfg->provinceID) ? self::$cfg->provinceID : 103
    ));
    // zipcode
    $groups->add('text', array(
      'id' => 'zipcode',
      'labelClass' => 'g-input icon-location',
      'itemClass' => 'width33',
      'label' => '{LNG_Zipcode}',
      'pattern' => '[0-9]+',
      'maxlength' => 10,
      'comment' => '%AUTHORITYZIPCODE%',
      'value' => isset(self::$cfg->zipcode) ? self::$cfg->zipcode : ''
    ));
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button save large',
      'value' => '{LNG_Save}'
    ));
    return $form->render();
  }
}
