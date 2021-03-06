<?php
namespace Xpressengine\Plugins\Board\ToggleMenus\Shares;

use Xpressengine\ToggleMenu\AbstractToggleMenu;

class TwitterItem extends AbstractToggleMenu
{

    /**
     * 메뉴에서 보여질 문자열
     *
     * @return string
     */
    public function getText()
    {
        return xe_trans('board::twitter');
    }

    /**
     * 메뉴의 타입
     * 'exec' or 'link' or 'raw' 중에 하나
     *
     * @return string
     */
    public function getType()
    {
        return static::MENUTYPE_RAW;
    }

    /**
     * 실행되기 위한 js 문자열
     * 타입이 'raw' 인 경우에는 html
     *
     * @return string
     */
    public function getAction()
    {
        $url = 'https://twitter.com/intent/tweet?url=' . urlencode(app('request')->get('url'));
        return '<a href="#" class="share-item" data-url="'.$url.'" data-type="twitter"><i class="xi-twitter"></i>'
        .$this->getText().'</a>';
    }

    /**
     * 별도의 js 파일을 load 해야 하는 경우 해당 파일의 경로
     * 없는 경우 null 반환
     *
     * @return string|null
     */
    public function getScript()
    {
        return null;
    }
}
