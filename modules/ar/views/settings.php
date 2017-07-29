<?php
/**
 * @filesource modules/ar/views/settings.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Settings;

use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=ar-settings
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
        'action' => 'index.php/ar/model/settings/submit',
        'onsubmit' => 'doFormSubmit',
        'ajax' => true,
        'token' => true
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_Module settings}'
    ));
    // currency_unit
    $fieldset->add('select', array(
      'id' => 'currency_unit',
      'labelClass' => 'g-input icon-currency',
      'itemClass' => 'item',
      'label' => '{LNG_Currency unit}',
      'options' => Language::get('CURRENCY_UNITS'),
      'value' => isset($index->currency_unit) ? $index->currency_unit : 'THB'
    ));
    $table = $fieldset->add('groups', array(
      'label' => '{LNG_Extra interest}',
      'comment' => '{LNG_Determine how to calculate additional payment if the payment does not meet the due date}'
    ));
    $row = $table->add('row');
    // excess_interest1
    $row->add('text', array(
      'id' => 'excess_interest1',
      'labelClass' => 'g-input icon-event',
      'itemClass' => 'width',
      'label' => '{LNG_Number}',
      'unit' => '{LNG_days}',
      'pattern' => '[0-9]+',
      'value' => isset($index->excess_interest1) ? $index->excess_interest1 : 5
    ));
    // multipier1
    $row->add('text', array(
      'id' => 'multipier1',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'width',
      'label' => '{LNG_multiplier}',
      'pattern' => '[\.0-9]+',
      'value' => isset($index->multipier1) ? $index->multipier1 : 0.5
    ));
    $row = $table->add('row');
    // excess_interest2
    $row->add('text', array(
      'id' => 'excess_interest2',
      'labelClass' => 'g-input icon-event',
      'itemClass' => 'width',
      'label' => '{LNG_Number}',
      'unit' => '{LNG_days}',
      'pattern' => '[0-9]+',
      'value' => isset($index->excess_interest2) ? $index->excess_interest2 : 20
    ));
    // multipier2
    $row->add('text', array(
      'id' => 'multipier2',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'width',
      'label' => '{LNG_multiplier}',
      'pattern' => '[\.0-9]+',
      'value' => isset($index->multipier2) ? $index->multipier2 : 1
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
