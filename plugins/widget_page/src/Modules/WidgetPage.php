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
namespace Xpressengine\Plugins\WidgetPage\Modules;

use Xpressengine\Menu\AbstractModule;

/**
 * @category
 * @package     Xpressengine\Plugins\WidgetPage\Modules
 * @author      XE Team (developers) <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class WidgetPage extends AbstractModule
{

    /**
     * Return Create Form View
     *
     * @return mixed
     */
    public function createMenuForm()
    {
        return '';
    }

    /**
     * Process to Store
     *
     * @param string $instanceId     to store instance id
     * @param array  $menuTypeParams for menu type store param array
     * @param array  $itemParams     except menu type param array
     *
     * @return mixed
     * @internal param $inputs
     *
     */
    public function storeMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $handler = app('xe.widgetbox');
        $widgetboxPrefix = 'widgetpage-';
        $id = $widgetboxPrefix.$instanceId;

        $widgetbox = $handler->find($id);
        if($widgetbox === null) {
            $widgetbox = $handler->create(['id'=>$id, 'title'=>xe_trans($itemParams['title']), 'content'=> '']);
        }
    }

    /**
     * Return Edit Form View
     *
     * @param string $instanceId to edit instance id
     *
     * @return mixed
     */
    public function editMenuForm($instanceId)
    {
        return '';
    }

    /**
     * Process to Update
     *
     * @param string $instanceId     to update instance id
     * @param array  $menuTypeParams for menu type update param array
     * @param array  $itemParams     except menu type param array
     *
     * @return mixed
     * @internal param $inputs
     *
     */
    public function updateMenu($instanceId, $menuTypeParams, $itemParams)
    {
    }

    /**
     * displayed message when menu is deleted.
     *
     * @param string $instanceId to summary before deletion instance id
     *
     * @return string
     */
    public function summary($instanceId)
    {
    }

    /**
     * Process to delete
     *
     * @param string $instanceId to delete instance id
     *
     * @return mixed
     */
    public function deleteMenu($instanceId)
    {
        $handler = app('xe.widgetbox');
        $widgetboxPrefix = 'widgetpage-';
        $id = $widgetboxPrefix.$instanceId;

        $widgetbox = $handler->find($id);
        $handler->delete($widgetbox);
    }

    /**
     * Get menu type's item object
     *
     * @param string $id item id of menu type
     *
     * @return mixed
     */
    public function getTypeItem($id)
    {
    }}
