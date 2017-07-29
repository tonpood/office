<?php
/**
 * @filesource Gcms/Config.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcms;

/**
 * Config Class สำหรับ GCMS
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Config extends \Kotchasan\Config
{
  /**
   * รายชื่อฟิลด์จากตารางสมาชิก สำหรับตรวจสอบการ login
   *
   * @var array
   */
  public $login_fields = array('username');
  /**
   * สถานะสมาชิก
   * 0 สมาชิกทั่วไป
   * 1 ผู้ดูแลระบบ
   *
   * @var array
   */
  public $member_status = array(
    0 => 'สมาชิก',
    1 => 'ผู้ดูแลระบบ',
  );
  /**
   * สีของสมาชิกตามสถานะ
   *
   * @var array
   */
  public $color_status = array(
    0 => '#259B24',
    1 => '#FF0000',
  );
  /**
   * กำหนดอายุของแคช (วินาที)
   * 0 หมายถึงไม่มีการใช้งานแคช
   *
   * @var int
   */
  public $cache_expire = 10;
  /**
   * admin template
   *
   * @var string
   */
  public $skin = 'default';
  /*
   * คีย์สำหรับการเข้ารหัส
   *
   * @var string
   */
  public $password_key = '1234567890';
  /**
   * ถ้ากำหนดเป็น true บัญชี demo จะสามารถเข้าระบบได้
   *
   * @var boolean default false
   */
  public $demo_mode = false;
}
