<?php
/**
 * @filesource modules/ar/controllers/creditorsreport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Creditorsreport;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Gcms\Login;
use \Kotchasan\Language;
use \Kotchasan\ArrayTool;

/**
 * module=ar-creditorsreport
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * รายงานสำหรับเจ้าหนี้
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // เจ้าหนี้
    $index = (object)array(
        'creditors' => \Ar\Detail\Model::getCreditors(),
    );
    // ค่าที่ส่งมา
    $index->u = $request->get('u', ArrayTool::getFirstKey($index->creditors))->toInt();
    // ข้อความ title bar
    $this->title = Language::trans('{LNG_Creditors report} ').$index->creditors[$index->u];
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
      $ul->appendChild('<li><span class="icon-office">{LNG_Report}</span></li>');
      $ul->appendChild('<li><a href="{BACKURL?module=ar-customer&id=0}">{LNG_Customer}</a></li>');
      $ul->appendChild('<li><span>{LNG_Creditors report}</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-report">'.$this->title.'</h2>'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Ar\Creditorsreport\View')->render($index));
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}
