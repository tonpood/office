<?php
/**
 * @filesource modules/ar/controllers/detail.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Detail;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Gcms\Login;
use \Kotchasan\Language;

/**
 * module=ar-detail
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * แสดงรายละเอียดของบัญชี
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // อ่านข้อมูลที่เลือก
    $index = \Ar\Detail\Model::get($request->request('id')->toInt());
    // ข้อความ title bar
    $title = $index && $index->id == 0 ? '{LNG_Add New}' : '{LNG_Details of}';
    $this->title = Language::trans($title.' {LNG_Customer}');
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
      $ul->appendChild('<li><a href="{BACKURL?module=ar-customer}">{LNG_Customer}</a></li>');
      $ul->appendChild('<li><span>'.$title.'</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-write">'.$this->title.'</h2>'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Ar\Detail\View')->render($index));
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}
