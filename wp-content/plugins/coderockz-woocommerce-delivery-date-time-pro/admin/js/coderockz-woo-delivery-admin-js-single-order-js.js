(function(a){a(function(){function C(b){a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(u);e!==-1&&a(this).text(d.substr(0,e));}});}function D(b){a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(u);e!==-1&&a(this).text(d.substr(0,e));}});}function E(b){a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(u);e!==-1&&a(this).text(d.substr(0,e));}});}function F(d,f,g,c,b,e){d==f&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a(this).val()!='as-soon-as-possible'&&a(this).val()!='')if(a(this).val().indexOf(' - ')!==-1){var d=a(this).val().split(' - ');_times_one=d[0].split(':'),_times_two=d[1].split(':'),d=_times_one[0]*60+parseInt(_times_one[1])+' - '+(_times_two[0]*60+parseInt(_times_two[1])),d=d.split(' - '),(d[0]<=c+b&&d[1]<=c+b||d[0]<=c+b&&e=='1')&&a(this).attr('disabled',!0);}else _times_one=a(this).val().split(':'),d=_times_one[0]*60+parseInt(_times_one[1]),d<=c+b&&a(this).attr('disabled',!0);}),b>0&&d==g&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a(this).val()!='as-soon-as-possible'&&a(this).val()!='')if(a(this).val().indexOf(' - ')!==-1){var c=a(this).val().split(' - ');_times_one=c[0].split(':'),_times_two=c[1].split(':'),c=_times_one[0]*60+parseInt(_times_one[1])+' - '+(_times_two[0]*60+parseInt(_times_two[1])),c=c.split(' - '),(c[0]<=b&&c[1]<=b||c[0]<=b&&e=='1')&&a(this).attr('disabled',!0);}else _times_one=a(this).val().split(':'),c=_times_one[0]*60+parseInt(_times_one[1]),c<=b&&a(this).attr('disabled',!0);});}function G(b){a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){a.inArray(a(this).val(),b)!==-1&&(a(this).hide(),a(this).attr('disabled',!0));});}function H(b){a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){a.inArray(a(this).val(),b)!==-1&&(a(this).hide(),a(this).attr('disabled',!0));});}function I(d,c){for(var b in c)c[b].length>0&&a.inArray(d,c[b])!==-1&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){a(this).val()==b&&(a(this).hide(),a(this).attr('disabled',!0));});}function J(e,f){var b={};if(e.length>0){for(var d=0;e.length>d;d++)b[e[d]]=(b[e[d]]||0)+1;for(var c in b){if(!b.hasOwnProperty(c))continue;typeof f!==typeof undefined&&f!==!1&&b[c]>=f[c]&&f[c]!=0&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){a(this).val()==c&&(a(this).attr('disabled',!0),a(this).text(a(this).text()+u));});}}}function K(c,d,e,b){c==d&&e&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a(this).val()!='as-soon-as-possible'&&a(this).val()!=''&&a(this).val().indexOf(' - ')!==-1){var c=a(this).val().split(' - ');_times_one=c[0].split(':'),_times_two=c[1].split(':'),c=_times_one[0]*60+parseInt(_times_one[1])+' - '+(_times_two[0]*60+parseInt(_times_two[1])),c=c.split(' - '),c[0]<=b&&c[1]>b&&a(this).attr('disabled',!0);}});}function L(b,c,d){(typeof a('#coderockz_woo_delivery_meta_box_datepicker').val()==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').val()==0||a('#coderockz_woo_delivery_meta_box_datepicker').val()=='')&&a('#coderockz_woo_delivery_meta_box_time_field option').each(function(){if(a(this).val()!='as-soon-as-possible'&&a(this).val()!='')if(a(this).val().indexOf(' - ')!==-1){var e=a(this).val().split(' - ');_times_one=e[0].split(':'),_times_two=e[1].split(':'),e=_times_one[0]*60+parseInt(_times_one[1])+' - '+(_times_two[0]*60+parseInt(_times_two[1])),e=e.split(' - '),(e[0]<=b+c&&e[1]<=b+c||e[0]<=b+c&&d=='1')&&a(this).attr('disabled',!0);}else _times_one=a(this).val().split(':'),e=_times_one[0]*60+parseInt(_times_one[1]),e<=b+c&&a(this).attr('disabled',!0);});}function M(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(s);e!==-1&&a(this).text(d.substr(0,e));}});}function N(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(s);e!==-1&&a(this).text(d.substr(0,e));}});}function O(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(s);e!==-1&&a(this).text(d.substr(0,e));}});}function P(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a.inArray(a(this).val(),b)!==-1){var c=a(this).attr('disabled');typeof c!==typeof undefined&&c!==!1&&(a(this).show(),a(this).attr('disabled',!1));var d=a(this).text();var e=d.indexOf(s);e!==-1&&a(this).text(d.substr(0,e));}});}function Q(e,f,g,d,c,h){e==f&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a(this).val().indexOf(' - ')!==-1){var e=a(this).val().split(' - ');_pickupTimes_one=e[0].split(':'),_pickupTimes_two=e[1].split(':'),e=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1])+' - '+(_pickupTimes_two[0]*60+parseInt(_pickupTimes_two[1])),e=e.split(' - '),(e[0]<=d+c&&e[1]<=d+c||e[0]<=d+c&&b=='1')&&a(this).attr('disabled',!0);}else _pickupTimes_one=a(this).val().split(':'),e=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1]),e<=d+c&&a(this).attr('disabled',!0);}),c>0&&e==g&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a(this).val().indexOf(' - ')!==-1){var d=a(this).val().split(' - ');_pickupTimes_one=d[0].split(':'),_pickupTimes_two=d[1].split(':'),d=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1])+' - '+(_pickupTimes_two[0]*60+parseInt(_pickupTimes_two[1])),d=d.split(' - '),(d[0]<=c&&d[1]<=c||d[0]<=c&&b=='1')&&a(this).attr('disabled',!0);}else _pickupTimes_one=a(this).val().split(':'),d=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1]),d<=c&&a(this).attr('disabled',!0);});}function R(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){a.inArray(a(this).val(),b)!==-1&&(a(this).hide(),a(this).attr('disabled',!0));});}function S(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){a.inArray(a(this).val(),b)!==-1&&(a(this).hide(),a(this).attr('disabled',!0));});}function T(b){a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){a.inArray(a(this).val(),b)!==-1&&(a(this).hide(),a(this).attr('disabled',!0));});}function U(d,c){for(var b in c)c[b].length>0&&a.inArray(d,c[b])!==-1&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){a(this).val()==b&&(a(this).hide(),a(this).attr('disabled',!0));});}function V(e,f){var b={};if(e.length>0){for(var d=0;e.length>d;d++)b[e[d]]=(b[e[d]]||0)+1;for(var c in b){if(!b.hasOwnProperty(c))continue;typeof f!==typeof undefined&&f!==!1&&b[c]>=f[c]&&f[c]!=0&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){a(this).val()==c&&(a(this).attr('disabled',!0),a(this).text(a(this).text()+s));});}}}function W(c,d,e,b){c==d&&e&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a(this).val().indexOf(' - ')!==-1){var c=a(this).val().split(' - ');_times_one=c[0].split(':'),_times_two=c[1].split(':'),c=_times_one[0]*60+parseInt(_times_one[1])+' - '+(_times_two[0]*60+parseInt(_times_two[1])),c=c.split(' - '),c[0]<=b&&c[1]>b&&a(this).attr('disabled',!0);}});}function X(c,d,e){(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').val()==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').val()==0||a('#coderockz_woo_delivery_meta_box_pickup_datepicker').val()=='')&&a('#coderockz_woo_delivery_meta_box_pickup_field option').each(function(){if(a(this).val().indexOf(' - ')!==-1){var e=a(this).val().split(' - ');_pickupTimes_one=e[0].split(':'),_pickupTimes_two=e[1].split(':'),e=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1])+' - '+(_pickupTimes_two[0]*60+parseInt(_pickupTimes_two[1])),e=e.split(' - '),(e[0]<=c+d&&e[1]<=c+d||e[0]<=c+d&&b=='1')&&a(this).attr('disabled',!0);}else _pickupTimes_one=a(this).val().split(':'),e=_pickupTimes_one[0]*60+parseInt(_pickupTimes_one[1]),e<=c+d&&a(this).attr('disabled',!0);});}function j(d){var a,e=d.length,b=[],c={};for(a=0;a<e;a++)c[d[a]]=0;for(a in c)b.push(a);return b;}function Y(){var i=[];var p=a('#coderockz_woo_delivery_meta_box_datepicker').data('selectable_dates');var e=new Date();var m='0'+(e.getMonth()+1);var k='0'+e.getDate();var c=e.getFullYear()+'-'+m.substr(-2)+'-'+k.substr(-2);a0&&l.unshift(c),all_disable_week_days=a.merge(a.merge(a.merge(a.merge([],d),t),f),g),all_disable_week_days=j(all_disable_week_days),disable_week_days_for_opening_day=a.merge(a.merge([],d),t),disable_week_days_for_opening_day=j(disable_week_days_for_opening_day);for(var r=0;r<p;r++){var e=new Date();var w=e.setDate(e.getDate()+r);var o=new Date(w);var m='0'+(Number(o.getMonth())+1);var x='0'+o.getDate();var k=o.getDay().toString();var h=o.getFullYear()+'-'+m.substr(-2)+'-'+x.substr(-2);a.inArray(k,disable_week_days_for_opening_day)||a.inArray(h,q)&&a.inArray(h,v)||(i.push(h),p-=1),a.inArray(h,l)===-1&&a.inArray(h,v)===-1&&a.inArray(h,z)===-1&&a.inArray(k,all_disable_week_days)===-1?i.push(h):p+=1;}var s=a('#coderockz_woo_delivery_meta_box_order_id').val();var u=a('#coderockz_woo_delivery_meta_box_delivery_selection_field option:selected').val();flatpickr.localize(flatpickr.l10ns[_]),a('#coderockz_woo_delivery_meta_box_datepicker').length?a('#coderockz_woo_delivery_meta_box_datepicker').flatpickr({enable:i,dateFormat:Z,locale:{firstDayOfWeek:$},onChange:function(h,j,k){a('.coderockz-woo-delivery-loading-image').fadeIn();var d=new Date(h);var f='0'+(d.getMonth()+1);var g='0'+d.getDate();var e=d.getFullYear()+'-'+f.substr(-2)+'-'+g.substr(-2);a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders',date:e,orderId:s,deliveryType:u},success:function(q){data=JSON.parse(q.data);var d=data.current_time;var f=data.delivery_times;var g=data.max_order_per_slot;var h=data.slot_disable_for;var j=data.formated_date;var k=data.disable_timeslot.state;var l=data.disable_timeslot.postcode;var m=data.state_zip_disable_timeslot_all.state;var o=data.state_zip_disable_timeslot_all.postcode;var p=data.no_state_zip_disable_timeslot_all.nostatezip;C(m),D(o),E(p),F(e,c,i[0],d,n,b),G(k),H(l),I(j,h),J(f,g),K(e,c,data.disabled_current_time_slot,d),L(d,n,b),a('.coderockz-woo-delivery-loading-image').fadeOut();}});},onReady:function(j,k,f){a('.coderockz-woo-delivery-loading-image').fadeIn();var e=new Date(f.selectedDates[0]);var g='0'+(e.getMonth()+1);var h='0'+e.getDate();if(f.selectedDates.length>0)var d=e.getFullYear()+'-'+g.substr(-2)+'-'+h.substr(-2);else var d=c;a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders',date:d,orderId:s,deliveryType:u},success:function(q){data=JSON.parse(q.data);var e=data.current_time;var f=data.delivery_times;var g=data.max_order_per_slot;var h=data.slot_disable_for;var j=data.formated_date;var k=data.disable_timeslot.state;var l=data.disable_timeslot.postcode;var m=data.state_zip_disable_timeslot_all.state;var o=data.state_zip_disable_timeslot_all.postcode;var p=data.no_state_zip_disable_timeslot_all.nostatezip;C(m),D(o),E(p),F(d,c,i[0],e,n,b),G(k),H(l),I(j,h),J(f,g),K(d,c,data.disabled_current_time_slot,e),L(e,n,b),a('.coderockz-woo-delivery-loading-image').fadeOut();}});}}):(a('.coderockz-woo-delivery-loading-image').fadeIn(),a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders',onlyDeliveryTime:!0,date:c,orderId:s,deliveryType:u},success:function(p){data=JSON.parse(p.data);var d=data.current_time;if(data.length){var e=data.delivery_times;var f=data.max_order_per_slot;var g=data.slot_disable_for;var h=data.formated_date;var j=data.disable_timeslot.state;var k=data.disable_timeslot.postcode;var l=data.state_zip_disable_timeslot_all.state;var m=data.state_zip_disable_timeslot_all.postcode;var o=data.no_state_zip_disable_timeslot_all.nostatezip;}else{var e=[];var f=[];var g=[];var h='';var j=[];var k=[];var l=[];var m=[];var o=[];}C(l),D(m),E(o),F(c,c,i[0],d,n,b),G(j),H(k),I(h,g),J(e,f),K(c,c,data.disabled_current_time_slot,d),L(d,n,b),a('.coderockz-woo-delivery-loading-image').fadeOut();}}));}function y(){var g=[];var q=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_selectable_dates');var d=new Date();var l='0'+(d.getMonth()+1);var k='0'+d.getDate();var b=d.getFullYear()+'-'+l.substr(-2)+'-'+k.substr(-2);a4&&m.unshift(b),all_pickup_disable_week_days=a.merge(a.merge(a.merge(a.merge([],e),t),h),i),all_pickup_disable_week_days=j(all_pickup_disable_week_days),disable_week_days_for_opening_day_pickup=a.merge(a.merge([],e),t),disable_week_days_for_opening_day_pickup=j(disable_week_days_for_opening_day_pickup);for(var s=0;s<q;s++){var d=new Date();var v=d.setDate(d.getDate()+s);var n=new Date(v);var l='0'+(Number(n.getMonth())+1);var x='0'+n.getDate();var k=n.getDay().toString();var f=n.getFullYear()+'-'+l.substr(-2)+'-'+x.substr(-2);a.inArray(k,disable_week_days_for_opening_day_pickup)||a.inArray(f,r)&&a.inArray(f,w)||(g.push(f),q-=1),a.inArray(f,m)===-1&&a.inArray(f,w)===-1&&a.inArray(f,A)===-1&&a.inArray(k,all_pickup_disable_week_days)===-1?g.push(f):q+=1;}var u=a('#coderockz_woo_delivery_meta_box_order_id').val();pickupDate=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').val(),flatpickr.localize(flatpickr.l10ns[a2]),a('#coderockz_woo_delivery_meta_box_pickup_datepicker').length?a('#coderockz_woo_delivery_meta_box_pickup_datepicker').flatpickr({enable:g,dateFormat:a1,locale:{firstDayOfWeek:a3},onChange:function(h,i,k){a('.coderockz-woo-delivery-loading-image').fadeIn();var c=new Date(h);var e='0'+(c.getMonth()+1);var f='0'+c.getDate();var d=c.getFullYear()+'-'+e.substr(-2)+'-'+f.substr(-2);givenLocation=a('#coderockz_woo_delivery_pickup_location_field').val(),a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders_pickup',date:d,orderId:u,givenLocation:givenLocation},success:function(v){data=JSON.parse(v.data);var f=data.current_time;formatedPickupDateSelected=data.formated_pickup_date_selected;var i=data.pickup_delivery_times;var k=data.pickup_max_order_per_slot;var l=data.pickup_slot_disable_for;var m=data.formated_date;var n=data.pickup_disable_timeslot.state;var q=data.pickup_disable_timeslot.postcode;var r=data.pickup_state_zip_disable_timeslot_all.state;var s=data.pickup_state_zip_disable_timeslot_all.postcode;var e=data.pickup_no_state_zip_disable_timeslot_all.nostatezip;var h=data.detect_pickup_location_hide;if(h){var t=data.pickup_disable_timeslot_location.location;var u=data.pickup_location_disable_timeslot_all.location;var c=data.pickup_no_location_disable_timeslot_all.nolocation;if(typeof c!==typeof undefined&&c!==!1)var c=data.pickup_no_location_disable_timeslot_all.nolocation;else var c=[];e=a.merge(a.merge([],c),e),e=j(e);}M(r),N(s),h&&O(u),P(e),Q(d,b,g[0],f,o,p),R(n),S(q),h&&T(t),U(m,l),V(i,k),W(d,b,data.pickup_disabled_current_time_slot,f),X(f,o,p),a('.coderockz-woo-delivery-loading-image').fadeOut();}});},onReady:function(k,l,f){a('.coderockz-woo-delivery-loading-image').fadeIn();var e=new Date(f.selectedDates[0]);var h='0'+(e.getMonth()+1);var i='0'+e.getDate();if(f.selectedDates.length>0)var d=e.getFullYear()+'-'+h.substr(-2)+'-'+i.substr(-2);else var d=b;givenLocation=a('#coderockz_woo_delivery_pickup_location_field').val(),a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders_pickup',date:d,orderId:u,givenLocation:givenLocation},success:function(w){data=JSON.parse(w.data);var h=data.current_time;formatedPickupDateSelected=data.formated_pickup_date_selected;var l=data.pickup_delivery_times;var m=data.pickup_max_order_per_slot;var n=data.pickup_slot_disable_for;var q=data.formated_date;var r=data.pickup_disable_timeslot.state;var s=data.pickup_disable_timeslot.postcode;var t=data.pickup_state_zip_disable_timeslot_all.state;var u=data.pickup_state_zip_disable_timeslot_all.postcode;var f=data.pickup_no_state_zip_disable_timeslot_all.nostatezip;if(c=data.detect_pickup_location_hide,c){a('#coderockz_woo_delivery_meta_box_pickup_datepicker').val(pickupDate);var i=a('#coderockz_woo_delivery_meta_box_pickup_field');i.next('#coderockz_woo_delivery_pickup_location_field').insertBefore(i);var k=data.pickup_disable_timeslot_location.location;var v=data.pickup_location_disable_timeslot_all.location;var e=data.pickup_no_location_disable_timeslot_all.nolocation;if(a.inArray(a('#coderockz_woo_delivery_meta_box_pickup_field').val(),k)!==-1&&a('#coderockz_woo_delivery_meta_box_pickup_field').val(''),typeof e!==typeof undefined&&e!==!1)var e=data.pickup_no_location_disable_timeslot_all.nolocation;else var e=[];f=a.merge(a.merge([],e),f),f=j(f);}M(t),N(u),c&&O(v),P(f),Q(d,b,g[0],h,o,p),R(r),S(s),c&&T(k),U(q,n),V(l,m),W(d,b,data.pickup_disabled_current_time_slot,h),X(h,o,p),a('.coderockz-woo-delivery-loading-image').fadeOut();}});}}):(a('.coderockz-woo-delivery-loading-image').fadeIn(),a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_meta_box_get_orders_pickup',onlyPickupTime:!0,date:b,orderId:u},success:function(v){data=JSON.parse(v.data);var h=data.current_time;if(data.length){var i=data.pickup_delivery_times;var k=data.pickup_max_order_per_slot;var l=data.pickup_slot_disable_for;var m=data.formated_date;var n=data.pickup_disable_timeslot.state;var q=data.pickup_disable_timeslot.postcode;var r=data.pickup_state_zip_disable_timeslot_all.state;var s=data.pickup_state_zip_disable_timeslot_all.postcode;var e=data.pickup_no_state_zip_disable_timeslot_all.nostatezip;}else{var i=[];var k=[];var l=[];var m='';var n=[];var q=[];var r=[];var s=[];var e=[];var f=[];var t=[];var d=[];}if(c=data.detect_pickup_location_hide,c){var u=a('#coderockz_woo_delivery_meta_box_pickup_field');u.next('#coderockz_woo_delivery_pickup_location_field').insertBefore(u);var f=data.pickup_disable_timeslot_location.location;var t=data.pickup_location_disable_timeslot_all.location;var d=data.pickup_no_location_disable_timeslot_all.nolocation;if(a.inArray(a('#coderockz_woo_delivery_meta_box_pickup_field').val(),f)!==-1&&a('#coderockz_woo_delivery_meta_box_pickup_field').val(''),typeof d!==typeof undefined&&d!==!1)var d=data.pickup_no_location_disable_timeslot_all.nolocation;else var d=[];e=a.merge(a.merge([],d),e),e=j(e);}M(r),N(s),c&&O(t),P(e),Q(b,b,g[0],h,o,p),R(n),S(q),c&&T(f),U(m,l),V(i,k),W(b,b,data.pickup_disabled_current_time_slot,h),X(h,o,p),a('.coderockz-woo-delivery-loading-image').fadeOut();}}));}a(document).on('click','.coderockz-woo-delivery-metabox-delivery-complete-btn',function(b){b.preventDefault(),a(this).find('button').text('Completing...'),B=a('#coderockz_woo_delivery_meta_box_order_id').val(),a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'post',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_make_order_delivered',orderId:B},success:function(a){location.reload();}});}),a(document).on('click','.coderockz-woo-delivery-metabox-update-btn',function(l){l.preventDefault();var c=a(this).children('button').text();c=='Update'?a(this).children('button').text('Updating...'):a(this).children('button').text(c+'ing...');var d=a("input[name='coderockz_woo_delivery_meta_box_datepicker']").val();var e=a("input[name='coderockz_woo_delivery_meta_box_pickup_datepicker']").val();var f=a("select[name='coderockz_woo_delivery_meta_box_delivery_selection_field']").val();var g=a("select[name='coderockz_woo_delivery_meta_box_time_field']").val();var h=a("select[name='coderockz_woo_delivery_meta_box_pickup_field']").val();var i=a("select[name='coderockz_woo_delivery_pickup_location_field']").val();var j=a('#coderockz_woo_delivery_meta_box_additional_field_field').val();var k=a('#coderockz_woo_delivery_meta_box_order_id').val();var b=a(this).data('notify');typeof b!==typeof undefined&&b!==!1?b='yes':b='no',a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'post',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_save_meta_box_information',deliveryOption:f,date:d,pickupDate:e,time:g,pickupTime:h,pickup:i,additional:j,orderId:k,notify:b},success:function(a){location.reload();}});});var k='';k+='<div class="coderockz-woo-delivery-loading-image">',k+='<div class="coderockz-woo-delivery-loading-gif">',k+='<img src="'+a('.coderockz-woo-delivery-metabox-update-section').data('plugin-url')+'public/images/loading.gif" alt="" />',k+='</div>',k+='</div>',a('#coderockz_woo_delivery_meta_box').append(k);var Z=a('#coderockz_woo_delivery_meta_box_datepicker').data('date_format');if(typeof a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days')!==!1){var d=a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days');d=d.toString(),d=d.split(',');}else var d=[];if(typeof a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_category')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_category')!==!1){var f=a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_category');f=f.toString(),f=f.split(',');}else var f=[];if(typeof a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_product')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_product')!==!1){var g=a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_week_days_product');g=g.toString(),g=g.split(',');}else var g=[];var _=a('#coderockz_woo_delivery_meta_box_datepicker').data('calendar_locale');var $=a('#coderockz_woo_delivery_meta_box_datepicker').data('week_starts_from');if(typeof a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_dates')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_dates')!==!1){var l=a('#coderockz_woo_delivery_meta_box_datepicker').data('disable_dates');l=l.toString(),l=l.split('::');}if(typeof a('#coderockz_woo_delivery_meta_box_datepicker').data('special_open_days_dates')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_datepicker').data('special_open_days_dates')!==!1){var q=a('#coderockz_woo_delivery_meta_box_datepicker').data('special_open_days_dates');q=q.toString(),q=q.split('::');}var a0=a('#coderockz_woo_delivery_meta_box_datepicker').data('same_day_delivery');var a1=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_date_format');if(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days')!==!1){var e=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days');e=e.toString(),e=e.split(',');}else var e=[];if(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_category')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_category')!==!1){var h=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_category');h=h.toString(),h=h.split(',');}else var h=[];if(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_product')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_product')!==!1){var i=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_week_days_product');i=i.toString(),i=i.split(',');}else var i=[];var a2=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_calendar_locale');var a3=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_week_starts_from');if(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_dates')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_dates')!==!1){var m=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('pickup_disable_dates');m=m.toString(),m=m.split('::');}if(typeof a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('special_open_days_dates_pickup')!==typeof undefined&&a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('special_open_days_dates_pickup')!==!1){var r=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('special_open_days_dates_pickup');r=r.toString(),r=r.split('::');}var a4=a('#coderockz_woo_delivery_meta_box_pickup_datepicker').data('same_day_pickup');var n=a('#coderockz_woo_delivery_meta_box_time_field').data('max_processing_time');var o=a('#coderockz_woo_delivery_meta_box_pickup_field').data('max_processing_time');var b=a('#coderockz_woo_delivery_meta_box_time_field').data('disable_timeslot_with_processing_time');var p=a('#coderockz_woo_delivery_meta_box_pickup_field').data('disable_timeslot_with_processing_time');var u=a('#coderockz_woo_delivery_meta_box_time_field').data('order_limit_notice');var s=a('#coderockz_woo_delivery_meta_box_pickup_field').data('pickup_limit_notice');var v=[];var w=[];var t=[];var z=[];var A=[];if(a('#coderockz_woo_delivery_meta_box_datepicker').length||a('#coderockz_woo_delivery_meta_box_pickup_datepicker').length){a('.coderockz-woo-delivery-loading-image').fadeIn();var B=a('#coderockz_woo_delivery_meta_box_order_id').val();a.when(a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_get_state_zip_disable_weekday',orderId:B},success:function(a){data=JSON.parse(a.data),t=data.current_state_zip_offdays;}})).then(function(b){var c=a('#coderockz_woo_delivery_meta_box_delivery_selection_field option:selected').val();a.when(a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_admin_disable_max_delivery_pickup_date',deliverySelector:c},success:function(a){b=JSON.parse(a.data),v=b.disable_for_max_delivery_dates,w=b.disable_for_max_pickup_dates,z=b.disable_delivery_date_passed_time,A=b.disable_pickup_date_passed_time;}})).then(function(a){c=='delivery'?Y():c=='pickup'&&y();});});}else y(),Y();var c=!1;a(document).on('change','#coderockz_woo_delivery_pickup_location_field',function(a){a.preventDefault(),c&&y();});var x=a('#coderockz_woo_delivery_meta_box_additional_field_field').data('character_limit');if(typeof x!==typeof undefined&&x!==!1){a('#coderockz_woo_delivery_meta_box_additional_field_field').after("<span class='coderockz-woo-delivery-meta-box-additional-field-text-count'></span>");var a5=a('#coderockz_woo_delivery_meta_box_additional_field_field').val().length;a('.coderockz-woo-delivery-meta-box-additional-field-text-count').text(x-a5+' characters remaining'),a('#coderockz_woo_delivery_meta_box_additional_field_field').on('keyup',function(d){var c=a('#coderockz_woo_delivery_meta_box_additional_field_field').val().length;var b=x-c;b==0?(a('#coderockz_woo_delivery_meta_box_additional_field_field').css('border','1px solid #CA4A1F'),a('.coderockz-woo-delivery-meta-box-additional-field-text-count').css('color','#CA4A1F')):(a('#coderockz_woo_delivery_meta_box_additional_field_field').css('border','1px solid #0073aa'),a('.coderockz-woo-delivery-meta-box-additional-field-text-count').css('color','unset')),a('.coderockz-woo-delivery-meta-box-additional-field-text-count').text(b+' characters remaining');});}a('#coderockz_woo_delivery_meta_box_delivery_selection_field option:selected').val()=='delivery'?(a('#coderockz_woo_delivery_meta_box_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_time_field').hide(),a('#coderockz_woo_delivery_meta_box_pickup_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_pickup_field').hide(),a('#coderockz_woo_delivery_pickup_location_field').hide(),a('#coderockz_woo_delivery_meta_box_additional_field_field').hide(),a('#coderockz_woo_delivery_meta_box_datepicker').show(),a('#coderockz_woo_delivery_meta_box_time_field').show(),a('#coderockz_woo_delivery_meta_box_additional_field_field').show()):a('#coderockz_woo_delivery_meta_box_delivery_selection_field option:selected').val()=='pickup'&&(a('#coderockz_woo_delivery_meta_box_pickup_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_pickup_field').hide(),a('#coderockz_woo_delivery_pickup_location_field').hide(),a('#coderockz_woo_delivery_meta_box_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_time_field').hide(),a('#coderockz_woo_delivery_meta_box_additional_field_field').hide(),a('#coderockz_woo_delivery_meta_box_pickup_datepicker').show(),a('#coderockz_woo_delivery_meta_box_pickup_field').show(),a('#coderockz_woo_delivery_pickup_location_field').show(),a('#coderockz_woo_delivery_meta_box_additional_field_field').show()),a(document).on('change','#coderockz_woo_delivery_meta_box_delivery_selection_field',function(c){c.preventDefault(),a('.coderockz-woo-delivery-loading-image').fadeIn();var b=a(this).val();a.when(a.ajax({url:coderockz_woo_delivery_ajax_obj.coderockz_woo_delivery_ajax_url,type:'POST',data:{_ajax_nonce:coderockz_woo_delivery_ajax_obj.nonce,action:'coderockz_woo_delivery_admin_disable_max_delivery_pickup_date',deliverySelector:b},success:function(a){data=JSON.parse(a.data),v=data.disable_for_max_delivery_dates,w=data.disable_for_max_pickup_dates,z=data.disable_delivery_date_passed_time,A=data.disable_pickup_date_passed_time;}})).then(function(c){b=='delivery'?(a('#coderockz_woo_delivery_meta_box_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_time_field').hide(),a('#coderockz_woo_delivery_meta_box_pickup_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_pickup_field').hide(),a('#coderockz_woo_delivery_pickup_location_field').hide(),a('#coderockz_woo_delivery_meta_box_additional_field_field').hide(),a('#coderockz_woo_delivery_meta_box_datepicker').show(),a('#coderockz_woo_delivery_meta_box_time_field').show(),a('#coderockz_woo_delivery_meta_box_additional_field_field').show(),Y()):b=='pickup'&&(a('#coderockz_woo_delivery_meta_box_pickup_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_pickup_field').hide(),a('#coderockz_woo_delivery_pickup_location_field').hide(),a('#coderockz_woo_delivery_meta_box_datepicker').hide(),a('#coderockz_woo_delivery_meta_box_time_field').hide(),a('#coderockz_woo_delivery_meta_box_additional_field_field').hide(),a('#coderockz_woo_delivery_meta_box_pickup_datepicker').show(),a('#coderockz_woo_delivery_meta_box_pickup_field').show(),a('#coderockz_woo_delivery_pickup_location_field').show(),a('#coderockz_woo_delivery_meta_box_additional_field_field').show(),y());});});});}(jQuery));