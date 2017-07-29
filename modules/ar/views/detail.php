<?php
/**
 * @filesource modules/ar/views/detail.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Detail;

use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=ar-detail
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{

  /**
   * เพิ่ม-แก้ไข รายละเอียดของลูกค้า
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
        'action' => 'index.php/ar/model/detail/submit',
        'onsubmit' => 'doFormSubmit',
        'ajax' => true,
        'token' => true
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_Details of} {LNG_Customer}'
    ));
    $group = $fieldset->add('groups');
    // name
    $group->add('text', array(
      'id' => 'name',
      'labelClass' => 'g-input icon-customer',
      'itemClass' => 'width40',
      'label' => '{LNG_Name}',
      'maxlength' => 100,
      'value' => isset($index->name) ? $index->name : ''
    ));
    // sex
    $group->add('select', array(
      'id' => 'sex',
      'labelClass' => 'g-input icon-sex',
      'itemClass' => 'width10',
      'label' => '{LNG_Sex}',
      'options' => Language::get('SEXES'),
      'value' => isset($index->sex) ? $index->sex : 'f'
    ));
    // phone
    $group->add('text', array(
      'id' => 'phone',
      'labelClass' => 'g-input icon-phone',
      'itemClass' => 'width30',
      'label' => '{LNG_Phone}',
      'maxlength' => 32,
      'value' => isset($index->phone) ? $index->phone : ''
    ));
    $group = $fieldset->add('groups');
    // id_card
    $group->add('text', array(
      'id' => 'id_card',
      'labelClass' => 'g-input icon-profile',
      'itemClass' => 'width50',
      'label' => '{LNG_Identification number}',
      'pattern' => '[0-9]+',
      'maxlength' => 13,
      'value' => isset($index->id_card) ? $index->id_card : ''
    ));
    // expire_date
    $group->add('date', array(
      'id' => 'expire_date',
      'labelClass' => 'g-input icon-calendar',
      'itemClass' => 'width50',
      'label' => '{LNG_Expiration date}',
      'value' => isset($index->expire_date) ? $index->expire_date : date('Y-m-d')
    ));
    // address
    $fieldset->add('text', array(
      'id' => 'address',
      'labelClass' => 'g-input icon-address',
      'itemClass' => 'item',
      'label' => '{LNG_Address}',
      'maxlength' => 64,
      'value' => isset($index->address) ? $index->address : '999 หมู่ 9 ต.ลาดหญ้า อ.เมือง'
    ));
    $group = $fieldset->add('groups');
    // provinceID
    $group->add('select', array(
      'id' => 'provinceID',
      'labelClass' => 'g-input icon-location',
      'itemClass' => 'width50',
      'label' => '{LNG_Province}',
      'options' => \Kotchasan\Province::all(),
      'value' => isset($index->provinceID) ? $index->provinceID : 103
    ));
    // zipcode
    $group->add('text', array(
      'id' => 'zipcode',
      'labelClass' => 'g-input icon-location',
      'itemClass' => 'width50',
      'label' => '{LNG_Zipcode}',
      'pattern' => '[0-9]+',
      'maxlength' => 10,
      'value' => isset($index->zipcode) ? $index->zipcode : 71000
    ));
    // detail
    $fieldset->add('textarea', array(
      'id' => 'detail',
      'labelClass' => 'g-input icon-file',
      'itemClass' => 'item',
      'label' => '{LNG_Detail}',
      'rows' => 5,
      'value' => isset($index->detail) ? $index->detail : 'ที่ดินพร้อมสิ่งปลูกสร้าง ตามใบ ภ.บ.ท.5 เลขที่สำรวจ xx/xxx หมู่ที่ xx ตำบล วังด้ง อำเภอ เมือง จังหวัด กาญจนบุรี เนื้อที่ xx ไร่ xx งาน xx วา'
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_Account details}'
    ));
    $currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    $group = $fieldset->add('groups-table', array(
      'comment' => '{LNG_Enter the principal amount of each creditor (0 means not the creditor of this account)}'
    ));
    $creditor = array();
    $total = 0;
    if (isset($index->creditor)) {
      foreach (explode(',', $index->creditor) as $item) {
        list($a, $b) = explode('|', $item);
        $creditor[$a] = (double)$b;
        $total += $creditor[$a];
      }
    }
    // creditor + amount
    foreach (\Ar\Detail\Model::getCreditors() as $id => $displayname) {
      $row = $group->add('rowgroup');
      $row->add('currency', array(
        'id' => 'creditor_'.$id,
        'name' => 'creditor['.$id.']',
        'labelClass' => 'g-input icon-money',
        'itemClass' => 'width',
        'label' => $displayname,
        'unit' => $currency_unit,
        'value' => isset($creditor[$id]) ? $creditor[$id] : 0
      ));
    }
    $group = $fieldset->add('groups');
    // create_date
    $group->add('date', array(
      'id' => 'create_date',
      'labelClass' => 'g-input icon-calendar',
      'itemClass' => 'width33',
      'label' => '{LNG_Transaction date}',
      'value' => date('Y-m-d', isset($index->create_date) ? $index->create_date : time())
    ));
    // period
    $group->add('select', array(
      'id' => 'period',
      'labelClass' => 'g-input icon-calendar',
      'itemClass' => 'width33',
      'label' => '{LNG_Period}',
      'options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12),
      'value' => isset($index->period) ? $index->period : 1
    ));
    // period_type
    $group->add('select', array(
      'id' => 'period_type',
      'labelClass' => 'g-input',
      'itemClass' => 'width33',
      'label' => '&nbsp;',
      'options' => array(1 => '{LNG_month}', 12 => '{LNG_year}'),
      'value' => isset($index->period_type) ? $index->period_type : 12
    ));
    $group = $fieldset->add('groups');
    // total
    $group->add('currency', array(
      'id' => 'total',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'width50',
      'label' => '{LNG_principal amount}',
      'unit' => $currency_unit,
      'readonly' => true,
      'value' => $total
    ));
    // interest
    $group->add('currency', array(
      'id' => 'interest',
      'labelClass' => 'g-input icon-money',
      'itemClass' => 'width50',
      'label' => '{LNG_Interest}',
      'unit' => '%',
      'value' => isset($index->interest) ? $index->interest : 0
    ));
    $group = $fieldset->add('groups', array(
      'comment' => '{LNG_The principal amount plus interest over the life of the contract (Printing contract)}',
    ));
    // include_interest
    $group->add('select', array(
      'id' => 'include_interest',
      'labelClass' => 'g-input icon-calculator',
      'itemClass' => 'width50',
      'label' => '{LNG_options}',
      'options' => Language::get('AGGREGATE_OPTIONS'),
      'value' => isset($index->include_interest) ? $index->include_interest : 1
    ));
    // aggregate
    $group->add('currency', array(
      'id' => 'aggregate',
      'labelClass' => 'g-input icon-money',
      'label' => '{LNG_Net amount}',
      'readonly' => empty($index->include_interest) ? false : true,
      'unit' => $currency_unit,
      'itemClass' => 'width50',
      'value' => isset($index->aggregate) ? $index->aggregate : 0
    ));
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_Other details}'
    ));
    // comment
    $fieldset->add('textarea', array(
      'id' => 'comment',
      'labelClass' => 'g-input icon-file',
      'itemClass' => 'item',
      'placeholder' => '{LNG_Detail}',
      'rows' => 5,
      'value' => isset($index->comment) ? $index->comment : ''
    ));
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button ok',
      'value' => '{LNG_Save}'
    ));
    // account
    $fieldset->add('button', array(
      'id' => 'account',
      'class' => 'button orange icon-report',
      'value' => '{LNG_Account details}'
    ));
    // template
    $fieldset->add('select', array(
      'id' => 'template',
      'options' => Language::get('AR_PRINT_TYPIES')
    ));
    // print
    $fieldset->add('a', array(
      'id' => 'print',
      'class' => 'button print icon-print',
      'innerHTML' => '{LNG_Print}'
    ));
    // id
    $fieldset->add('hidden', array(
      'id' => 'id',
      'value' => $index->id
    ));
    $form->script('initArDetail();');
    return $form->render();
  }
}
