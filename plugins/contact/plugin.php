<?php
namespace amuz\contact;

use XeFrontend;
use XePresenter;
use Route;
use Xpressengine\Http\Request;
use Xpressengine\Plugin\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        // implement code

        $this->route();
    }

    protected function route()
    {
        // implement code

        Route::fixed(
            $this->getId(),
            function () {
                Route::get(
                    '/',
                    [
                        'as' => 'contact::index',
                        'uses' => function (Request $request) {

                            $title = '상담관리1';

                            // set browser title
                            XeFrontend::title($title);

                            // load css file
                            XeFrontend::css($this->asset('assets/style.css'))->load();

                            // output
                            return XePresenter::make('contact::views.index', ['title' => $title]);

                        }
                    ]
                );
            }
        );

    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
        // implement code
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
//        문의가온 이후에 처리상황, 상담기록을 위한 테이블
//        Schema::create('contacts', function ($table) {
//            $table->engine = 'InnoDB';
//
//            $table->increments('id');
//            $table->string('document_id', 36)->comment('document ID/document의 FK')
//            $table->string('user_id', 36)->comment('상담 작성자 아이디/user 의 FK');
//
//            $table = $this->setColumns($table);
//
//            $table->primary('document_id');
//            $table->unique(['head', 'reply']);
//            $table->primary(array('id'));
//        });
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled()
    {
        // implement code

//        return Schema::hasTable('');
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @return void
     */
    public function update()
    {
        // implement code
    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated()
    {
        // implement code

        return parent::checkUpdated();
    }
}
