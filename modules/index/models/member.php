<?php
/**
 * @filesource modules/index/models/member.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Member;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;

/**
 * ตารางสมาชิก
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
  protected $table = 'user U';

  /**
   * ฟังก์ชั่นอ่านจำนวนสมาชิกทั้งหมด
   *
   * @return int
   */
  public static function getCount()
  {
    $model = new \Kotchasan\Model;
    $query = $model->db()->createQuery()
      ->selectCount()
      ->from('user')
      ->toArray()
      ->execute();
    return $query[0]['count'];
  }

  /**
   * รับค่าจาก action
   *
   * @param Request $request
   */
  public function action(Request $request)
  {
    $ret = array();
    // session, referer, admin
    if ($request->initSession() && $request->isReferer() && $login = Login::isAdmin()) {
      if ($login['username'] != 'demo') {
        // รับค่าจากการ POST
        $action = $request->post('action')->toString();
        // id ที่ส่งมา
        if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
          // Model
          $model = new \Kotchasan\Model;
          // ตาราง user
          $user_table = $model->getTableName('user');
          if ($action === 'delete') {
            // ลบสมาชิก
            $model->db()->delete($user_table, array(
              array('id', $match[1]),
              array('id', '!=', 1)
              ), 0);
            // reload
            $ret['location'] = 'reload';
          } elseif ($action === 'sendpassword') {
            // ขอรหัสผ่านใหม่
            $query = $model->db()->createQuery()
              ->select('id', 'username')
              ->from('user')
              ->where(array(
                array('id', $match[1]),
                array('id', '!=', 1),
                array('username', '!=', '')
              ))
              ->toArray();
            $msgs = array();
            foreach ($query->execute() as $item) {
              // รหัสผ่านใหม่
              $password = \Kotchasan\Text::rndname(6);
              // ส่งอีเมล์ขอรหัสผ่านใหม่
              $err = \Index\Forgot\Model::execute($item['id'], $password, $item['username']);
              if ($err != '') {
                $msgs[] = $err;
              }
            }
            if (isset($password)) {
              if (empty($msgs)) {
                // ส่งอีเมล์ สำเร็จ
                $ret['alert'] = Language::get('Your message was sent successfully');
              } else {
                // มีข้อผิดพลาด
                $ret['alert'] = implode("\n", $msgs);
              }
            }
          }
        }
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่า JSON
    echo json_encode($ret);
  }
}
