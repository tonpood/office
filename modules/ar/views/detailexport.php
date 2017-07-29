<?php
/**
 * @filesource modules/ar/views/detailexport.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Ar\Detailexport;

use \Kotchasan\Template;
use \Kotchasan\Date;
use \Kotchasan\Province;
use \Kotchasan\Currency;
use \Kotchasan\Language;

/**
 * module=ar-detailexport
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{

  /**
   * พิมพ์สัญญา
   *
   * @param object $index ข้อมูลที่จะพิมพ์
   * @param string $source ชื่อสัญญาที่ต้องการพิมพ์
   * @return string
   */
  public function render($index, $source)
  {
    // template
    $template = Template::createFromFile(ROOT_PATH.'modules/ar/template/index.html');
    // วันครบกำหนด
    $enddate = mktime(23, 59, 59, date('m', $index->create_date) + ($index->period * $index->period_type), date('d', $index->create_date), date('Y', $index->create_date));
    // ใส่ลงใน Template
    $template->add(array(
      '/{LANGUAGE}/' => Language::name(),
      '/{WEBURL}/' => WEB_URL,
      '/{TOPIC}/' => Language::find('AR_PRINT_TYPIES', null, $source),
      '/{CONTENT}/' => file_get_contents(ROOT_PATH.'modules/ar/template/'.$source.'.html'),
      '/%AUTHORITY%/' => self::$cfg->authority,
      '/%AUTHORITYADDRESS%/' => self::$cfg->address,
      '/%AUTHORITYPROVINCE%/' => Province::get(self::$cfg->provinceID),
      '/%AUTHORITYPHONE%/' => self::$cfg->phone,
      '/%AUTHORITYIDCARD%/' => self::$cfg->idcard,
      '/%CREATEDATE%/' => Date::format($index->create_date, 'd M Y'),
      '/%CUSTOMER%/' => $index->name,
      '/%ADDRESS%/' => $index->address,
      '/%PROVINCE%/' => Province::get($index->provinceID),
      '/%PHONE%/' => $index->phone,
      '/%IDCARD%/' => $index->id_card,
      '/%AMOUNT%/' => Currency::format($index->aggregate),
      '/%THAIBAHT%/' => Currency::bahtThai($index->aggregate),
      '/%PROPERTY%/' => $index->detail,
      '/%D%/' => intval(date('d', $index->create_date)),
      '/%ENDDATE%/' => Date::format($enddate, 'd M Y'),
      '/%NAME2%/' => $index->name2,
      '/%ADDRESS2%/' => $index->address2,
      '/%PROVINCE2%/' => Province::get($index->province2),
      '/%PHONE2%/' => $index->phone2,
    ));
    return $template->render();
  }
}
