<?php
/**
 * @filesource modules/ar/views/transactionexport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Transactionexport;

use \Kotchasan\Currency;
use \Kotchasan\Language;
use \Kotchasan\Date;
use \Kotchasan\Template;

/**
 * module=ar-transactionexport
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{

  /**
   * รายละเอียดของบัญชี
   *
   * @param object $index
   * @return object
   */
  public function render($index)
  {
    // สกุลเงิน
    $currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    // title ของเอกสาร
    $topic = Language::get('Transaction details').' '.$index->name.' '.Language::get('date').' '.Date::format(time(), 'd M Y');
    // แสดงผลตาราง
    $content = array('<h1>'.$topic.'</h1>');
    $content[] = '<table>';
    $content[] = '<thead class=detail>';
    $content[] = '<tr>';
    $content[] = '<th>{LNG_Transaction date}</th>';
    $content[] = '<th class=center>{LNG_Total}</th>';
    $content[] = '</tr>';
    $content[] = '</thead>';
    $content[] = '<tbody class=detail>';
    foreach ($index->principle as $item) {
      $content[] = '<tr>';
      $content[] = '<th class=left>'.$item['create_date'].'</th>';
      $content[] = '<th class=right>'.Currency::format($item['amount']).' '.$currency_unit.'</th>';
      $content[] = '</tr>';
    }
    foreach ($index->interest_paid as $item) {
      $content[] = '<tr>';
      $content[] = '<td>'.$item['create_date'].'</td>';
      $content[] = '<td class=right>'.Currency::format($item['amount']).' '.$currency_unit.'</td>';
      $content[] = '</tr>';
    }
    $content[] = '</tbody>';
    $content[] = '<tfoot>';
    $content[] = '<tr>';
    $content[] = '<td class=right>ดอกเบี้ยชำระแล้ว</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right>ยอดเงินต้น</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_principle).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right>ยอดรวมดอกเบี้ยทั้งหมด</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right>ค้างชำระ</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest - $index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr class=due>';
    $content[] = '<td class=right>ยอดไถ่ถอน</td>';
    $content[] = '<td class="right total">'.Currency::format($index->total_principle + $index->total_interest - $index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '</tfoot>';
    $content[] = '</table>';
    // template
    $template = Template::createFromFile(ROOT_PATH.'modules/ar/template/index.html');
    // ใส่ลงใน Template
    $template->add(array(
      '/{LANGUAGE}/' => Language::name(),
      '/{WEBURL}/' => WEB_URL,
      '/{TOPIC}/' => $topic,
      '/{CONTENT}/' => implode('', $content),
      '/{LNG_([^}]+)}/e' => '\Kotchasan\Language::parse(array(1=>"$1"))',
    ));
    return $template->render();
  }
}
