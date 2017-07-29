<?php
/**
 * @filesource modules/index/controllers/loader.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Loader;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Template;

/**
 * Controller สำหรับโหลดหน้าเว็บด้วย GLoader
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * มาจากการเรียกด้วย GLoader
   *
   * @param Request $request
   * @return JSON string
   */
  public function index(Request $request)
  {
    // session, referer
    if ($request->initSession() && $request->isReferer()) {
      // ตัวแปรป้องกันการเรียกหน้าเพจโดยตรง
      define('MAIN_INIT', 'indexhtml');
      // ตรวจสอบการ login
      Login::create();
      // กำหนด skin ให้กับ template
      Template::init(self::$cfg->skin);
      // View
      self::$view = new \Gcms\View;
      // โมดูลจาก URL ถ้าไม่มีใช้ default (home)
      $module = $request->post('module', 'home')->toString();
      if (preg_match('/^([a-z]+)([\/\-]([a-z]+))?$/i', $module, $match)) {
        if (empty($match[3])) {
          $owner = 'index';
          $module = $match[1];
        } else {
          $owner = $match[1];
          $module = $match[3];
        }
      } else {
        // หน้า default ถ้าไม่ระบุ module มา
        $owner = 'index';
        $module = 'home';
      }
      // ตรวจสอบหน้าที่เรียก
      if (is_file(APP_PATH.'modules/'.$owner.'/controllers/'.$module.'.php')) {
        // หน้าที่เรียก (Admin)
        include APP_PATH.'modules/'.$owner.'/controllers/'.$module.'.php';
        $className = ucfirst($owner).'\\'.ucfirst($module).'\Controller';
      } else {
        // ถ้าไม่พบหน้าที่เรียก แสดงหน้า 404
        include APP_PATH.'modules/index/controllers/error.php';
        $className = 'Index\Error\Controller';
      }
      $controller = new $className;
      // เนื้อหา
      self::$view->setContents(array(
        '/{CONTENT}/' => $controller->render($request)
      ));
      // output เป็น HTML
      $ret = array(
        'detail' => self::$view->renderHTML(Template::load('', '', 'loader')),
        'menu' => $controller->menu(),
        'topic' => $controller->title(),
        'to' => $request->post('to', 'scroll-to')->filter('a-z0-9_')
      );
      // คืนค่า JSON
      echo json_encode($ret);
    }
  }
}
