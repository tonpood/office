<?php
/**
 * @filesource modules/ar/views/monthlyreport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Monthlyreport;

use \Kotchasan\DataTable;
use \Kotchasan\Date;
use \Kotchasan\Currency;
use \Kotchasan\Language;

/**
 * module=ar-monthlyreport
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
  private $time;
  private $currency_unit;
  private $typies;
  private $index;

  /**
   * ตารางรายการ
   *
   * @param object $index
   * @return string
   */
  public function render($index)
  {
    $this->time = time();
    $this->currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    $this->typies = $index->AR_TYPIES;
    $years = array();
    for ($y = 2008; $y <= date('Y'); $y++) {
      $years[$y] = $y + $index->YEAR_OFFSET;
    }
    // query
    $this->index = \Ar\Monthlyreport\Model::get($index);
    // ตารางสมาชิก
    $table = new DataTable(array(
      /* Uri */
      'uri' => self::$request->createUriWithGlobals(WEB_URL.'index.php'),
      /* Model */
      'datas' => $this->index->datas,
      /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
      'onRow' => array($this, 'onRow'),
      /* คอลัมน์ที่ไม่ต้องแสดงผล */
      'hideColumns' => array('id'),
      /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
      'filters' => array(
        'month' => array(
          'name' => 'month',
          'text' => '{LNG_month}',
          'options' => Language::get('MONTH_LONG'),
          'value' => $this->index->month
        ),
        'year' => array(
          'name' => 'year',
          'text' => '{LNG_year}',
          'options' => $years,
          'value' => $this->index->year
        )
      ),
      /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
      'headers' => array(
        'create_date' => array(
          'text' => '{LNG_Transaction date}',
        ),
        'name' => array(
          'text' => '{LNG_Name}'
        ),
        'type' => array(
          'text' => ''
        ),
        'amount' => array(
          'text' => '{LNG_Total}',
          'class' => 'center',
        )
      ),
      /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
      'cols' => array(
        'last_transaction' => array(
          'class' => 'center date'
        ),
        'amount' => array(
          'class' => 'right'
        )
      ),
      'onCreateFooter' => array($this, 'onCreateFooter'),
    ));
    foreach ($index->creditors as $item) {
      $table->headers[$item] = array(
        'text' => $item,
        'class' => 'center',
      );
      $table->cols[$item] = array(
        'class' => 'right',
      );
    }
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
    $item['create_date'] = '<a href="index.php?module=ar-transaction&amp;id='.$item['id'].'">'.Date::format($item['create_date'], 'd M Y').'</a>';
    $item['name'] = '<a href="index.php?module=ar-detail&amp;id='.$item['id'].'">'.$item['name'].'</a>';
    $item['type'] = $this->typies[$item['type']];
    $item['amount'] = empty($item['amount']) ? '' : '<span class=nowrap>'.Currency::format($item['amount']).' '.$this->currency_unit.'</span>';
    return $item;
  }

  /**
   * footer
   */
  public function onCreateFooter()
  {
    $n = 0;
    $ret = '<tr><td class=right colspan=3>{LNG_Total}</td>';
    foreach ($this->index->summary as $name => $amount) {
      $n += $amount;
      $ret .= '<td class=right><span class=nowrap>'.($amount == 0 ? '' : Currency::format($amount).' '.$this->currency_unit).'</span></td>';
    }
    $ret .= '<td class=right><span class=nowrap>'.($n == 0 ? '' : Currency::format($n).' '.$this->currency_unit).'</span></td></tr>';
    return $ret;
  }
}
