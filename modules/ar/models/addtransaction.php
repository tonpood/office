<?php
/**
 * @filesource modules/ar/models/addtransaction.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Addtransaction;

use \Kotchasan\Http\Request;
use \Kotchasan\Language;
use \Gcms\Login;
use \Kotchasan\Date;

/**
 * โมเดลสำหรับแสดงรายละเอียดของลูกค้า (transaction.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * module=ar-addtransaction
   *
   * @param Request $request
   */
  public function submit(Request $request)
  {
    $ret = array();
    // session, token, member
    if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
      if ($login['username'] == 'demo') {
        $ret['alert'] = Language::get('Unable to complete the transaction');
      } else {
        // POST
        $save = array(
          'type' => $request->post('type')->topic(),
          'member_id' => $request->post('member_id')->toInt(),
          'amount' => $request->post('amount')->toDouble(),
          'percent' => $request->post('percent')->toDouble(),
          'detail' => $request->post('detail')->topic(),
          'create_date' => Date::sqlDateTimeToMktime($request->post('create_date')->date())
        );
        // ตรวจสอบรายการที่เลือก
        $index = \Ar\Transaction\Model::get($request->post('office_id')->toInt(), $save['member_id']);
        if ($index) {
          if ($save['amount'] == 0) {
            $ret['ret_amount'] = 'Please fill in';
          } else {
            // บันทึก
            $save['office_id'] = $index->id;
            // ชื่อตาราง
            $table_name = $this->getTableName('ar_details');
            // บันทึก
            $this->db()->insert($table_name, $save);
            if ($save['type'] == 'out') {
              $save['detail'] = '';
              $save['amount'] = ($save['amount'] * $save['percent']) / 100;
              $save['type'] = 'in';
              $this->db()->insert($table_name, $save);
            }
            // ส่งค่ากลับ
            $ret['alert'] = Language::get('Saved successfully');
            $ret['modal'] = 'close';
            $ret['location'] = 'reload';
          }
        }
      }
    } else {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่าเป็น JSON
    echo json_encode($ret);
  }
}
