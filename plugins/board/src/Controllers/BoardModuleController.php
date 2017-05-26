<?php
/**
 * BoardModuleController
 *
 * PHP version 5
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        https://xpressengine.io
 */
namespace Xpressengine\Plugins\Board\Controllers;

use XeDocument;
use XePresenter;
use XeFrontend;
use XeEditor;
use XeStorage;
use XeTag;
use Auth;
use Gate;
use Event;
use App\Http\Controllers\Controller;
use Xpressengine\Category\Models\Category;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Config\ConfigEntity;
use Xpressengine\Counter\Exceptions\GuestNotSupportException;
use Xpressengine\Document\Models\Document;
use Xpressengine\Http\Request;
use Xpressengine\Permission\Instance;
use Xpressengine\Plugins\Board\ConfigHandler;
use Xpressengine\Plugins\Board\Exceptions\CaptchaNotVerifiedException;
use Xpressengine\Plugins\Board\Exceptions\HaveNoWritePermissionHttpException;
use Xpressengine\Plugins\Board\Exceptions\NotFoundDocumentException;
use Xpressengine\Plugins\Board\Exceptions\NotMatchedCertifyKeyException;
use Xpressengine\Plugins\Board\Exceptions\SecretDocumentHttpException;
use Xpressengine\Plugins\Board\Handler;
use Xpressengine\Plugins\Board\IdentifyManager;
use Xpressengine\Plugins\Board\Models\Board;
use Xpressengine\Plugins\Board\Modules\BoardModule;
use Xpressengine\Plugins\Board\BoardPermissionHandler;
use Xpressengine\Plugins\Board\Models\BoardSlug;
use Xpressengine\Plugins\Board\Purifier;
use Xpressengine\Plugins\Board\Services\BoardService;
use Xpressengine\Plugins\Board\UrlHandler;
use Xpressengine\Plugins\Board\Validator;
use Xpressengine\Routing\InstanceConfig;
use Xpressengine\Support\Exceptions\AccessDeniedHttpException;
use Xpressengine\User\Models\User;
use Xpressengine\User\UserInterface;

/**
 * BoardModuleController
 *
 * 메뉴에서 게시판 추가할 때 추가된 게시판 관리
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        https://xpressengine.io
 */
class BoardModuleController extends Controller
{
    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var Handler
     */
    public $handler;

    /**
     * @var ConfigHandler
     */
    public $configHandler;

    /**
     * @var UrlHandler
     */
    public $urlHandler;

    /**
     * @var ConfigEntity
     */
    public $config;

    /**
     * @var bool
     */
    public $isManager = false;

    /**
     * constructor.
     *
     * @param Handler                $handler         board handler
     * @param ConfigHandler          $configHandler   board config handler
     * @param UrlHandler             $urlHandler      board url handler
     * @param BoardPermissionHandler $boardPermission board permission handler
     */
    public function __construct(
        Handler $handler,
        ConfigHandler $configHandler,
        UrlHandler $urlHandler,
        BoardPermissionHandler $boardPermission
    ) {
        $instanceConfig = InstanceConfig::instance();
        $this->instanceId = $instanceConfig->getInstanceId();

        $this->handler = $handler;
        $this->configHandler = $configHandler;
        $this->urlHandler = $urlHandler;

        $this->config = $configHandler->get($this->instanceId);
        if ($this->config !== null) {
            $urlHandler->setInstanceId($this->config->get('boardId'));
            $urlHandler->setConfig($this->config);

            $this->isManager = false;
            if (Gate::allows(
                BoardPermissionHandler::ACTION_MANAGE,
                new Instance($boardPermission->name($this->instanceId))
            )) {
                $this->isManager = true;
            };
        }

        // set Skin
        XePresenter::setSkinTargetId(BoardModule::getId());
        XePresenter::share('handler', $handler);
        XePresenter::share('configHandler', $configHandler);
        XePresenter::share('urlHandler', $urlHandler);
        XePresenter::share('isManager', $this->isManager);
        XePresenter::share('instanceId', $this->instanceId);
        XePresenter::share('config', $this->config);
    }

    /**
     * index page
     *
     * @param BoardService           $service         board service
     * @param Request                $request         request
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @return \Xpressengine\Presenter\RendererInterface
     * @throws AccessDeniedHttpException
     */
    public function index(BoardService $service, Request $request, BoardPermissionHandler $boardPermission)
    {
        if (Gate::denies(
            BoardPermissionHandler::ACTION_LIST,
            new Instance($boardPermission->name($this->instanceId))
        )) {
            throw new AccessDeniedHttpException;
        }

        $notices = $service->getNoticeItems($request, $this->config, Auth::user()->getId());
        $paginate = $service->getItems($request, $this->config);
        $fieldTypes = $service->getFieldTypes($this->config);
        $categories = $service->getCategoryItems($this->config);

        return XePresenter::makeAll('index', [
            'notices' => $notices,
            'paginate' => $paginate,
            'categories' => $categories,
            'fieldTypes' => $fieldTypes,
        ]);
    }

    /**
     * show
     *
     * @param BoardService           $service         board service
     * @param Request                $request         request
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @param string                 $menuUrl         first segment
     * @param string                 $id              document id
     * @return mixed
     */
    public function show(
        BoardService $service,
        Request $request,
        BoardPermissionHandler $boardPermission,
        $menuUrl,
        $id
    ) {
        if (Gate::denies(
            BoardPermissionHandler::ACTION_READ,
            new Instance($boardPermission->name($this->instanceId))
        )) {
            throw new AccessDeniedHttpException;
        }

        $item = $service->getItem($id, Auth::user(), $this->config, $this->isManager);

        // 글 조회수 증가
        if ($item->display == Board::DISPLAY_VISIBLE) {
            $this->handler->incrementReadCount($item, Auth::user());
        }

        $notices = $service->getNoticeItems($request, $this->config, Auth::user()->getId());
        $paginate = $service->getItems($request, $this->config, $id);
        $fieldTypes = $service->getFieldTypes($this->config);
        $categories = $service->getCategoryItems($this->config);

        return XePresenter::make('show', [
            'item' => $item,
            'notices' => $notices,
            'paginate' => $paginate,
            'categories' => $categories,
            'fieldTypes' => $fieldTypes,
        ]);
    }

    /**
     * show by slug
     *
     * @param BoardService           $service         board service
     * @param Request                $request         request
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @param string                 $menuUrl         first segment
     * @param string                 $strSlug         document slug
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function slug(
        BoardService $service,
        Request $request,
        BoardPermissionHandler $boardPermission,
        $menuUrl,
        $strSlug
    ) {
        $slug = BoardSlug::where('slug', $strSlug)->where('instanceId', $this->instanceId)->first();

        if ($slug === null) {
            throw new NotFoundDocumentException;
        }

        return $this->show($service, $request, $boardPermission, $menuUrl, $slug->targetId);
    }

    /**
     * create
     *
     * @param BoardService           $service         board service
     * @param Request                $request         request
     * @param Validator              $validator       validator
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @return mixed
     */
    public function create(
        BoardService $service,
        Request $request,
        Validator $validator,
        BoardPermissionHandler $boardPermission
    ) {
        if (Gate::denies(
            BoardPermissionHandler::ACTION_CREATE,
            new Instance($boardPermission->name($this->instanceId))
        )) {
            throw new AccessDeniedHttpException;
        }

        $categories = $service->getCategoryItems($this->config);
        $rules = $validator->getCreateRule(Auth::user(), $this->config);

        return XePresenter::makeAll('create', [
            'categories' => $categories,
            'rules' => $rules,
            'head' => '',
        ]);
    }

    /**
     * create
     *
     * @param BoardService           $service         board service
     * @param Request                $request         request
     * @param Validator              $validator       validator
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @param IdentifyManager        $identifyManager identify manager
     * @return mixed
     */
    public function store(
        BoardService $service,
        Request $request,
        Validator $validator,
        BoardPermissionHandler $boardPermission,
        IdentifyManager $identifyManager
    ) {
        if (Gate::denies(
            BoardPermissionHandler::ACTION_CREATE,
            new Instance($boardPermission->name($this->instanceId))
        )) {
            throw new AccessDeniedHttpException;
        }

        // 유표성 체크
        $this->validate($request, $validator->getCreateRule(Auth::user(), $this->config));

        // 공지 등록 권한 확인
        if ($request->get('status') == Board::STATUS_NOTICE && $this->isManager === false) {
            throw new HaveNoWritePermissionHttpException(['name' => xe_trans('xe::notice')]);
        }

        $item = $service->store($request, Auth::user(), $this->config, $identifyManager);

        return XePresenter::redirect()
            ->to($this->urlHandler->getShow($item, $request->query->all()))
            ->setData(['item' => $item]);
    }

    /**
     * 문자열을 넘겨 slug 반환
     *
     * @param Request $request request
     * @return mixed
     */
    public function hasSlug(Request $request)
    {
        $slugText = BoardSlug::convert('', $request->get('slug'));
        $slug = BoardSlug::make($slugText, $request->get('id'));

        return XePresenter::makeApi([
            'slug' => $slug,
        ]);
    }

    /**
     * edit
     *
     * @param BoardService    $service         board service
     * @param Request         $request         request
     * @param Validator       $validator       validator
     * @param IdentifyManager $identifyManager identify manager
     * @param string          $menuUrl         first segment
     * @param string          $id              document id
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function edit(
        BoardService $service,
        Request $request,
        Validator $validator,
        IdentifyManager $identifyManager,
        $menuUrl,
        $id
    ) {
        $item = Board::division($this->instanceId)->find($id);

        if ($item === null) {
            throw new NotFoundDocumentException;
        }

        // 비회원이 작성 한 글일 때 인증페이지로 이동
        if ($this->isManager !== true &&
            $item->isGuest() === true &&
            $identifyManager->identified($item) === false &&
            Auth::user()->getRating() != 'super') {
            return xeRedirect()->to($this->urlHandler->get('guest.id', [
                'id' => $item->id,
                'referrer' => app('url')->current(),
            ]));
        }

        // 접근 권한 확인
        if ($service->hasItemPerm($item, Auth::user(), $identifyManager, $this->isManager) == false) {
            throw new AccessDeniedHttpException;
        }

        $categories = $service->getCategoryItems($this->config);

        $rules = $validator->getEditRule(Auth::user(), $this->config);

        return XePresenter::make('edit', [
            'item' => $item,
            'categories' => $categories,
            'rules' => $rules,
            'parent' => null,
        ]);
    }

    /**
     * update
     *
     * @param BoardService    $service         board service
     * @param Request         $request         request
     * @param Validator       $validator       validator
     * @param IdentifyManager $identifyManager identify manager
     * @param string          $menuUrl         first segment
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function update(
        BoardService $service,
        Request $request,
        Validator $validator,
        IdentifyManager $identifyManager,
        $menuUrl
    ) {
        $item = Board::division($this->instanceId)->find($request->get('id'));

        // 비회원이 작성 한 글 인증
        if ($this->isManager !== true &&
            $item->isGuest() === true &&
            $identifyManager->identified($item) === false &&
            Auth::user()->getRating() != 'super') {
            return xeRedirect()->to($this->urlHandler->get('guest.id', [
                'id' => $item->id,
                'referrer' => $this->urlHandler->get('edit', ['id' => $item->id]),
            ]));
        }

        $this->validate($request, $validator->getEditRule(Auth::user(), $this->config));

        if ($service->hasItemPerm($item, Auth::user(), $identifyManager, $this->isManager) == false) {
            throw new AccessDeniedHttpException;
        }

        // 공지 등록 권한 확인
        if ($request->get('status') == Board::STATUS_NOTICE && $this->isManager === false) {
            throw new HaveNoWritePermissionHttpException(['name' => xe_trans('xe::notice')]);
        }

        $item = $service->update($item, $request, Auth::user(), $this->config, $identifyManager);

        return XePresenter::redirect()->to(
            $this->urlHandler->getShow(
                $item,
                $request->query->all()
            )
        )->setData(['item' => $item]);
    }

    /**
     * 비회원 인증 페이지
     *
     * @param Request   $request   request
     * @param Validator $validator validator
     * @param string    $menuUrl   first segment
     * @param string    $id        document id
     * @param string    $referrer  referrer url
     * @return mixed
     */
    public function guestId(Request $request, Validator $validator, $menuUrl, $id, $referrer = null)
    {
        $item = Board::division($this->instanceId)->find($id);

        // 레퍼러는 현재 url
        if ($referrer == null) {
            $referrer = app('url')->current();
        }

        if ($request->has('referrer')) {
            $referrer = $request->get('referrer');
        }

        return XePresenter::make('guestId', [
            'item' => $item,
            'referrer' => $referrer,
            'rules' => $validator->guestCertifyRule(),
        ]);
    }

    /**
     * 비회원 인증 처리
     *
     * @param Request         $request         request
     * @param IdentifyManager $identifyManager identify manager
     * @param Validator       $validator       validator
     * @param string          $menuUrl         first segment
     * @param string          $id              document id
     * @return mixed
     */
    public function guestCertify(
        Request $request,
        IdentifyManager $identifyManager,
        Validator $validator,
        $menuUrl,
        $id
    ) {
        $item = Board::division($this->instanceId)->find($id);

        $this->validate($request, $validator->guestCertifyRule());

        if ($identifyManager->verify($item, $request->get('email'), $request->get('certifyKey')) === false) {
            throw new NotMatchedCertifyKeyException;
        }

        // 인증 되었다면 DB의 인증키를 세션에 저장
        $identifyManager->create($item);

        return xeRedirect()->to($request->get('referrer', 'edit'));
    }

    /**
     * 미리보기
     *
     * @param Request                $request         request
     * @param Validator              $validator       validator
     * @param BoardPermissionHandler $boardPermission board permission handler
     * @return mixed
     */
    public function preview(Request $request, Validator $validator, BoardPermissionHandler $boardPermission)
    {
        if (Gate::denies(
            BoardPermissionHandler::ACTION_CREATE,
            new Instance($boardPermission->name($this->instanceId))
        )) {
            throw new AccessDeniedHttpException;
        }

        /** @var UserInterface $user */
        $user = Auth::user();

        // get rules
        $this->validate($request, $validator->getCreateRule($user, $this->config));

        $content = $request->originAll()['content'];
        $title = htmlspecialchars($request->originAll()['title'], ENT_COMPAT | ENT_HTML401, 'UTF-8', false);

        $writer = $user->getDisplayName();
        if ($request->get('writer', '') !== '') {
            $writer = $request->get('writer');
        }
        if ($this->config->get('anonymity') === true) {
            $writer = $this->config->get('anonymityName');
        }

        if ($request->get('categoryItemId', '') !== '') {

        }

        $showCategoryItem = null;
        if ($request->get('categoryItemId', '') !== '') {
            $showCategoryItem = CategoryItem::find($request->get('categoryItemId'));
        }

        /** @var \Xpressengine\Editor\AbstractEditor $editor */
        $editor = XeEditor::get($this->instanceId);
        $format = $editor->htmlable() ? Board::FORMAT_HTML : Board::FORMAT_NONE;

        return XePresenter::make('preview', [
            'config' => $this->config,
            'handler' => $this->handler,
            'currentDate' => date('Y-m-d H:i:s'),
            'title' => $title,
            'content' => $content,
            'writer' => $writer,
            'format' => $format,
            'showCategoryItem' => $showCategoryItem,
        ]);
    }

    /**
     * destroy
     *
     * @param BoardService    $service         board service
     * @param Request         $request         request
     * @param Validator       $validator       validator
     * @param IdentifyManager $identifyManager identify manager
     * @param string          $menuUrl         first segment
     * @param string          $id              document id
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function destroy(
        BoardService $service,
        Request $request,
        Validator $validator,
        IdentifyManager $identifyManager,
        $menuUrl,
        $id
    ) {
        /** @var Board $item */
        $item = Board::division($this->instanceId)->find($id);

        if ($item === null) {
            throw new NotFoundDocumentException;
        }

        // 비회원이 작성 한 글 인증
        if ($item->isGuest() === true &&
            $identifyManager->identified($item) === false &&
            Auth::user()->getRating() != 'super') {
            return xeRedirect()->to($this->urlHandler->get('guest.id', [
                'id' => $item->id,
                'referrer' => $this->urlHandler->get('show', ['id' => $item->id]),
            ]));
        }

        if ($service->hasItemPerm($item, Auth::user(), $identifyManager, $this->isManager) == false) {
            throw new AccessDeniedHttpException;
        }

        $service->destroy($item, $this->config, $identifyManager);

        return xeRedirect()->to(
            $this->urlHandler->get('index', $request->query->all())
        )->setData(['item' => $item]);
    }

    /**
     * trash
     *
     * @param BoardService $server  board service
     * @param Request      $request request
     * @return mixed
     */
    public function trash(BoardService $service, Request $request)
    {
        $user = Auth::user();
        $id = $request->get('id');

        $item = Board::division($this->instanceId)->find($id);

        if ($user->getRating() != 'super' && $user->getId() != $item->userId) {
            throw new AccessDeniedHttpException;
        }

        // use page resolver
        $items = $service->getItems($request, $this->config, $id);

        $this->handler->trash($item, $this->config);

        return xeRedirect()->to(
            $this->urlHandler->get('index', $request->query->all())
        )->setData([
            'item' => $item,
        ]);
    }

    /**
     * 즐겨찾기 등록, 삭제
     *
     * @param string $menuUrl first segment
     * @param string $id      document id
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function favorite($menuUrl, $id)
    {
        if (Auth::check() === false) {
            throw new AccessDeniedHttpException;
        }
        $item = Board::division($this->instanceId)->find($id);

        $userId = Auth::user()->getId();
        $favorite = false;
        if ($this->handler->hasFavorite($item->id, $userId) === false) {
            $this->handler->addFavorite($item->id, $userId);
            $favorite = true;
        } else {
            $this->handler->removeFavorite($item->id, $userId);
        }

        return XePresenter::makeApi(['favorite' => $favorite]);
    }

    /**
     * 투표 정보
     *
     * @param Request $request request
     * @param string  $id      document id
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function showVote(Request $request, $id)
    {
        // display 설정
        $display =['assent' => true, 'dissent' => true];
        if ($this->config->get('assent') !== true) {
            $display['assent'] = false;
        }

        if ($this->config->get('dissent') !== true) {
            $display['dissent'] = false;
        }

        $user = Auth::user();

        $item = Board::division($this->instanceId)->find($id);

        $voteCounter = $this->handler->getVoteCounter();
        $vote = $voteCounter->getByName($id, $user);

        return XePresenter::makeApi([
            'display' => $display,
            'id' => $id,
            'counts' => [
                'assent' => $item->assentCount,
                'dissent' => $item->dissentCount,
            ],
            'voteAt' => $vote['counterOption'],
        ]);
    }

    /**
     * 좋아요 추가, 삭제
     *
     * @param Request $request request
     * @param string  $menuUrl first segment
     * @param string  $option  options
     * @param string  $id      document id
     * @return \Xpressengine\Presenter\RendererInterface
     */
    public function vote(Request $request, $menuUrl, $option, $id)
    {
        $author = Auth::user();

        $item = Board::division($this->instanceId)->find($id);

        try {
            $this->handler->vote($item, $author, $option);
        } catch (GuestNotSupportException $e) {
            throw new AccessDeniedHttpException;
        }

        return $this->showVote($request, $id);
    }

    /**
     * get voted user list
     *
     * @param Request $request request
     * @param string  $menuUrl first segment
     * @param string  $option  options
     * @param string  $id      document id
     * @return mixed
     */
    public function votedUsers(Request $request, $menuUrl, $option, $id)
    {
        $limit = $request->get('limit', 10);

        $item = Board::division($this->instanceId)->find($id);

        $counter = $this->handler->getVoteCounter();
        $logModel = $counter->newModel();
        $logs = $logModel->where('counterName', $counter->getName())->where('targetId', $id)
            ->where('counterOption', $option)->take($limit)->get();

        return apiRender('votedUsers', [
            'urlHandler' => $this->urlHandler,
            'option' => $option,
            'item' => $item,
            'logs' => $logs,
        ]);
    }

    /**
     * get voted user modal
     *
     * @param Request $request request
     * @param string  $menuUrl first segment
     * @param string  $option  options
     * @param string  $id      document id
     * @return mixed
     */
    public function votedModal(Request $request, $menuUrl, $option, $id)
    {
        $item = Board::division($this->instanceId)->find($id);

        $counter = $this->handler->getVoteCounter();
        $logModel = $counter->newModel();
        $count = $logModel->where('counterName', $counter->getName())->where('targetId', $id)
            ->where('counterOption', $option)->count();

        return apiRender('votedModal', [
            'urlHandler' => $this->urlHandler,
            'option' => $option,
            'item' => $item,
            'count' => $count,
        ]);
    }

    /**
     * get voted user list
     *
     * @param Request $request request
     * @param string  $menuUrl first segment
     * @param string  $option  options
     * @param string  $id      document id
     * @return mixed
     */
    public function votedUserList(Request $request, $menuUrl, $option, $id)
    {
        $startId = $request->get('startId');
        $limit = $request->get('limit', 10);

        $item = Board::division($this->instanceId)->find($id);

        $counter = $this->handler->getVoteCounter();
        $logModel = $counter->newModel();
        $query = $logModel->where('counterName', $counter->getName())->where('targetId', $id)
            ->where('counterOption', $option);

        if ($startId != null) {
            $query->where('id', '<', $startId);
        }

        $logs = $query->orderBy('id', 'desc')->take($limit)->get();
        $list = [];
        foreach ($logs as $log) {
            /** @var User $user */
            $user = $log->user;
            $profilePage = '#';
            if ($user->getId() != '') {
                $profilePage = route('member.profile', ['member' => $user->getId()]);
            }
            $list[] = [
                'id' => $user->getId(),
                'displayName' => $user->getDisplayName(),
                'profileImage' => $user->getProfileImage(),
                'createdAt' => (string)$log->createdAt,
                'profilePage' => $profilePage,
            ];
        }

        $nextStartId = 0;
        if (count($logs) == $limit) {
            $nextStartId = $logs->last()->id;
        }

        return XePresenter::makeApi([
            'item' => $item,
            'list' => $list,
            'nextStartId' => $nextStartId,
        ]);
    }
}
