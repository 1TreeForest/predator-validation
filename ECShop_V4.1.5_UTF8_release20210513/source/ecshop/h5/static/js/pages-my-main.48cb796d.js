(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-main"],{"0bd8":function(t,i,e){"use strict";e.r(i);var n=e("c950"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=a.a},"11b3":function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={data:function(){return{typeList:{left:"icon-zuo",right:"icon-you",up:"icon-shang",down:"icon-xia"}}},props:{icon:{type:String,default:""},title:{type:String,default:"标题"},tips:{type:String,default:""},navigateType:{type:String,default:"right"},border:{type:String,default:"b-b"},hoverClass:{type:String,default:"cell-hover"},iconColor:{type:String,default:"#333"}},methods:{eventClick:function(){this.$emit("eventClick")}}};i.default=n},"16be":function(t,i,e){"use strict";function n(t,i,e){var n=plus.screen.resolutionWidth,a=25,o=25,s=55,r=55,c=10,l=12,u=Math.floor(n/(s+o)),d=(n-(s+o)*u-o)/(u+1),v=o+d,f=s+v,b=r+c+l+a,p=r+c,h=new plus.nativeObj.View("alphaBg",{top:"0px",left:"0px",height:"100%",width:"100%",backgroundColor:"rgba(0,0,0,0.5)"});h.addEventListener("click",(function(){h.hide(),g.hide()}));var g=new plus.nativeObj.View("shareMenu",{bottom:"0px",left:"0px",height:Math.ceil(i.length/u)*b+a+44+1+"px",width:"100%",backgroundColor:"rgb(255,255,255)"});return g.draw([{tag:"rect",color:"#e7e7e7",position:{top:"0px",height:"1px"}},{tag:"font",id:"sharecancel",text:"取消分享",textStyles:{size:"14px"},position:{bottom:"0px",height:"44px"}},{tag:"rect",color:"#e7e7e7",position:{bottom:"45px",height:"1px"}}]),i.map((function(t,i){var e=(new Date).getTime(),n=Math.floor(i/u),o=i%u,r=[{src:t.icon,id:1e3*Math.random()+e,tag:"img",position:{top:n*b+a,left:o*f+v,width:s,height:s}},{text:t.text,id:1e3*Math.random()+e,tag:"font",textStyles:{size:l},position:{top:n*b+p,left:o*f+v,width:s,height:s}}];g.draw(r)})),g.addEventListener("click",(function(t){if(t.screenY>plus.screen.resolutionHeight-44)h.hide(),g.hide();else if(t.clientX<5||t.clientX>n-5||t.clientY<5);else{var i=t.clientX,a=t.clientY,o=Math.floor(i/f),s=Math.floor(a/b),r=o+s*u;e&&e(r)}})),{alphaBg:h,shareMenu:g}}e("d81d"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var a=n;i.default=a},"22ce":function(t,i,e){"use strict";var n=e("689f"),a=e.n(n);a.a},"2a3b":function(t,i,e){"use strict";e.r(i);var n=e("301d"),a=e("c510");for(var o in a)"default"!==o&&function(t){e.d(i,t,(function(){return a[t]}))}(o);e("22ce");var s,r=e("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"8b677bd4",null,!1,n["a"],s);i["default"]=c.exports},"301d":function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",[e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"user-section"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"https://imgt1.oss-cn-shanghai.aliyuncs.com/ecAllRes/images/user-bg.png"}}),e("v-uni-view",{staticClass:"user-info-box",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/memberinfo/main")}}},[e("v-uni-view",{staticClass:"portrait-box"},[e("v-uni-image",{staticClass:"portrait",attrs:{src:t.avator}})],1),e("v-uni-view",{staticClass:"info-box"},[e("v-uni-text",{staticClass:"username"},[t._v(t._s(t.userInfo.nickName||"请登录"))])],1)],1),e("v-uni-view",{staticClass:"vip-card-box"},[e("v-uni-image",{staticClass:"card-bg",attrs:{src:"https://imgt1.oss-cn-shanghai.aliyuncs.com/ecAllRes/images/vip-card-bg.png",mode:""}}),"formallv"===t.statuslv?e("v-uni-view",{staticClass:"b-btn"},[t._v("查看信息")]):t._e(),e("v-uni-view",{staticClass:"tit"},[e("v-uni-text",{staticClass:"yticon icon-iLinkapp-"}),t._v(t._s(t.rank_name.rank_name))],1),e("v-uni-text",{staticClass:"e-m"},[t._v("等级优惠")]),e("v-uni-text",{staticClass:"e-b"},[t._v("当前等级享受："+t._s(t.rank_name.discount)+" 折的购物优惠")])],1)],1),e("v-uni-view",{staticClass:"cover-container",style:[{transform:t.coverTransform,transition:t.coverTransition}],on:{touchstart:function(i){arguments[0]=i=t.$handleEvent(i),t.coverTouchstart.apply(void 0,arguments)},touchmove:function(i){arguments[0]=i=t.$handleEvent(i),t.coverTouchmove.apply(void 0,arguments)},touchend:function(i){arguments[0]=i=t.$handleEvent(i),t.coverTouchend.apply(void 0,arguments)}}},[e("v-uni-image",{staticClass:"arc",attrs:{src:"https://imgt1.oss-cn-shanghai.aliyuncs.com/ecAllRes/images/arc.png"}}),e("v-uni-view",{staticClass:"tj-sction"},[e("v-uni-view",{staticClass:"tj-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/advancelist/main")}}},[e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.advance))]),e("v-uni-text",[t._v("余额")])],1),e("v-uni-view",{staticClass:"tj-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/coupon/main")}}},[e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.couponNum))]),e("v-uni-text",[t._v("红包")])],1),e("v-uni-view",{staticClass:"tj-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/point/main")}}},[e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.point))]),e("v-uni-text",[t._v("积分")])],1)],1),e("v-uni-view",{staticClass:"all-line"},[e("v-uni-view",{staticClass:"name"},[t._v("我的订单")]),e("v-uni-view",{staticClass:"all",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=0")}}},[t._v("查看全部订单>")])],1),e("v-uni-view",{staticClass:"order-section"},[e("v-uni-view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=1")}}},[void 0==t.waitPayNum?e("div",{staticClass:"faker"},[t._v("0")]):t._e(),void 0!=t.waitPayNum&&t.waitPayNum<=99?e("div",{staticClass:"sup"},[t._v(t._s(t.waitPayNum))]):t._e(),t.waitPayNum>99?e("div",{staticClass:"sup"},[t._v(t._s("99+"))]):t._e(),e("v-uni-text",{staticClass:"yticon icon-dengdaifukuan"}),e("v-uni-text",[t._v("待付款")])],1),e("v-uni-view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=2")}}},[void 0==t.waitFhNum?e("div",{staticClass:"faker"},[t._v("0")]):t._e(),void 0!=t.waitFhNum?e("div",{staticClass:"sup"},[t._v(t._s(t.waitFhNum))]):t._e(),e("v-uni-text",{staticClass:"yticon icon-daishouhuo"}),e("v-uni-text",[t._v("待发货")])],1),e("v-uni-view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=3")}}},[void 0==t.waitRecNum?e("div",{staticClass:"faker"},[t._v("0")]):t._e(),void 0!=t.waitRecNum?e("div",{staticClass:"sup"},[t._v(t._s(t.waitRecNum))]):t._e(),e("v-uni-text",{staticClass:"yticon icon-daishouhuo"}),e("v-uni-text",[t._v("待收货")])],1),e("v-uni-view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=4")}}},[e("div",{staticClass:"faker"}),e("v-uni-text",{staticClass:"yticon icon-tianjiapinglun"}),e("v-uni-text",[t._v("待评价")])],1),e("v-uni-view",{staticClass:"order-item",attrs:{"hover-class":"common-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/orderlist/main?status=5")}}},[0==t.sup.d?e("div",{staticClass:"faker"},[t._v("0")]):t._e(),0!=t.sup.d?e("div",{staticClass:"sup"},[t._v(t._s(t.sup.d))]):t._e(),e("v-uni-text",{staticClass:"yticon icon-refund"}),e("v-uni-text",[t._v("退款/售后")])],1)],1),e("v-uni-view",{staticClass:"history-section icon"},[e("v-uni-view",{staticClass:"sec-header"},[e("v-uni-text",{staticClass:"yticon icon-lishi"}),e("v-uni-text",[t._v("浏览历史")])],1),e("v-uni-scroll-view",{staticClass:"h-list",attrs:{"scroll-x":!0}},t._l(t.historyGoods,(function(i,n){return e("v-uni-image",{key:n,attrs:{src:i.img,mode:"aspectFill"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goodsDetail(i.id)}}})})),1),e("list-cell",{attrs:{icon:"icon-qianbao",iconColor:"#e07472",title:"我的钱包"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/deposit/main")}}}),e("list-cell",{attrs:{icon:"icon-qianbao",iconColor:"#e07472",title:"超值礼包"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiShop/superpackage/main")}}}),e("list-cell",{attrs:{icon:"icon-libao",iconColor:"#e07472",title:"红包激活",border:""},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/bonus/main")}}}),e("list-cell",{attrs:{icon:"icon-dizhi",iconColor:"#5fcda2",title:"地址管理"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiCart/address/main")}}}),1==t.my_subordinates?e("list-cell",{attrs:{icon:"icon-fenxiaodingdanbiaoshi",iconColor:"#54b4ef",title:"我的下级"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/agent/main")}}}):t._e(),e("list-cell",{attrs:{icon:"icon-fenxiang",iconColor:"#9789f7",title:"分享",tips:"邀请好友"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/promote/main")}}}),e("list-cell",{attrs:{icon:"icon-shoucang",iconColor:"#54b4ef",title:"我的收藏"},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/collectlist/main")}}}),e("v-uni-button",{staticClass:"btn shadow",attrs:{"data-target":"Modal"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.showModal.apply(void 0,arguments)}}},[e("list-cell",{attrs:{icon:"icon-shouye",iconColor:"#ee883b",title:"联系客服"}})],1),e("list-cell",{attrs:{icon:"icon-shezhi_",iconColor:"#e07472",title:"设置",border:""},on:{eventClick:function(i){arguments[0]=i=t.$handleEvent(i),t.navTo("/apiMember/set/main")}}})],1)],1),e("v-uni-view",{staticClass:"cu-modal",class:"Modal"==t.modalName?"show":""},[e("v-uni-view",{staticClass:"cu-dialog"},[e("v-uni-view",{staticClass:"cu-bar bg-white justify-end"},[e("v-uni-view",{staticClass:"content"},[t._v("客服电话")]),e("v-uni-view",{staticClass:"action",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.hideModal.apply(void 0,arguments)}}},[e("v-uni-text",{staticClass:"cuIcon-close text-red"})],1)],1),e("v-uni-view",{staticClass:"padding-xl",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.go()}}},[t._v(t._s(t.kefu_tel))])],1)],1)],1)],1)},o=[]},"30ca":function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("div",[e("div",{style:{height:t.statusBarHeight,background:t.bgColor}})])},o=[]},"3b13":function(t,i,e){"use strict";e.r(i);var n=e("7870"),a=e("75f9");for(var o in a)"default"!==o&&function(t){e.d(i,t,(function(){return a[t]}))}(o);e("a15d");var s,r=e("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"2037ea44",null,!1,n["a"],s);i["default"]=c.exports},"60af":function(t,i,e){var n=e("8e2b");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("383a4476",n,!0,{sourceMap:!1,shadowMode:!1})},"62f9":function(t,i,e){"use strict";(function(t){var n=e("4ea4");e("ac1f"),e("1276"),Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("96cf");var a=n(e("1da1")),o=(n(e("b168")),e("b3d2")),s=e("ffcc"),r=n(e("3b13")),c=(n(e("db74")),n(e("16be")),0),l=0,u=!0,d={onShow:function(){if(!(0,o.toLogin)())return!1;this.getData(),this.userInfo=(0,o.getStorageUserInfo)(),t.log(this.userInfo),t.log("============="),t.log(JSON.stringify((0,o.getStorageUserInfo)())),this.avator=this.userInfo.avatarUrl,this.getMemberInfo(),this.getCacheGoodsList()},onShareAppMessage:function(){var i=this.url;return i=i.split("?")[1],t.log("/pages/my/main?"+i),{path:"/pages/my/main?"+i}},components:{listCell:r.default},data:function(){return{opacity:0,qq:875358601,coverTransform:"translateY(0px)",coverTransition:"0s",moving:!1,avator:"https://imgt1.oss-cn-shanghai.aliyuncs.com/ecAllRes/images/missing-face.png",allcheck:!1,Listids:[],historyGoods:[],point:0,modalName:null,couponNum:0,advance:0,kefu_tel:"18888888888",userInfo:{},sup:{a:0,b:0,c:0,d:0,e:0},statuslv:"registerlv",rank_name:"",waitPayNum:0,waitRecNum:0,waitFhNum:0,url:"",my_subordinates:0}},onLoad:function(){},onNavigationBarButtonTap:function(t){var i=t.index;0===i?this.navTo("/apiMember/set/main"):1===i&&uni.switchTab({url:"/pages/topic/main"})},computed:{},methods:{scroll:function(t){this.opacity=t.detail.scrollTop/100,this.opacity>=1&&(this.opacity=1)},getData:function(){var i=this;return(0,a.default)(regeneratorRuntime.mark((function e(){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return t.log("URL的方法"),e.next=3,(0,s.GetPromoteApi)({});case 3:n=e.sent,i.url=n.data.url,n.data.code_url;case 6:case"end":return e.stop()}}),e)})))()},gotoLogin:function(){this.userInfo.avatarUrl||(0,o.toLogin)()},goodsDetail:function(i){t.log(i),uni.navigateTo({url:"/apiShop/goods/main?id="+i})},getCacheGoodsList:function(){var t=uni.getStorageSync("historyGoods");this.historyGoods=t},getMemberInfo:function(){var i=this;return(0,a.default)(regeneratorRuntime.mark((function e(){var n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,(0,s.memberInfoApi)({user_id:uni.getStorageSync("user_id")});case 2:n=e.sent,i.my_subordinates=n.data.my_subordinates,!0===n.data.res&&(i.point=n.data.info.point,i.advance=n.data.info.advance,i.couponNum=n.data.info.couponNum,i.sup=n.data.allOrderNum,i.statuslv=n.data.statuslv,i.rank_name=n.data.rank_name,n.data.kefu_tel&&(i.kefu_tel=n.data.kefu_tel.val),i.waitPayNum=i.sup.b,i.waitRecNum=i.sup.c,i.waitFhNum=i.sup.e,uni.setStorageSync("point",i.point),uni.setStorageSync("advance",i.advance),t.log(i.couponNum),t.log("历史："+i.historyGoods),t.log(uni.getStorageSync("historyGoods")+"历史"));case 5:case"end":return e.stop()}}),e)})))()},navTo:function(t){(0,o.toLogin)()&&uni.navigateTo({url:t})},showModal:function(t){this.modalName=t.currentTarget.dataset.target},hideModal:function(t){this.modalName=null},go:function(){uni.makePhoneCall({phoneNumber:this.kefu_tel,success:function(i){t.log("调用成功!")},fail:function(i){t.log("调用失败!")}})},kefu:function(){},coverTouchstart:function(t){!1!==u&&(this.coverTransition="transform .1s linear",c=t.touches[0].clientY)},coverTouchmove:function(t){l=t.touches[0].clientY;var i=l-c;i<0?this.moving=!1:(this.moving=!0,i>=80&&i<100&&(i=80),i>0&&i<=80&&(this.coverTransform="translateY(".concat(i,"px)")))},coverTouchend:function(){!1!==this.moving&&(this.moving=!1,this.coverTransition="transform 0.3s cubic-bezier(.21,1.93,.53,.64)",this.coverTransform="translateY(0px)")}}};i.default=d}).call(this,e("5a52")["default"])},"689f":function(t,i,e){var n=e("ed00");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=e("4f06").default;a("acdc7184",n,!0,{sourceMap:!1,shadowMode:!1})},"75f9":function(t,i,e){"use strict";e.r(i);var n=e("11b3"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=a.a},7870:function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"mix-list-cell",class:t.border,attrs:{"hover-class":"cell-hover","hover-stay-time":50},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.eventClick.apply(void 0,arguments)}}},[t.icon?e("v-uni-text",{staticClass:"cell-icon yticon",class:t.icon,style:[{color:t.iconColor}]}):t._e(),e("v-uni-text",{staticClass:"cell-tit clamp"},[t._v(t._s(t.title))]),t.tips?e("v-uni-text",{staticClass:"cell-tip"},[t._v(t._s(t.tips))]):t._e(),e("v-uni-text",{staticClass:"cell-more yticon",class:t.typeList[t.navigateType]})],1)],1)},o=[]},"7a8b":function(t,i,e){"use strict";e.r(i);var n=e("b61f"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=a.a},"8e2b":function(t,i,e){var n=e("24fb");i=n(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.icon .mix-list-cell.b-b[data-v-2037ea44]:after{left:%?90?%}.mix-list-cell[data-v-2037ea44]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:baseline;-webkit-align-items:baseline;align-items:baseline;padding:%?20?% %?30?%;line-height:%?60?%;position:relative}.mix-list-cell.cell-hover[data-v-2037ea44]{background:#fafafa}.mix-list-cell.b-b[data-v-2037ea44]:after{left:%?30?%}.mix-list-cell .cell-icon[data-v-2037ea44]{-webkit-align-self:center;align-self:center;width:%?56?%;max-height:%?60?%;font-size:%?32?%}.mix-list-cell .cell-more[data-v-2037ea44]{-webkit-align-self:center;align-self:center;font-size:%?30?%;color:#606266;margin-left:10px}.mix-list-cell .cell-tit[data-v-2037ea44]{-webkit-box-flex:1;-webkit-flex:1;flex:1;font-size:%?28?%;color:#303133;margin-right:%?10?%}.mix-list-cell .cell-tip[data-v-2037ea44]{font-size:%?26?%;color:#909399}',""]),t.exports=i},a15d:function(t,i,e){"use strict";var n=e("60af"),a=e.n(n);a.a},b168:function(t,i,e){"use strict";e.r(i);var n=e("30ca"),a=e("0bd8");for(var o in a)"default"!==o&&function(t){e.d(i,t,(function(){return a[t]}))}(o);var s,r=e("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"a5b2e68e",null,!1,n["a"],s);i["default"]=c.exports},b61f:function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={created:function(){},onLoad:function(){},data:function(){return{PageCur:""}},methods:{NavChange:function(t){uni.navigateTo({url:"/pages/"+t+"/main"})}}};i.default=n},bc1d:function(t,i,e){"use strict";var n;e.d(i,"b",(function(){return a})),e.d(i,"c",(function(){return o})),e.d(i,"a",(function(){return n}));var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{staticClass:"cu-bar tabbar bg-white shadow foot"},[e("v-uni-view",{staticClass:"action",attrs:{"data-cur":"index"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.NavChange("index")}}},[e("v-uni-view",{staticClass:"cuIcon-cu-image"},[e("v-uni-image",{attrs:{src:"/static/images/ic_menu_choice"+["index"==t.PageCur?"_pressed":"_nor"]+".png"}})],1),e("v-uni-view",{class:"index"==t.PageCur?"text-jred":"text-jgray"},[t._v("首页")])],1),e("v-uni-view",{staticClass:"action",attrs:{"data-cur":"topic"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.NavChange("topic")}}},[e("v-uni-view",{staticClass:"cuIcon-cu-image"},[e("v-uni-image",{attrs:{src:"/static/images/ic_menu_topic"+["topic"==t.PageCur?"_pressed":"_nor"]+".png"}})],1),e("v-uni-view",{class:"topic"==t.PageCur?"text-jred":"text-jgray"},[t._v("精选")])],1),e("v-uni-view",{staticClass:"action",attrs:{"data-cur":"category"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.NavChange("category")}}},[e("v-uni-view",{staticClass:"cuIcon-cu-image"},[e("v-uni-image",{attrs:{src:"/static/images/ic_menu_sort"+["category"==t.PageCur?"_pressed":"_nor"]+".png"}})],1),e("v-uni-view",{class:"category"==t.PageCur?"text-jred":"text-jgray"},[t._v("分类")])],1),e("v-uni-view",{staticClass:"action",attrs:{"data-cur":"cart"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.NavChange("cart")}}},[e("v-uni-view",{staticClass:"cuIcon-cu-image"},[e("v-uni-image",{attrs:{src:"/static/images/ic_menu_shoping"+["cart"==t.PageCur?"_pressed":"_nor"]+".png"}})],1),e("v-uni-view",{class:"cart"==t.PageCur?"text-jred":"text-jgray"},[t._v("购物车")])],1),e("v-uni-view",{staticClass:"action",attrs:{"data-cur":"my"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.NavChange("my")}}},[e("v-uni-view",{staticClass:"cuIcon-cu-image"},[e("v-uni-image",{attrs:{src:"/static/images/ic_menu_me"+["my"==t.PageCur?"_pressed":"_nor"]+".png"}})],1),e("v-uni-view",{class:"my"==t.PageCur?"text-jred":"text-jgray"},[t._v("我的")])],1)],1)},o=[]},c510:function(t,i,e){"use strict";e.r(i);var n=e("62f9"),a=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(i,t,(function(){return n[t]}))}(o);i["default"]=a.a},c950:function(t,i,e){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var n={data:function(){return{statusBarHeight:""}},props:{bgColor:{type:String,default:"#fff"}},created:function(){var t=this;t.$nextTick((function(){uni.getSystemInfo({success:function(i){t.statusBarHeight=i.statusBarHeight+"px"}})}))},mounted:function(){this.$emit("getHeight",this.statusBarHeight)}};i.default=n},db74:function(t,i,e){"use strict";e.r(i);var n=e("bc1d"),a=e("7a8b");for(var o in a)"default"!==o&&function(t){e.d(i,t,(function(){return a[t]}))}(o);var s,r=e("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"0daa49a6",null,!1,n["a"],s);i["default"]=c.exports},ed00:function(t,i,e){var n=e("24fb");i=n(!1),i.push([t.i,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 页面左右间距 */\n/* 文字尺寸 */\n/*文字颜色*/\n/* 边框颜色 */\n/* 图片加载中颜色 */\n/* 行为相关颜色 */.tj-sction .tj-item[data-v-8b677bd4], .order-section .order-item[data-v-8b677bd4], .order-section .order-item-width[data-v-8b677bd4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.tj-sction[data-v-8b677bd4], .order-section[data-v-8b677bd4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;-webkit-align-content:center;align-content:center;background:#fff;border-radius:%?10?%}.scroll[data-v-8b677bd4]{height:calc(100vh - %?1?%)}.top-bar[data-v-8b677bd4]{height:%?60?%;background-color:#f4f4f4;opacity:0;position:fixed;top:0;left:0;right:0;z-index:9999}.user-section[data-v-8b677bd4]{padding:%?120?% %?30?% 0;position:relative}.user-section .bg[data-v-8b677bd4]{position:absolute;left:0;top:0;width:100%;height:100%;-webkit-filter:blur(1px);filter:blur(1px);opacity:.7}.user-info-box[data-v-8b677bd4]{height:%?180?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative;z-index:1}.user-info-box .portrait[data-v-8b677bd4]{width:%?130?%;height:%?130?%;border:%?5?% solid #fff;border-radius:50%}.user-info-box .username[data-v-8b677bd4]{font-size:%?38?%;color:#303133;margin-left:%?20?%}.vip-card-box[data-v-8b677bd4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;color:#f7d680;height:%?240?%;background:-webkit-linear-gradient(left,rgba(0,0,0,.7),rgba(0,0,0,.8));background:linear-gradient(left,rgba(0,0,0,.7),rgba(0,0,0,.8));border-radius:%?16?% %?16?% 0 0;overflow:hidden;position:relative;padding:%?20?% %?24?%}.vip-card-box .card-bg[data-v-8b677bd4]{position:absolute;top:%?20?%;right:0;width:%?380?%;height:%?260?%}.vip-card-box .b-btn[data-v-8b677bd4]{position:absolute;right:%?20?%;top:%?16?%;width:%?132?%;height:%?40?%;text-align:center;line-height:%?40?%;font-size:%?22?%;color:#36343c;border-radius:20px;background:-webkit-linear-gradient(left,#f9e6af,#ffd465);background:linear-gradient(left,#f9e6af,#ffd465)}.vip-card-box .tit[data-v-8b677bd4]{font-size:%?30?%;color:#f7d680;margin-bottom:%?28?%}.vip-card-box .tit .yticon[data-v-8b677bd4]{color:#f15353;margin-right:%?16?%}.vip-card-box .e-b[data-v-8b677bd4]{font-size:%?24?%;color:#d8cba9;margin-top:%?10?%}.cover-container[data-v-8b677bd4]{background:#f8f8f8;margin-top:%?-90?%;padding:0 %?30?%;position:relative;background:#f5f5f5;padding-bottom:%?20?%}.cover-container .arc[data-v-8b677bd4]{position:absolute;left:0;top:%?-34?%;width:100%;height:%?36?%}.tj-sction .tj-item[data-v-8b677bd4]{-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;height:%?140?%;width:33.3%;font-size:%?24?%;color:#75787d;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.tj-sction .num[data-v-8b677bd4]{width:100%;font-size:%?32?%;color:#303133;margin-bottom:%?8?%;text-align:center;text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.all-line[data-v-8b677bd4]{padding:%?20?% %?20?% %?10?%;margin-top:%?20?%;margin-bottom:%?-40?%;background-color:#fff;border-radius:%?10?% %?10?% 0 0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.all-line .name[data-v-8b677bd4]{color:#666;font-size:%?26?%}.all-line .all[data-v-8b677bd4]{font-size:%?24?%;color:#999}.order-section[data-v-8b677bd4]{padding:%?28?% 0;margin-top:%?20?%;display:-webkit-box;display:-webkit-flex;display:flex}.order-section .order-item[data-v-8b677bd4]{width:%?120?%;height:%?120?%;border-radius:%?10?%;font-size:%?24?%;color:#303133}.order-section .order-item-width[data-v-8b677bd4]{width:%?200?%;height:%?120?%;border-radius:%?10?%;font-size:%?24?%;color:#303133}.order-section .faker[data-v-8b677bd4]{width:%?26?%;height:%?26?%;display:block;position:relative;z-index:10;border-radius:50%;top:%?15?%;left:14%;text-align:center;color:#fff;font-size:%?20?%;background:transparent}.order-section .sup[data-v-8b677bd4]{width:%?30?%;height:%?30?%;display:block;position:relative;z-index:10;border-radius:50%;top:%?15?%;left:14%;text-align:center;color:#fff;font-size:%?20?%;background:#fa436a}.order-section .yticon[data-v-8b677bd4]{font-size:%?48?%;margin-bottom:%?18?%;color:#fa436a}.order-section .icon-shouhoutuikuan[data-v-8b677bd4]{font-size:%?48?%}.history-section[data-v-8b677bd4]{padding:%?30?% 0 0;margin-top:%?20?%;background:#fff;border-radius:%?10?%}.history-section .sec-header[data-v-8b677bd4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?28?%;color:#303133;line-height:%?40?%;margin-left:%?30?%}.history-section .sec-header .yticon[data-v-8b677bd4]{font-size:%?44?%;color:#5eba8f;margin-right:%?16?%;line-height:%?40?%}.history-section uni-button[data-v-8b677bd4]{width:100%;text-align:left;padding:0\n}.history-section uni-button list-cell[data-v-8b677bd4]{width:%?690?%}.history-section uni-button uni-view[data-v-8b677bd4]{width:%?690?%}.history-section .btn[data-v-8b677bd4]{border-top:0 none;display:-webkit-box;display:-webkit-flex;display:flex;background:none;border-radius:0;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border:0 none}.history-section .btn[data-v-8b677bd4]:after{border:0 none}.history-section .h-list[data-v-8b677bd4]{width:100%;white-space:nowrap;overflow:hidden;padding:%?30?% %?30?% 0}.history-section .h-list uni-image[data-v-8b677bd4]{display:inline-block;width:%?160?%;height:%?160?%;margin-right:%?20?%;border-radius:%?10?%}uni-button[data-v-8b677bd4]::after{display:none}',""]),t.exports=i}}]);