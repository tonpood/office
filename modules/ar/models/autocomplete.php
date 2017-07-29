<?php
/**
 * @filesource modules/ar/models/autocomplete.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Autocomplete;

use \Kotchasan\Http\Request;
use \Gcms\Login;

/**
 * ค้นหาสมาชิก สำหรับ autocomplete
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * ค้นหารายชื่อลูกค้า สำหรับ autocomplete
   * คืนค่าเป็น JSON
   *
   * @param Request $request
   */
  public function findCustomer(Request $request)
  {
    if ($request->initSession() && $request->isReferer() && Login::isMember()) {
      $search = $request->post('name')->topic();
      $result = $this->db()->createQuery()
        ->select('id', 'name', 'sex', 'id_card', 'expire_date', 'address', 'phone', 'provinceID', 'zipcode')
        ->from('user')
        ->where(array(
          array('name', 'LIKE', "%$search%"),
          array('id_card', 'LIKE', "$search%"),
          array('phone', 'LIKE', "$search%"),
          ), 'OR')
        ->order('name')
        ->limit($request->post('count')->toInt())
        ->toArray()
        ->execute();
      // คืนค่า JSON
      if (!empty($result)) {
        echo json_encode($result);
      }
    }
  }
}
