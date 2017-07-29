<?php
/**
 * @filesource modules/ar/models/creditorsreport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Creditorsreport;

use \Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับแสดงรายงานของเจ้าหนี้ (creditorsreport.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * query เจ้าหนี้ทั้งหมด
   *
   * @param int $id
   * @return array
   */
  public static function get($id)
  {
    $model = new static;
    return $model->db()->createQuery()
        ->select('C.create_date', 'O.name', Sql::SUM('C.amount', 'amount'), 'O.id')
        ->from('ar_details C')
        ->join('ar O', 'INNER', array('O.id', 'C.office_id'))
        ->where(array(
          array('C.type', 'out'),
          array('C.member_id', $id)
        ))
        ->groupBy('C.office_id')
        ->order('C.create_date')
        ->toArray()
        ->execute();
  }
}
