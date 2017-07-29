<?php
/**
 * @filesource modules/ar/controllers/export.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Export;

use \Kotchasan\Http\Request;

/**
 * Controller สำหรับการ Export หรือ Print
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * export.php
   *
   * @param Request $request
   */
  public function index(Request $request)
  {
    // session cookie
    $request->initSession();
    // แม่แบบที่ต้องการพิมพ์
    $template = $request->get('template')->toString();
    if ($template == 'transaction') {
      // อ่านข้อมูลการทำรายการ office และ รายละเอียดการทำรายการ
      $index = \Ar\Transaction\Model::all($request->get('id')->toInt(), true);
      if ($index) {
        $detail = createClass('Ar\Transactionexport\View')->render($index);
      }
    } elseif (preg_match('/^[a-z0-9]{3,}$/', $template)) {
      // อ่านข้อมูลที่ต้องการ
      $index = \Ar\Detailexport\Model::get($request->get('id')->toInt());
      if ($index) {
        $detail = createClass('Ar\Detailexport\View')->render($index, $template);
      }
    }
    if (empty($detail)) {
      // ไม่พบโมดูลหรือไม่มีสิทธิ
      new \Kotchasan\Http\NotFound();
    } else {
      // แสดงผล
      echo $detail;
    }
  }
}
