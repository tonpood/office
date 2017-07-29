<?php
/**
 * @filesource modules/ar/views/transaction.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Transaction;

use \Kotchasan\Currency;
use \Kotchasan\Language;

/**
 * module=ar-transaction
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
   * @return string
   */
  public function render($index)
  {
    // สกุลเงิน
    $currency_unit = Language::find('CURRENCY_UNITS', null, self::$cfg->currency_unit);
    // แสดงผลตาราง
    $content = array();
    $content[] = '<div class=datatable id=transaction>';
    $content[] = '<div class=tablebody>';
    $content[] = '<table class=fullwidth>';
    $content[] = '<thead>';
    $content[] = '<tr><th colspan=7>{LNG_principal amount}</th></tr>';
    $content[] = '<tr>';
    $content[] = '<th class=check-column><a class="checkall icon-uncheck" title="{LNG_Select all}"></a></th>';
    $content[] = '<th>{LNG_Transaction date}</th>';
    $content[] = '<th class=center>{LNG_Creditor}</th>';
    $content[] = '<th class=center>{LNG_Interest} (%)</th>';
    $content[] = '<th class=center>{LNG_Total amount}</th>';
    $content[] = '<th class=center>{LNG_Interest} ({LNG_per month})</th>';
    $content[] = '<th class=center>{LNG_Total}</th>';
    $content[] = '</tr>';
    $content[] = '</thead>';
    $content[] = '<tbody>';
    foreach ($index->principle as $item) {
      $content[] = '<tr>';
      $content[] = '<td class=check-column><a id=check_'.$item['id'].' class=icon-uncheck title="{LNG_Choose}"></a></td>';
      $content[] = '<td><span class=nowrap>'.$item['create_date'].'</span></td>';
      $content[] = '<td class=center><span class=nowrap>'.$index->creditors[$item['member_id']].'</span></td>';
      $content[] = '<td class=center>'.Currency::format($item['percent']).'</td>';
      $content[] = '<td class=right><span class=nowrap>'.Currency::format($item['amount']).' '.$currency_unit.'</span></td>';
      $content[] = '<td class=right><span class=nowrap>'.Currency::format($item['interest']).' '.$currency_unit.'</span></td>';
      $content[] = '<td class=right><span class=nowrap>'.Currency::format($item['total']).' '.$currency_unit.'</span></td>';
      $content[] = '</tr>';
    }
    $content[] = '<tr class=tfoot>';
    $content[] = '<td class=right colspan=4>{LNG_Total}</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_principle).' '.$currency_unit.'</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_percent).' '.$currency_unit.'</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr class=thead><th colspan=7>{LNG_Payment}</th></tr>';
    $content[] = '<tr class=thead>';
    $content[] = '<th class=check-column></th>';
    $content[] = '<th>{LNG_Transaction date}</th>';
    $content[] = '<th class=center>{LNG_Creditor}</th>';
    $content[] = '<th colspan=3></th>';
    $content[] = '<th class=center>{LNG_Total amount}</th>';
    $content[] = '</tr>';
    foreach ($index->interest_paid as $item) {
      $content[] = '<tr>';
      $content[] = '<td class=check-column><a id=check_'.$item['id'].' class=icon-uncheck title="{LNG_Choose}"></a></td>';
      $content[] = '<td><span class=nowrap>'.$item['create_date'].'</span></td>';
      $content[] = '<td class=center><span class=nowrap>'.$index->creditors[$item['member_id']].'</span></td>';
      $content[] = '<td class=right colspan=4><span class=nowrap>'.Currency::format($item['amount']).' '.$currency_unit.'</span></td>';
      $content[] = '</tr>';
    }
    $content[] = '</tbody>';
    $content[] = '<tfoot>';
    $content[] = '<tr>';
    $content[] = '<td class=check-column><a class="checkall icon-uncheck" title="{LNG_Select all}"></a></td>';
    $content[] = '<td class=right colspan=5>{LNG_interest paid}</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right colspan=6>{LNG_principal amount}</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_principle).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right colspan=6>{LNG_total interest}</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr>';
    $content[] = '<td class=right colspan=6>{LNG_accrued interest expense}</td>';
    $content[] = '<td class=right>'.Currency::format($index->total_interest - $index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '<tr class=due>';
    $content[] = '<td class=right colspan=6>{LNG_redemption}</td>';
    $content[] = '<td class="right total">'.Currency::format($index->total_principle + $index->total_interest - $index->total_interest_paid).' '.$currency_unit.'</td>';
    $content[] = '</tr>';
    $content[] = '</tfoot>';
    $content[] = '</table>';
    $content[] = '</div>';
    $content[] = '<div class="table_nav action">';
    $content[] = '<fieldset>';
    $content[] = '<select id="action"><option value="delete">{LNG_Delete}</option></select>';
    $content[] = '<label for="action" class="button ok action"><span>{LNG_With selected}</span></label>';
    $content[] = '</fieldset>';
    $content[] = '<fieldset>';
    $content[] = '<a href="'.WEB_URL.'modules/ar/print.php?template=transaction&amp;id='.$index->id.'" class="button print action" target=print><span class=icon-print>{LNG_Print}</span></a>';
    $content[] = '</fieldset>';
    $content[] = '<fieldset>';
    $content[] = '<a id="add'.$index->id.'" class="button red action"><span class=icon-plus>{LNG_Additional items}</span></a>';
    $content[] = '</fieldset>';
    $content[] = '</div>';
    $content[] = '</div>';
    $content[] = '<script>';
    $content[] = 'var table = new GTable("transaction", {"action":"index.php\/ar\/model\/transaction\/action","actionCallback":"dataTableActionCallback","actionConfirm":"confirmAction"});';
    $content[] = '</script>';
    return implode("\n", $content);
  }
}
