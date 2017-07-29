<?php
/*
 * @filesource modules/ar/controllers/init.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Init;

use \Kotchasan\Http\Request;

/**
 * Init Module
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{

  /**
   * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
   * และจัดการเมนูของโมดูล
   *
   * @param Request $request
   * @param \Index\Menu\Controller $menu
   */
  public static function execute(Request $request, $menu, $login)
  {
    // repair module
    $menu->addTopLvlMenu('ar', '{LNG_Account Receivable}', null, array(
      array(
        'text' => '{LNG_Customer}',
        'url' => 'index.php?module=ar-customer'
      ),
      array(
        'text' => '{LNG_Add New} {LNG_Customer}',
        'url' => 'index.php?module=ar-detail'
      ),
      ), 'member');
    $menu->addTopLvlMenu('report', '{LNG_Report}', null, array(
      array(
        'text' => '{LNG_Monthly report}',
        'url' => 'index.php?module=ar-monthlyreport'
      ),
      array(
        'text' => '{LNG_Creditors report} {LNG_Customer}',
        'url' => 'index.php?module=ar-creditorsreport'
      ),
      ), 'member');
    // ตั้งค่าโมดูล
    $menu->add('settings', '{LNG_Creditor}', 'index.php?module=ar-creditor');
    $menu->add('settings', '{LNG_Settings} {LNG_Account Receivable}', 'index.php?module=ar-settings');
  }
}