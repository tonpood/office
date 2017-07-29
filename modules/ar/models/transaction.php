<?php
/**
 * @filesource modules/ar/models/transaction.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Transaction;

use \Kotchasan\Date;
use \Gcms\Login;

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
   * อ่านข้อมูลการทำรายการ office ที่เลือก
   *
   * @param int $id ID ของ office
   * @param int $creditor_id เจ้าหนี้ (เฉพาะตอนบันทึก)
   * @return object
   */
  public static function get($id, $creditor_id = 0)
  {
    // Model
    $model = new static;
    // ตรวจสอบโมดูลและรายการที่เลือก
    $query = $model->db()->createQuery()
      ->from('ar O')
      ->where(array('O.id', $id));
    if ($creditor_id > 0) {
      $index = $query->join('user U', 'INNER', array('U.id', $creditor_id));
    }
    return $query->first('O.*');
  }

  /**
   * อ่านข้อมูลการทำรายการ office และ รายละเอียดการทำรายการ
   *
   * @param int $id
   * @param boolean $group false (default) แยกตามรายชื่อ สำหรับหน้าแสดงรายการ, true รวมวันที่เดียวกันเป็นรายการเดียว สำหรับหน้าพิมพ์
   * @return object
   */
  public static function all($id, $group = false)
  {
    // ตรวจสอบโมดูลและรายการที่เลือก
    $index = self::get($id);
    if ($index) {
      // Model
      $model = new static;
      // เจ้าหนี้
      $index->creditors = \Ar\Detail\Model::getCreditors();
      // รายละเอียดบัญชี
      $query = $model->db()->createQuery()
        ->select()
        ->from('ar_details')
        ->where(array('office_id', $index->id))
        ->order(array('create_date', 'member_id'))
        ->toArray();
      $datas = array();
      foreach ($query->execute() as $item) {
        // จัดกลุ่มข้อมูลเดียวกัน
        if ($group) {
          // สำหรับหน้าพิมพ์
          $key = $item['type'].$item['percent'].$item['create_date'];
        } else {
          // สำหรับหน้ารายงาน
          $key = $item['type'].$item['member_id'].$item['percent'].$item['create_date'];
        }
        if (isset($datas[$key])) {
          $datas[$key]['amount'] += $item['amount'];
        } else {
          $datas[$key] = $item;
        }
      }
      // ดอกเบี้ยชำระแล้ว
      $index->total_interest_paid = 0;
      // ดอกเบี้ย
      $index->interest_paid = array();
      // เงินต้นทั้งหมด
      $index->total_principle = 0;
      // เงินต้น
      $index->principle = array();
      // ดอกเบี้ยรายเดือน
      $index->total_percent = 0;
      // ดอกเบี้ยรวม
      $index->total_interest = 0;
      // เวลานี้
      $mktime = time();
      foreach ($datas as $item) {
        if ($item['type'] == 'in') {
          $index->total_interest_paid += $item['amount'];
          $item['create_date'] = Date::format($item['create_date'], 'd M Y');
          $index->interest_paid[] = $item;
        } else {
          $diff = Date::compare($item['create_date'], $mktime);
          $diff['month'] += $diff['year'] * 12;
          if ($diff['day'] >= self::$cfg->excess_interest2) {
            // เศษ 20 วัน คิด 1 เดือน
            $cm = $diff['month'] + (double)self::$cfg->multipier2;
          } elseif ($diff['day'] >= self::$cfg->excess_interest1) {
            // เศษ 5 วันขึ้นไปคิดครึ่งเดือน
            $cm = $diff['month'] + (double)self::$cfg->multipier1;
          } else {
            $cm = $diff['month'];
          }
          // เดือนแรกคิด 1 เดือน
          $cm = $cm < 1 ? 1 : $cm;
          $item['interest'] = ($item['amount'] * $item['percent']) / 100;
          // ดอกเบี้ยรายเดือน
          $index->total_percent += $item['interest'];
          $item['total'] = $item['interest'] * $cm;
          // ดอกเบี้ยรวม
          $index->total_interest += $item['total'];
          // มีการชำระยอดเงินต้น ยอดเงินที่ชำระจะติดลบ
          if ($item['type'] == 'del') {
            $item['amount'] = 0 - $item['amount'];
          }
          // ต้นรวม
          $index->total_principle += $item['amount'];
          $s = array();
          // วันแรกให้แสดงเป็น 1 วัน
          if ($diff['month'] == 0 && $diff['day'] == 0) {
            $diff['day'] = 1;
          }
          $s[] = $diff['month'] > 0 ? floor($diff['month']).' {LNG_month}' : '';
          $s[] = $diff['day'] > 0 ? "$diff[day] {LNG_days}" : '';
          $item['create_date'] = Date::format($item['create_date'], 'd M Y').' ('.implode(' ', $s).')';
          $index->principle[] = $item;
        }
      }
      return $index;
    }
    return null;
  }

  /**
   * action ของตาราง
   */
  public function action()
  {
    $ret = array();
    // referer, session, member
    if (self::$request->initSession() && self::$request->isReferer() && $login = Login::isMember()) {
      // รับค่าจากการ POST
      $id = self::$request->post('id')->toString();
      $action = self::$request->post('action')->toString();
      // Model
      $model = new static;
      if ($login['username'] != 'demo' && $action === 'delete' && preg_match('/^[0-9,]+$/', $id)) {
        // ลบรายการที่เลือก
        $model->db()->createQuery()->delete('ar_details', array('id', explode(',', $id)))->execute();
        // คืนค่า
        $ret['location'] = 'reload';
      } elseif (preg_match('/^add([0-9]+)$/', $action, $match)) {
        // ฟอร์มเพิ่ม transaction
        $ret['modal'] = \Ar\Addtransaction\View::render($match[1]);
      }
    }
    if (!empty($ret)) {
      // คืนค่าเป็น JSON
      echo json_encode($ret);
    }
  }
}
