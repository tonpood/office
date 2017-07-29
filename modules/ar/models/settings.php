<?php
/**
 * @filesource modules/ar/models/settings.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Settings;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;
use \Kotchasan\Config;

/**
 * บันทึกการตั้งค่าโมดูล
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{

  /**
   * module=ar-settings
   *
   * @param Request $request
   */
  public function submit(Request $request)
  {
    $ret = array();
    // session, token, can_config
    if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
      if ($login['username'] != 'demo' && Login::checkPermission($login, 'can_config')) {
        // โหลด config
        $config = Config::load(ROOT_PATH.'settings/config.php');
        // รับค่าจากการ POST
        $config->multipier1 = $request->post('multipier1')->toDouble();
        $config->multipier2 = $request->post('multipier2')->toDouble();
        $config->excess_interest1 = $request->post('excess_interest1')->toDouble();
        $config->excess_interest2 = $request->post('excess_interest2')->toDouble();
        $config->currency_unit = $request->post('currency_unit')->topic();
        // save config
        if (Config::save($config, ROOT_PATH.'settings/config.php')) {
          // คืนค่า
          $ret['alert'] = Language::get('Saved successfully');
          $ret['location'] = 'reload';
          // เคลียร์
          $request->removeToken();
        } else {
          // ไม่สามารถบันทึก config ได้
          $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
        }
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่าเป็น JSON
    echo json_encode($ret);
  }
}
