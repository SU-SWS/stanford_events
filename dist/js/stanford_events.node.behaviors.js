!function(n){var r={};function a(e){if(r[e])return r[e].exports;var t=r[e]={i:e,l:!1,exports:{}};return n[e].call(t.exports,t,t.exports,a),t.l=!0,t.exports}a.m=n,a.c=r,a.d=function(e,t,n){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)a.d(n,r,function(e){return t[e]}.bind(null,r));return n},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=0)}([function(e,t,n){"use strict";n.r(t);n(1)},function(e,t){window.Drupal.behaviors.stanford_events={attach:function(i){!function(e){e(".event-types__collapsable-menu",i).click(function(){e(this).toggleClass("show"),"none"!==e(this).siblings(".menu").css("display")?e(this).attr("aria-expanded","true"):e(this).attr("aria-expanded","false")});var t=(new Date).setHours(0,0,0,0),n=parseInt(t,10),r=e("div.node-stanford-event-su-event-type .su-event-type"),a=e("div.node-stanford-event-su-event-date-time"),s=e("section.event").attr("data-end-date"),o=e('<span class="su-event-label-past">'+Drupal.t("Past Event")+'<span class="divider">'+Drupal.t("|")+"</span></span>"),u=e('<div class="su-event-text-past">'+Drupal.t("This event has passed.")+"</div>");s<n&&(o.prependTo(r.first()),u.appendTo(a.last()))}(jQuery)}}}]);
//# sourceMappingURL=stanford_events.node.behaviors.js.map