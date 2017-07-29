<?php
/**
 * @filesource modules/ar/models/detailexport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Detailexport;

use \Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับแสดงรายการลูกค้า (detail.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * อ่านข้อมูลสำหรับพิมพ์
   *
   * @param int $id
   * @return object|bool คืนค่าผลลัพท์ที่พบเพียงรายการเดียว ไม่พบข้อมูลคืนค่า false
   */
  public static function get($id)
  {
    // Model
    $model = new static;
    // ข้อมูลบัญชี
    $ar_details = $model->getTableName('ar_details');
    $sql1 = Sql::create("(SELECT MIN(`create_date`) FROM $ar_details WHERE `office_id`=O.`id` AND `type`='out') AS `create_date`");
    $sql2 = Sql::create("(SELECT SUM(`amount`) FROM $ar_details WHERE `office_id`=O.`id` AND `type`='out') AS `amount`");
    $sql3 = Sql::create("(SELECT MAX(`percent`) FROM $ar_details WHERE `office_id`=O.`id` AND `type`='out') AS `interest`");
    return $model->db()->createQuery()
        ->from('ar O')
        ->join('user U', 'INNER', array('U.id', 1))
        ->where(array('O.id', $id))
        ->first('O.*', $sql1, $sql2, $sql3, 'U.name name2', 'U.address address2', 'U.provinceID province2', 'U.phone phone2');
  }
}
