<?php
/**
 * @filesource modules/ar/controllers/customer.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Customer;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Gcms\Login;
use \Kotchasan\Language;

/**
 * module=ar-customer
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * รายชื่อลูกค้า
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // ข้อความ title bar
    $this->title = Language::get('Customer list');
    // เลือกเมนู
    $this->menu = 'ar';
    // พนักงานบัญชี
    if (Login::checkPermission(Login::isMember(), 'accountant')) {
      // แสดงผล
      $section = Html::create('section');
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs'
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><span class="icon-office">{LNG_Account Receivable}</span></li>');
      $ul->appendChild('<li><span>{LNG_Customer}</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-report">'.$this->title.'</h2>'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Ar\Customer\View')->render());
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}
