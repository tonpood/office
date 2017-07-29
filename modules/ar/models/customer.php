<?php
/**
 * @filesource modules/ar/models/customer.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Customer;

use \Gcms\Login;
use \Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับแสดงรายการลูกค้า (customer.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Orm\Field
{
  /**
   * ชื่อตาราง
   *
   * @var string
   */
  protected $table = 'ar O';

  /**
   * ฟังก์ชั่นสำหรับเตรียม SQL ของ Model
   *
   * @return array
   */
  public function getConfig()
  {
    $ar_details = $this->getFullTableName('ar_details');
    $sql1 = Sql::create("(SELECT MIN(`create_date`) FROM $ar_details WHERE `office_id`=O.`id` AND `type`='out') AS `create_date`");
    $sql2 = Sql::create("(SELECT MAX(`create_date`) FROM $ar_details WHERE `office_id`=O.`id`) AS `last_transaction`");
    $sql3 = Sql::create("(SELECT SUM(`amount`) FROM $ar_details WHERE `office_id`=O.`id` AND `type`='out') AS `total`");
    return array(
      'select' => array('O.id', 'O.name', 'O.phone', $sql1, $sql2, $sql3)
    );
  }

  /**
   * action ของตาราง
   */
  public function action()
  {
    $ret = array();
    // session, referer, member
    if (self::$request->initSession() && self::$request->isReferer() && $login = Login::isMember()) {
      if ($login['username'] == 'demo') {
        $ret['alert'] = Language::get('Unable to complete the transaction');
      } elseif (Login::checkPermission($login, 'accountant')) {
        // รับค่าจากการ POST
        $id = self::$request->post('id')->toString();
        $action = self::$request->post('action')->toString();
        // Model
        $model = new \Kotchasan\Model;
        if ($action === 'delete' && preg_match('/^[0-9,]+$/', $id)) {
          // ลบรายการที่เลือก
          $id = explode(',', $id);
          $model->db()->createQuery()->delete('ar_details', array('office_id', $id))->execute();
          $model->db()->createQuery()->delete('ar', array('id', $id))->execute();
          // คืนค่า
          $ret['location'] = 'reload';
        }
      }
    }
    if (!empty($ret)) {
      // คืนค่าเป็น JSON
      echo json_encode($ret);
    }
  }
}
