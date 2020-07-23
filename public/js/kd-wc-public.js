var kdwc_viewed_triggers = {};


(function( $ ) {
	'use strict';

	$(document).ready(function () {

		function lookForLoadLaterTriggers(){
			var ret = [];
			var tags = $('KdWcTrigger');
			tags.each(function(index,el){
				var tid = el.getAttribute('tid');
				if(null!== tid && $.inArray(tid,ret)<0){
					ret.push(tid);
				}
			});
			return ret;
		}

		function replaceLoadLaterTriggers(){
			var toReplace = lookForLoadLaterTriggers();
			if (toReplace.length>0){
				sendAjaxReq('render_kdwc_shortcodes',{triggers:toReplace,pageload_referrer:referrer_for_pageload},function(ret){	//Referrer from kd-wc public
					if(ret && ret!== null){
						try{
							var data = JSON.parse(ret);
							$.each(data, function(tid,tval){
								var tagsInDom = $('KdWcTrigger[tid="'+tid+'"]');
								tagsInDom.each(function(i,tag){
									tag.outerHTML = tval;
								})
							});
						}
						catch(e){
							console.error('Error fetching kd-wc triggers!');
							console.error(e);
						}
					}
				})
			}
		}

		replaceLoadLaterTriggers();

		function sendAjaxReq(action, data, cb) {
			data['action'] = action;
			data['nonce'] = nonce;
			//data['page_url'] = kdwc_page_url;
			data['page_url'] = window.location.href;

			$.post(ajaxurl, data, function(response) {
				if (cb)
					cb(response);
			});
		}

		function getCookie(c_name) {
			var c_value = document.cookie,
				c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
			if (c_start == -1) {
				c_value = null;
			} else {
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = c_value.length;
				}
				c_value = unescape(c_value.substring(c_start, c_end));
			}
			return c_value;
		}

        function createCookie(name, value, days) {
            var expires;
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                expires = "; expires="+date.toGMTString();
            }
            else {
                expires = "";
            }
            document.cookie = name+"="+value+expires+"; path=/";
        }

        function deleteCookie( name ) {
			document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;";
        }

		function initialize_last_viewed_globals(){
			var cookie = getCookie('kdwc_last_viewed');
			try{
				var cookieObj = JSON.parse(cookie);
				kdwc_viewed_triggers = cookieObj;
			}
			catch(err){console.log('ERR! ' + err)}
		}

		function kdwc_analytics_do_conversion(allowed,disallowed){
			var allowed_triggers = allowed!=null ? decodeURIComponent(allowed) : false;
			var disallowed_triggers = disallowed ? decodeURIComponent(disallowed) : false;
 			var params = {an_action:'doConversion',postid:666, data:JSON.stringify(kdwc_viewed_triggers)};
			if(allowed_triggers) params['allowed'] = allowed_triggers;
			if(disallowed_triggers) params['disallowed'] = disallowed_triggers;
			sendAjaxReq('kdwc_analytics_req',params, function(){});
		}

		if(isPageVisitedOn){	//Passed form kd-wc public
			sendAjaxReq('kdwc_add_page_visit', {}, function(response){});
		}

		if(isAnalyticsOn){	//Passed from kd-wc public
			initialize_last_viewed_globals();
			if(getCookie('kdwc_viewing_triggers')) sendAjaxReq('kdwc_analytics_req',{postid:666,an_action:'ajaxViews',data:getCookie('kdwc_viewing_triggers') });

			if($('.kdwc-conversion-complete').length>0){
				var allowed =[];
				var disallowed = [];
				var doAll = false;
				$.each($('.kdwc-conversion-complete'),function(key,value){
					var allowed_here = value.getAttribute('allowed_triggers');
					var disallowed_here = value.getAttribute('disallowed_triggers');
					if(allowed_here!=null && allowed_here != 'all'){
						allowed_here = allowed_here.split(',');
						allowed = allowed.concat(allowed_here);
					}
					else{
						doAll = true;
					}
					if(disallowed_here!=null){
						disallowed_here = disallowed_here.split(',');
						disallowed = disallowed.concat(disallowed_here);
					}
				});
				if(disallowed == []) disallowed = false;
				if(doAll) kdwc_analytics_do_conversion(null,disallowed);
				else kdwc_analytics_do_conversion(allowed,disallowed);
			}
		}


		//Bounce mechanism - not in use for now
		/*if(getCookie('kdwc_bounce')){
            sendAjaxReq('kdwc_analytics_req',{an_action:'decrementField',postid:kdwc_last_viewed_trigger.triggerid, versionid:kdwc_last_viewed_trigger.versionid, field:'bounce'}, function(){
                deleteCookie('kdwc_bounce')
            });
        }
		window.addEventListener('beforeunload',function(e){
		    if(!getCookie('kdwc_bounce')){
				createCookie('kdwc_bounce');
                sendAjaxReq('kdwc_analytics_req',{an_action:'incrementField',postid:kdwc_last_viewed_trigger.triggerid, versionid:kdwc_last_viewed_trigger.versionid, field:'bounce'}, function(){});
            }

		});*/


	});

})( jQuery );

