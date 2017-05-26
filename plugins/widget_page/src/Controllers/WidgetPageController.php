<?php
/**
 *  This file is part of the Xpressengine package.
 *
 * PHP version 5
 *
 * @category
 * @package     Xpressengine\
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\WidgetPage\Controllers;

use App\Http\Controllers\Controller;
use XePresenter;
use Xpressengine\Plugins\WidgetPage\Plugin;
use Xpressengine\Routing\InstanceConfig;
use Xpressengine\WidgetBox\WidgetBoxHandler;

/**
 * @category    WidgetPage
 * @package     Xpressengine\Plugins\WidgetPage\Controllers
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class WidgetPageController extends Controller
{
    /**
     * @var string $pageId page instance id
     */
    protected $pageId;

    public function __construct()
    {
        $instanceConfig = InstanceConfig::instance();
        $this->pageId = $instanceConfig->getInstanceId();
    }

    public function show(WidgetBoxHandler $handler)
    {
        $widgetboxPrefix = 'widgetpage-';
        $id = $widgetboxPrefix.$this->pageId;

        $widgetbox = $handler->find($id);
        return XePresenter::make(Plugin::view('views.show'), compact('widgetbox'));
    }
}
