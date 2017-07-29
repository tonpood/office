<?php
/**
 * @filesource modules/ar/views/customer.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Customer;

use \Kotchasan\DataTable;
use \Kotchasan\Date;
use \Kotchasan\Currency;
use \Kotchasan\Language;

/**
 * module=ar-customer
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
  private $time;
  private $currency_unit;

  /**
   * ตารางรายการลูกค้า
   *
   * @return string
   */
  public function render()
  {
    $this->time = time();
    $this->currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    // Uri
    $uri = self::$request->createUriWithGlobals(WEB_URL.'index.php');
    // ตาราง
    $table = new DataTable(array(
      /* Uri */
      'uri' => $uri,
      /* Model */
      'model' => 'Ar\Customer\Model',
      /* รายการต่อหน้า */
      'perPage' => self::$request->cookie('customer_perPage', 30)->toInt(),
      /* เรียงลำดับ */
      'sort' => self::$request->cookie('customer_sort', 'last_transaction desc')->toString(),
      /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
      'onRow' => array($this, 'onRow'),
      /* คอลัมน์ที่ไม่ต้องแสดงผล */
      'hideColumns' => array('id'),
      /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
      'action' => 'index.php/ar/model/customer/action',
      'actionCallback' => 'dataTableActionCallback',
      'actions' => array(
        array(
          'id' => 'action',
          'class' => 'ok',
          'text' => '{LNG_With selected}',
          'options' => array(
            'delete' => '{LNG_Delete}'
          )
        )
      ),
      /* คอลัมน์ที่สามารถค้นหาได้ */
      'searchColumns' => array('name', 'comment', 'detail'),
      /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
      'headers' => array(
        'name' => array(
          'text' => '{LNG_Name}',
          'sort' => 'name'
        ),
        'phone' => array(
          'text' => '{LNG_Phone}'
        ),
        'create_date' => array(
          'text' => '{LNG_Transaction date}',
          'class' => 'center',
          'sort' => 'create_date'
        ),
        'last_transaction' => array(
          'text' => '{LNG_Recent Transactions}',
          'class' => 'center',
          'sort' => 'last_transaction'
        ),
        'total' => array(
          'text' => '{LNG_Total amount}',
          'class' => 'center',
          'sort' => 'total'
        )
      ),
      /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
      'cols' => array(
        'create_date' => array(
          'class' => 'center date'
        ),
        'last_transaction' => array(
          'class' => 'center date'
        ),
        'total' => array(
          'class' => 'right'
        )
      ),
      /* ปุ่มแสดงในแต่ละแถว */
      'buttons' => array(
        'transaction' => array(
          'class' => 'icon-money button orange notext',
          'href' => $uri->createBackUri(array('module' => 'ar-transaction', 'id' => ':id')),
          'title' => '{LNG_Transaction details}'
        ),
        'detail' => array(
          'class' => 'icon-edit button green notext',
          'href' => $uri->createBackUri(array('module' => 'ar-detail', 'id' => ':id')),
          'title' => '{LNG_Account details}'
        )
      ),
      /* ปุ่มเพิ่ม */
      'addNew' => array(
        'class' => 'button green icon-plus',
        'href' => $uri->createBackUri(array('module' => 'ar-detail')),
        'text' => '{LNG_Add New} {LNG_Customer}'
      )
    ));
    // save cookie
    setcookie('customer_perPage', $table->perPage, time() + 3600 * 24 * 365, '/');
    setcookie('customer_sort', $table->sort, time() + 3600 * 24 * 365, '/');
    return $table->render();
  }

  /**
   * จัดรูปแบบการแสดงผลในแต่ละแถว
   *
   * @param array $item
   * @return array
   */
  public function onRow($item, $o, $prop)
  {
    $item['name'] = '<span class=nowrap>'.$item['name'].'</span>';
    $item['phone'] = self::showPhone($item['phone']);
    if ($item['create_date'] == 0) {
      $item['create_date'] = '-';
    } else {
      $diff = Date::compare($item['create_date'], $this->time);
      $item['create_date'] = Date::format($item['create_date'], 'd M Y').' ('.(($diff['year'] * 12) + $diff['month']).'&nbsp;{LNG_month}&nbsp;'.$diff['day'].'&nbsp{LNG_days})';
    }
    if ($item['last_transaction'] == 0) {
      $item['last_transaction'] = '-';
    } else {
      $diff = Date::compare($item['last_transaction'], $this->time);
      $item['last_transaction'] = Date::format($item['last_transaction'], 'd M Y').' ('.(($diff['year'] * 12) + $diff['month']).'&nbsp;{LNG_month}&nbsp;'.$diff['day'].'&nbsp{LNG_days})';
    }
    $item['total'] = empty($item['total']) ? '' : '<span class=nowrap>'.Currency::format($item['total']).' '.$this->currency_unit.'</span>';
    return $item;
  }
}
