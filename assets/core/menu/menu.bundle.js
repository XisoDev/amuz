webpackJsonp([3],{214:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}var s=n(4),r=a(s),l=n(9),i=a(l),d=n(66),o=a(d);$(function(){var e=$("#menuContainer");i.default.render(r.default.createElement(o.default,{baseUrl:e.data("url"),home:e.data("home"),menus:e.data("menus"),menuRoutes:{createMenu:e.data("createmenu")}},null),e[0])})},338:function(e,t,n){n(214),n(62),n(63),n(67),n(68),n(64),n(65),e.exports=n(66)},62:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=n(4),s=function(e){return e&&e.__esModule?e:{default:e}}(a);t.default=s.default.createClass({displayName:"MenuEntity",propTypes:{index:s.default.PropTypes.object,onCollapse:s.default.PropTypes.func,getBaseUrl:s.default.PropTypes.func},handleCollapse:function(e){e.stopPropagation();var t=this.props.index.id;this.props.onCollapse&&this.props.onCollapse(t)},render:function(){var e=this.props.index,t=e.node,n=this.props.getBaseUrl()+"/menus/"+t.id,a=this.props.getBaseUrl()+"/menus/"+t.id+"/types",r={addItem:XE.Lang.trans("xe::addItem")},l="xe_tree_node_"+t.id;return s.default.createElement("div",{className:"panel-heading",id:l},s.default.createElement("div",{className:"pull-left"},s.default.createElement("a",{href:n},s.default.createElement("h3",null,s.default.createElement("i",{className:"xi-folder"}),t.title))),s.default.createElement("div",{className:"pull-right"},s.default.createElement("a",{href:a,className:"btn btn-primary"},s.default.createElement("i",{className:"xi-plus"}),s.default.createElement("span",null,r.addItem))))}})},63:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=n(4),r=a(s),l=n(9),i=a(l);t.default=r.default.createClass({displayName:"MenuItem",propTypes:{index:r.default.PropTypes.object,home:r.default.PropTypes.string,getBaseUrl:r.default.PropTypes.func,clickHome:r.default.PropTypes.func,mouseDown:r.default.PropTypes.func,getSelectedNode:r.default.PropTypes.func,setSelectedNode:r.default.PropTypes.func},onClickLink:function(e){"xpressengine@directLink"!==e.type?location.href="/"+e.url:location.href=e.url},componentDidUpdate:function(){var e=this.props.getSearchedNode(),t=this.props.index.node;e===t&&($("html, body").animate({scrollTop:$("#xe_tree_node_"+t.id).offset().top-20},500),this.props.setSearchedNode(null))},onClickHome:function(e){this.props.clickHome(e)},handleMouseDown:function(e){var t=this.props.index.id,n=i.default.findDOMNode(this.refs.inner);this.props.mouseDown(t,n,e)},onClickSetNode:function(e){this.props.getSelectedNode()===e?this.props.setSelectedNode(null):this.props.setSelectedNode(e)},render:function(){var e=this.props.index.node,t=e.title||"",n=e.type,a="",s={title:XE.Lang.trans(t),setHome:XE.Lang.trans("xe::setHome"),goLink:XE.Lang.trans("xe::goLink")};"xpressengine@directLink"!==e.type?(a=e.id==this.props.home?"/":"/"+e.url,a=Utils.getUri(xeBaseURL+a)):a=e.url;var l,i=this.props.getBaseUrl()+"/menus/"+e.menuId+"/items/"+e.id;l=e.id==this.props.home?r.default.createElement("button",{type:"button",className:"btn-link hidden-xs home-on"},r.default.createElement("i",{className:"xi-home"})):1===e.activated?r.default.createElement("button",{type:"button",className:"btn btn-link hidden-xs",onClick:this.onClickHome.bind(null,e)},r.default.createElement("i",{className:"xi-home"})):null;var d;this.props.getSelectedNode()==e&&(d=r.default.createElement("div",{className:"visible-xs more-area",style:{display:"block"}},r.default.createElement("button",{className:"btn",type:"button",onClick:this.onClickHome.bind(null,e)},s.setHome),r.default.createElement("a",{href:a,className:"btn"},s.goLink)));var o="xe_tree_node_"+e.id;return r.default.createElement("div",{className:"item-content",ref:"inner",id:o},r.default.createElement("button",{className:"btn handler",onMouseDown:this.handleMouseDown},r.default.createElement("i",{className:"xi-drag-vertical"})),r.default.createElement("div",{className:"item-info"},r.default.createElement("i",{className:"xi-paper"}),r.default.createElement("dl",null,r.default.createElement("dt",{className:"sr-only"},s.title),r.default.createElement("dd",{className:"ellipsis"},r.default.createElement("a",{href:i},s.title)),r.default.createElement("dt",{className:"sr-only"},a),r.default.createElement("dd",{className:"text-blue ellipsis"},r.default.createElement("a",{href:a},a),r.default.createElement("em",null,"[",n,"]")))),r.default.createElement("div",{className:"btn-group pull-right"},r.default.createElement("button",{type:"button",className:"btn-more visible-xs",onClick:this.onClickSetNode.bind(null,e)},r.default.createElement("i",{className:"xi-ellipsis-v"})),l),d)}})},64:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=n(4),r=a(s),l=n(9),i=a(l),d=n(65),o=a(d);t.default=r.default.createClass({displayName:"MenuSearchBar",propTypes:{tree:r.default.PropTypes.object,placeholder:r.default.PropTypes.string,handleSearch:r.default.PropTypes.func,menuRoutes:r.default.PropTypes.object},getDefaultProps:function(){return{placeholder:"Search...",tree:new Tree({})}},componentDidMount:function(){},getInitialState:function(){return{query:"",suggestions:[],selectedIndex:-1,selectionMode:!1,searchingCnt:0}},handleChange:function(e){var t=e.target.value.trim();this.setState({query:t}),this.searchMenu(t),0==t.length&&this.setState({suggestions:[],searchingCnt:0})},searchMenu:function(e){var t,n=this,a=this.state.searchingCnt+1,s=this.props.tree;this.setState({searchingCnt:a}),t=_.filter(s.indexes,function(t){if(0==t.id)return!1;var a=t.node.title;return n.isMenuEntity(t.node)||(a=XE.Lang.trans(t.node.title)),!(!a||-1===a.indexOf(e))}),a=this.state.searchingCnt,a-=1,n.setState({suggestions:t,searchingCnt:a})},isMenuEntity:function(e){return e.entity&&"menu"==e.entity},resetSearch:function(){var e=i.default.findDOMNode(this.refs.input);this.setState({query:"",selectedIndex:-1,selectionMode:!1,suggestions:[]}),e.value="",e.focus()},handleKeyDown:function(e){var t=this.state,n=t.query,a=t.selectedIndex,s=t.suggestions;e.keyCode===Keys.ESCAPE&&(e.preventDefault(),this.resetSearch()),e.keyCode!==Keys.ENTER&&e.keyCode!==Keys.TAB||""==n||(e.preventDefault(),this.state.selectionMode&&this.selection(this.state.suggestions[this.state.selectedIndex])),e.keyCode===Keys.UP_ARROW&&(e.preventDefault(),a<=0?this.setState({selectedIndex:this.state.suggestions.length-1,selectionMode:!0}):this.setState({selectedIndex:a-1,selectionMode:!0})),e.keyCode===Keys.DOWN_ARROW&&(e.preventDefault(),this.setState({selectedIndex:(this.state.selectedIndex+1)%s.length,selectionMode:!0}))},selection:function(e){var t=i.default.findDOMNode(this.refs.input);this.props.handleSearch(e.node),this.setState({query:"",selectionMode:!1,selectedIndex:-1}),t.value="",t.focus()},handleSuggestionClick:function(e,t){t.preventDefault(),this.selection(this.state.suggestions[e])},handleSuggestionHover:function(e,t){this.setState({selectedIndex:e,selectionMode:!0})},render:function(){var e=this.state.query.trim(),t=this.state.selectedIndex,n=this.state.suggestions,a=this.props.placeholder,s={addMenu:XE.Lang.trans("xe::addMenu")};return r.default.createElement("div",{className:"panel-heading"},r.default.createElement("div",{className:"pull-left"},r.default.createElement("div",{className:cx({"input-group":!0,"search-group":!0,open:e.length>0})},r.default.createElement("input",{type:"text",className:"form-control","aria-label":"Text input with dropdown button",placeholder:a,ref:"input",onChange:this.handleChange,onKeyDown:this.handleKeyDown}),r.default.createElement("button",{className:"btn-link",onClick:this.resetSearch},r.default.createElement("i",{className:"xi-magnifier"}),r.default.createElement("span",{className:"sr-only"},"검색")),r.default.createElement(o.default,{query:e,suggestions:n,selectedIndex:t,handleClick:this.handleSuggestionClick,handleHover:this.handleSuggestionHover}))),r.default.createElement("div",{className:"pull-right"},r.default.createElement("a",{href:this.props.menuRoutes.createMenu,className:"btn btn-primary pull-right"},r.default.createElement("i",{className:"xi-plus"})," ",s.addMenu)))}})},65:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=n(4),s=function(e){return e&&e.__esModule?e:{default:e}}(a);t.default=s.default.createClass({displayName:"MenuSearchSuggestion",propTypes:{query:s.default.PropTypes.string.isRequired,handleClick:s.default.PropTypes.func.isRequired,handleHover:s.default.PropTypes.func.isRequired,searchingCnt:s.default.PropTypes.number,suggestions:s.default.PropTypes.array,selectedIndex:s.default.PropTypes.number},markIt:function(e,t){var n=t.trim().replace(/[-\\^$*+?.()|[\]{}]/g,"\\$&"),a=RegExp(n,"gi"),s=e.node.title;return this.isMenuEntity(e.node)||(s=XE.Lang.trans(e.node.title)),{__html:s.replace(a,"<em>$&</em>")}},isMenuEntity:function(e){return e.entity&&"menu"==e.entity},render:function(){var e=this.props,t=this.props.suggestions.map(function(t,n){return s.default.createElement("li",{key:n,onClick:e.handleClick.bind(null,n),onMouseOver:e.handleHover.bind(null,n),className:cx({on:n==e.selectedIndex})},s.default.createElement("a",{href:"#",dangerouslySetInnerHTML:this.markIt(t,e.query)}))}.bind(this));return t&&0===t.length||e.query.length<2?s.default.createElement("div",{className:"search-list"}):s.default.createElement("div",{className:"search-list"},s.default.createElement("ul",null,t))}})},66:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=n(4),r=a(s),l=n(64),i=a(l),d=n(68),o=a(d);t.default=r.default.createClass({displayName:"MenuTree",getInitialState:function(){return{rawTree:this.props.menus,dataTree:new Tree({title:"root",items:this.props.menus}),selected:null,searched:null,home:this.props.home,menuRoutes:this.props.menuRoutes}},componentDidMount:function(){this.state.dataTree.movementFilter=this.movementFilter},getSearchedNode:function(){return this.state.searched},setSearchedNode:function(e){this.setState({searched:e})},getSelectedNode:function(){return this.state.selected},setSelectedNode:function(e){this.setState({selected:e})},movementFilter:function(e){var t=e.tree,n=t.get(e.toId),a=t.getIndex(e.toId),s=t.get(e.fromId);if(!this.isMenuEntity(s)){if(this.isMenuEntity(n)){if("after"==e.placement)return e.placement="prepend",e;if("before"==e.placement){if(a.prev&&null!=a.prev){var r=t.getIndex(a.prev);return e.toId=a.prev,r.collapsed||(e.placement="append"),e}return}return e}if("append"!=e.placement&&"prepend"!=e.placement||!(a.depth>MaxDepth))return e}},moveMenuItem:function(e){var t=this.props.baseUrl+"/moveItem",n=$("#uitree");XE.ajax({url:t,context:n,type:"put",dataType:"json",data:{itemId:e.id,parent:e.parent,ordering:e.position},success:function(e){XE.toast("success","Item moved")}.bind(this)})},getBaseUrl:function(){return this.props.baseUrl},onClickHome:function(e){var t=this.props.baseUrl+"/setHome",n=this.state.home,a=$("#uitree");this.setState({home:e.id}),XE.ajax({url:t,context:a,type:"put",dataType:"json",data:{itemId:e.id},success:function(t){XE.toast("success",e.title+" is home!")}.bind(this),error:function(e){XE.toast("error","home setting was failed!"),this.setState({home:n})}.bind(this)})},render:function(){return r.default.createElement("div",{className:"col-sm-12"},r.default.createElement("div",{className:"panel"},r.default.createElement(i.default,{tree:this.state.dataTree,handleSearch:this.setSearchedNode,menuRoutes:this.state.menuRoutes}),r.default.createElement("div",{className:"panel-body"},r.default.createElement(o.default,{paddingLeft:25,tree:this.state.dataTree,home:this.state.home,getBaseUrl:this.getBaseUrl,clickHome:this.onClickHome,getSearchedNode:this.getSearchedNode,setSearchedNode:this.setSearchedNode,getSelectedNode:this.getSelectedNode,setSelectedNode:this.setSelectedNode,moveNode:this.moveMenuItem}))))},isMenuEntity:function(e){return e.entity&&"menu"==e.entity}})},67:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=n(4),r=a(s),l=n(62),i=a(l),d=n(63),o=a(d),u=r.default.createClass({displayName:"TreeNode",propTypes:{index:r.default.PropTypes.object,tree:r.default.PropTypes.object,home:r.default.PropTypes.string,dragging:r.default.PropTypes.string,onDragStart:r.default.PropTypes.func,onCollapse:r.default.PropTypes.func,getBaseUrl:r.default.PropTypes.func,clickHome:r.default.PropTypes.func,getSelectedNode:r.default.PropTypes.func,setSelectedNode:r.default.PropTypes.func,getSearchedNode:r.default.PropTypes.func,setSearchedNode:r.default.PropTypes.func},renderChildren:function(){var e=this.props,t=e.index,n=e.tree,a=e.dragging,s=e.home,l=e.getBaseUrl,i=e.onDragStart,d=e.isDragging,o=t.id===a||this.props.isPlaceHolder;if(t.children&&t.children.length){var c={},p=t.children;if(t.collapsed){var f=e.getSearchedNode();null===f||t.id!=f.menuId?c.display="none":t.collapsed=!t.collapsed}var h=p.map(function(t){var c=n.getIndex(t);return r.default.createElement(u,{tree:n,index:c,key:c.id,dragging:a,home:s,onDragStart:i,isDragging:d,isPlaceHolder:o,clickHome:e.clickHome,getSelectedNode:e.getSelectedNode,setSelectedNode:e.setSelectedNode,getSearchedNode:e.getSearchedNode,setSearchedNode:e.setSearchedNode,getBaseUrl:l})});return r.default.createElement("div",{className:cx({"item-container":!0,move:d}),style:c},h)}return null},render:function(){var e=this.props,t=e.index,n=e.home,a=t.node,s=this.isPlaceHolder(e);return this.isMenuEntity(a)?r.default.createElement("div",{className:"menu-type"},r.default.createElement(i.default,{index:t,getBaseUrl:e.getBaseUrl,onCollapse:e.onCollapse}),this.renderChildren()):r.default.createElement("div",{className:cx({node:!0,item:!0,copy:s,off:1!==a.activated})},r.default.createElement(o.default,{index:t,home:n,getBaseUrl:e.getBaseUrl,clickHome:e.clickHome,mouseDown:this.handleMouseDown,getSelectedNode:e.getSelectedNode,setSelectedNode:e.setSelectedNode,getSearchedNode:e.getSearchedNode,setSearchedNode:e.setSearchedNode}),this.renderChildren())},handleMouseDown:function(e,t,n){this.props.onDragStart&&this.props.onDragStart(e,t,n)},isMenuEntity:function(e){return e.entity&&"menu"==e.entity},isPlaceHolder:function(e){var t=e.index,n=e.dragging;return t.id===n||e.isPlaceHolder}});t.default=u},68:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var s=n(4),r=a(s),l=n(67),i=a(l);t.default=r.default.createClass({displayName:"UITree",propTypes:{tree:r.default.PropTypes.object,home:r.default.PropTypes.string,paddingLeft:r.default.PropTypes.number,getBaseUrl:r.default.PropTypes.func,clickHome:r.default.PropTypes.func,getSelectedNode:r.default.PropTypes.func,setSelectedNode:r.default.PropTypes.func,getSearchedNode:r.default.PropTypes.func,setSearchedNode:r.default.PropTypes.func,moveNode:r.default.PropTypes.func},getDefaultProps:function(){return{paddingLeft:48}},getInitialState:function(){return this.init(this.props)},componentWillReceiveProps:function(e){this._update?this._update=!1:this.setState(this.init(e))},init:function(e){var t=e.tree;return t.updateNodesPosition(),{tempTree:null,tree:t,dragging:{id:null,x:null,y:null,w:null,h:null,originalParent:null,targetParent:null}}},getDraggingDom:function(){var e=this.state.tree,t=this.state.dragging,n=this.props.home;if(t&&t.id){var a=e.getIndex(t.id),s={top:t.y,left:t.x,width:t.w};return r.default.createElement("div",{className:"m-draggable move",style:s},r.default.createElement(i.default,{getBaseUrl:this.props.getBaseUrl,clickHome:this.props.clickHome,getSelectedNode:this.props.getSelectedNode,setSelectedNode:this.props.setSelectedNode,getSearchedNode:this.props.getSearchedNode,setSearchedNode:this.props.setSearchedNode,tree:e,home:n,index:a,key:t.id,isDragging:!0}))}return null},render:function(){var e=this.state.tree,t=this.state.dragging,n=this.getDraggingDom(),a=this,s=this.props.home,l=e.getIndex(0),d=l.children.map(function(n){return r.default.createElement(i.default,{tree:e,home:s,index:e.getIndex(n),getBaseUrl:a.props.getBaseUrl,clickHome:a.props.clickHome,getSelectedNode:a.props.getSelectedNode,setSelectedNode:a.props.setSelectedNode,getSearchedNode:a.props.getSearchedNode,setSearchedNode:a.props.setSearchedNode,key:n,onDragStart:a.dragStart,onCollapse:a.toggleCollapse,dragging:t&&t.id,isDragging:!1})});return r.default.createElement("div",{className:"menu-content",id:"uitree"},n,d)},dragStart:function(e,t,n){var a=this.state.tree,s=a.get(e);s.entity&&"menu"==s.entity||(this.setState({tempTree:new Tree(a.obj)}),this.dragging={id:e,w:t.offsetWidth,h:t.offsetHeight,x:t.offsetLeft,y:t.offsetTop,originalParent:a.get(e).parentId,targetParent:a.get(e).parentId,originalOrdering:a.get(e).ordering,lastOrdering:a.get(e).ordering},this._startX=t.offsetLeft,this._startY=t.offsetTop,this._offsetX=n.clientX,this._offsetY=n.clientY,this._start=!0,document.body.addEventListener("keydown",this.dragStop),document.body.addEventListener("mousemove",this.drag),document.body.addEventListener("mouseup",this.dragEnd))},drag:function(e){this._start&&(this.setState({dragging:this.dragging}),this._start=!1);var t=this.state.tree,n=this.state.dragging,a=this.props.paddingLeft,s=null,r=t.getIndex(n.id),l=r.collapsed,i=this._startX,d=this._startY,o=this._offsetX,u=this._offsetY,c={x:i+e.clientX-o,y:d+e.clientY-u};n.x=c.x,n.y=c.y;var p=n.x-a/2-(r.left-3)*a,f=n.y-n.h/2-(r.top-2)*n.h;if(2==r.depth&&p<0&&(p=0),p<0?r.parent&&!r.next&&(s=t.move(r.id,r.parent,"after")):p>a&&r.prev&&!t.getIndex(r.prev).collapsed&&(s=t.move(r.id,r.prev,"append")),s&&(r=s,s.collapsed=l,n.id=s.id),f<0){var h=t.getNodeByTop(r.top-1);s=t.move(r.id,h.id,"before")}else if(f>n.h){var m;r.next?(m=t.getIndex(r.next),s=m.children&&m.children.length&&!m.collapsed?t.move(r.id,r.next,"prepend"):t.move(r.id,r.next,"after")):(m=t.getNodeByTop(r.top+r.height))&&m.parent!==r.id&&(s=m.children&&m.children.length?t.move(r.id,m.id,"prepend"):t.move(r.id,m.id,"after"))}if(s){s.collapsed=l,n.id=s.id;var g=t.get(s.parent);n.targetParent=g.id,n.lastOrdering=s.ordering}this.setState({tree:t,dragging:n})},dragStop:function(e){var t={ENTER:13,TAB:9,BACKSPACE:8,UP_ARROW:38,DOWN_ARROW:40,ESCAPE:27};e.keyCode===t.ESCAPE&&e.preventDefault(),this.rollbackTree(),document.body.removeEventListener("mousemove",this.drag),document.body.removeEventListener("mouseup",this.dragEnd),document.body.removeEventListener("keydown",this.dragStop)},dragEnd:function(){this.setState({tempTree:null,dragging:{id:null,x:null,y:null,w:null,h:null,originalParent:null,targetParent:null}});var e=this.dragging;if(e.originalParent==e.targetParent){if(e.originalOrdering!=e.lastOrdering){var t=this.state.tree.get(e.id);"menu"!=t.entity&&this.props.moveNode({id:e.id,parent:e.targetParent,position:e.lastOrdering})}}else this.props.moveNode({id:e.id,parent:e.targetParent,position:e.lastOrdering});document.body.removeEventListener("mousemove",this.drag),document.body.removeEventListener("mouseup",this.dragEnd),document.body.removeEventListener("keydown",this.dragStop)},rollbackTree:function(){var e=this.state.tempTree;if(e){var t=this.props;t.tree=e,this.setState(this.init(t)),this.setState({tree:e,dragging:{id:null,x:null,y:null,w:null,h:null,originalParent:null,targetParent:null}})}},toggleCollapse:function(e){var t=this.state.tree,n=t.getIndex(e),a=t.get(e);n.collapsed=!n.collapsed,a.collapsed=!a.collapsed,t.updateNodesPosition(),this.setState({tree:t})}})}},[338]);