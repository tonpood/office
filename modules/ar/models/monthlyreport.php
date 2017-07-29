<?php
/**
 * @filesource modules/ar/models/monthlyreport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Monthlyreport;

use \Kotchasan\Currency;

/**
 * โมเดลสำหรับแสดงรายงานประจำเดือน (monthlyreport.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  public static function get($index)
  {
    // วันที่ 1
    $start = mktime(0, 0, 0, $index->month, 1, $index->year);
    // วันสิ้นเดือน
    $end = mktime(23, 59, 59, $index->month, date('t', $start), $index->year);
    // Model
    $model = new static;
    $query = $model->db()->createQuery()
      ->select('O.id', 'O.name', 'C.office_id', 'C.member_id', 'C.amount', 'C.create_date', 'C.type')
      ->from('ar_details C')
      ->join('ar O', 'INNER', array('O.id', 'C.office_id'))
      ->where(array(
        array('C.create_date', '>=', $start),
        array('C.create_date', '<=', $end),
        array('C.type', array('in', 'del'))
      ))
      ->order('C.create_date', 'C.member_id')
      ->toArray();
    $result = array();
    $summary = array();
    // เจ้าหนี้
    $index->creditors = \Ar\Detail\Model::getCreditors();
    foreach ($index->creditors as $k => $v) {
      $summary[$v] = 0;
    }
    foreach ($query->execute() as $item) {
      $key = $item['name'].$item['create_date'];
      if (!isset($result[$key])) {
        $result[$key] = array(
          'id' => $item['office_id'],
          'create_date' => $item['create_date'],
          'name' => $item['name'],
          'type' => $item['type']
        );
        foreach ($index->creditors as $k => $v) {
          $result[$key][$v] = '';
        }
        $result[$key]['amount'] = 0;
      }
      $summary[$index->creditors[$item['member_id']]] += $item['amount'];
      $result[$key][$index->creditors[$item['member_id']]] = empty($item['amount']) ? '' : Currency::format($item['amount']);
      $result[$key]['amount'] += $item['amount'];
    }
    $index->summary = $summary;
    $index->datas = $result;
    return $index;
  }
}
