<?php
/**
 * @filesource modules/ar/views/creditorsreport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Creditorsreport;

use \Kotchasan\DataTable;
use \Kotchasan\Date;
use \Kotchasan\Currency;
use \Kotchasan\Language;

/**
 * module=ar-creditorsreport
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
  private $total = 0;
  private $currency_unit;

  /**
   * ตารางรายการ
   *
   * @param object $index
   * @return string
   */
  public function render($index)
  {
    $this->currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    // ตารางสมาชิก
    $table = new DataTable(array(
      /* Uri */
      'uri' => self::$request->createUriWithGlobals(WEB_URL.'index.php'),
      /* datas */
      'datas' => \Ar\Creditorsreport\Model::get($index->u),
      /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
      'onRow' => array($this, 'onRow'),
      /* คอลัมน์ที่ไม่ต้องแสดงผล */
      'hideColumns' => array('id'),
      /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
      'filters' => array(
        'member_id' => array(
          'name' => 'u',
          'text' => '{LNG_Creditor}',
          'options' => $index->creditors,
          'value' => $index->u
        )
      ),
      /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
      'headers' => array(
        'create_date' => array(
          'text' => '{LNG_Transaction date}'
        ),
        'name' => array(
          'text' => '{LNG_Name}'
        ),
        'amount' => array(
          'text' => '{LNG_Total amount}',
          'class' => 'center',
        )
      ),
      /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
      'cols' => array(
        'amount' => array(
          'class' => 'right'
        )
      ),
      'onCreateFooter' => array($this, 'onCreateFooter'),
    ));
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
    $this->total += $item['amount'];
    $item['create_date'] = '<a href="index.php?module=ar-transaction&amp;id='.$item['id'].'">'.Date::format($item['create_date'], 'd M Y').'</a>';
    $item['name'] = '<a href="index.php?module=ar-detail&amp;id='.$item['id'].'">'.$item['name'].'</a>';
    $item['amount'] = empty($item['amount']) ? '' : '<span class=nowrap>'.Currency::format($item['amount']).' '.$this->currency_unit.'</span>';
    return $item;
  }

  /**
   * footer
   */
  public function onCreateFooter()
  {
    $ret = '<tr><td class=right colspan=2>{LNG_Total}</td>';
    $ret .= '<td class=right><span class=nowrap>'.($this->total == 0 ? '' : Currency::format($this->total).' '.$this->currency_unit).'</span></td>';
    $ret .= '<tr>';
    return $ret;
  }
}
