<?php
/**
 * @filesource modules/ar/views/addtransaction.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Addtransaction;

use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=ar-addtransaction
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{

  /**
   * ฟอร์มเพิ่มรายละเอียดของบัญชี
   *
   * @param int $id
   * @return string
   */
  public static function render($id)
  {
    // form
    $form = Html::create('form', array(
        'id' => 'setup_frm',
        'autocomplete' => 'off',
        'action' => 'index.php/ar/model/addtransaction/submit',
        'onsubmit' => 'doFormSubmit',
        'ajax' => true,
        'token' => true
    ));
    $fieldset = $form->add('header', array(
      'innerHTML' => '<h2 class="icon-edit">{LNG_Additional items}</h2>'
    ));
    $fieldset = $form->add('fieldset');
    // type
    $fieldset->add('select', array(
      'id' => 'type',
      'labelClass' => 'g-input icon-config',
      'itemClass' => 'item',
      'label' => '{LNG_want}',
      'options' => Language::get('AR_TYPIES')
    ));
    // member_id
    $fieldset->add('select', array(
      'id' => 'member_id',
      'labelClass' => 'g-input icon-customer',
      'itemClass' => 'item',
      'label' => '{LNG_Creditor}',
      'options' => \Ar\Detail\Model::getCreditors()
    ));
    // create_date
    $fieldset->add('date', array(
      'id' => 'create_date',
      'labelClass' => 'g-input icon-calendar',
      'itemClass' => 'item',
      'label' => '{LNG_date}',
      'value' => date('Y-m-d')
    ));
    // amount
    $fieldset->add('currency', array(
      'id' => 'amount',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'item',
      'label' => '{LNG_Total amount}',
      'unit' => Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit)
    ));
    // percent
    $fieldset->add('currency', array(
      'id' => 'percent',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'item',
      'label' => '{LNG_Interest}',
      'unit' => '%',
    ));
    // detail
    $fieldset->add('textarea', array(
      'id' => 'detail',
      'labelClass' => 'g-input icon-file',
      'itemClass' => 'item',
      'label' => '{LNG_Note or additional notes}'
    ));
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button ok large',
      'value' => '{LNG_Save}'
    ));
    // office_id
    $fieldset->add('hidden', array(
      'id' => 'office_id',
      'value' => $id
    ));
    return createClass('Gcms\View')->renderHTML($form->render());
  }
}
