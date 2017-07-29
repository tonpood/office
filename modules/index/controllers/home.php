<?php
/**
 * @filesource modules/index/controllers/home.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Home;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Kotchasan\Language;
use \Gcms\Login;

/**
 * module=home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * Dashboard
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // ข้อความ title bar
    $this->title = Language::get('Dashboard');
    // เลือกเมนู
    $this->menu = 'home';
    // แสดงผล
    $section = Html::create('section');
    // breadcrumbs
    $breadcrumbs = $section->add('div', array(
      'class' => 'breadcrumbs'
    ));
    $ul = $breadcrumbs->add('ul');
    $ul->appendChild('<li><span class="icon-home">{LNG_Home}</span></li>');
    $section->add('header', array(
      'innerHTML' => '<h2 class="icon-dashboard">'.$this->title.'</h2>'
    ));
    $dashboard = $section->add('div', array(
      'class' => 'dashboard'
    ));
    $card = $dashboard->add('div', array(
      'class' => 'ggrid row'
    ));
    // Login
    $login = Login::isMember();
    // โหลด Component หน้า Home
    $dir = ROOT_PATH.'modules/';
    $f = @opendir($dir);
    if ($f) {
      while (false !== ($text = readdir($f))) {
        if ($text != '.' && $text != '..' && $text != 'index' && $text != 'css' && $text != 'js' && is_dir($dir.$text)) {
          if (is_file($dir.$text.'/controllers/home.php')) {
            require_once $dir.$text.'/controllers/home.php';
            $className = '\\'.ucfirst($text).'\Home\Controller';
            if (method_exists($className, 'addCard')) {
              $className::addCard($request, $card, $login);
            }
          }
        }
      }
      closedir($f);
    }
    if (sizeof($card) < 4) {
      self::renderCard($card, 'icon-users', 'Users', \Index\Member\Model::getCount(), 'Member list', 'index.php?module=member');
    }
    return $section->render();
  }

  /**
   * ฟังก์ชั่นสร้าง card ในหน้า Home
   *
   * @param \Kotchasan\Html $card
   * @param string $icon
   * @param string $title
   * @param int $value
   * @param string $link
   * @param string $url
   */
  public static function renderCard($card, $icon, $title, $value, $link, $url)
  {
    $content = array('<div class="table fullwidth">');
    $content[] = '<div class="td '.$icon.' notext"></div>';
    $content[] = '<div class="td right">';
    $content[] = '<span class="cuttext">{LNG_'.$title.'}</span>';
    $content[] = '<h3>'.number_format($value).'</h3>';
    $content[] = '<a href="'.$url.'" class="cuttext">{LNG_'.$link.'}</a>';
    $content[] = '</div>';
    $content[] = '</div>';
    $card->add('section', array(
      'class' => 'card block3 float-left',
      'innerHTML' => implode('', $content)
    ));
  }
}
