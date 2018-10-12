var indexload;



function checkName( name ) {
    var regu = /[\u4E00-\u9FA5]/;
    var re = new RegExp(regu);
    if (re.test(name)) {
        return true;  
    }
    else {
        return false;
    }
};

var times = 0;
function mobileMsg(){
    if(times<60){
        $('.getcaptcha').hide();
        $('.waiting').show().text('等待'+(60-times)+'秒');
        // $('.getcaptcha').attr('disable','disable').removeClass('getcaptcha').addClass('waiting');
        // $('.waiting').text('等待'+(60-times)+'秒');
        times = times + 1;
        setTimeout(function(){mobileMsg()}, 1000);
    }else{
        times = 0;
        $('.waiting').hide()
        $('.getcaptcha').removeAttr('disabled');
        $('.getcaptcha').show();
        // $('.waiting').removeAttr('disable').removeClass('waiting').addClass('getcaptcha');
        // $('.getcaptcha').text('发送验证码');
    }
}

function checkMobile( strMobile ) {
    var regu = /^[1][3-9][0-9]{9}$/;
    var re = new RegExp(regu);
    if (re.test(strMobile)) {
        return true;  
    }
    else {
        return false;
    }
}

function checkRate(input) {
　　var re = /^[0-9]+.?[0-9]*$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/ 
　　if (!re.test(input)) {
　　　　return false;
　　}
}

var $_GET = (function() {
    var url = document.location.href;
    var param = url.split("?");
    var get = {};
    if ("string" == typeof(param[1])) {
        var pair = param[1].split("&");
        for (var i in pair) {
            var j = pair[i].split("=");
            get[j[0]] = j[1]? j[1]: "";
        }
    }
    return get;
})();



$(document).ready(function(){

	$("#Sign").on('tap',function(){
		layer.msg('签到成功！<br>积分 +5 ',{time:2500});
		$(this).attr('disabled',true);
	});

	(function($, doc) {
		$.init();
		$.ready(function() {
			//性别
			var userSex = new $.PopPicker();
			userSex.setData([{
				text: '男'
			}, {
				text: '女'
			}]);
			var setSex = doc.getElementById('changeUserSex');
			if(setSex){
				setSex.addEventListener('tap', function(event) {
					userSex.show(function(items) {
						setSex.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userSex.dispose();
					});
				}, false);
			}
			//------------------------------
			//生日
			var userBirthday = doc.getElementById('userBirthday');
			if(userBirthday){
				userBirthday.addEventListener('tap', function() {
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var picker = new $.DtPicker(options);
					picker.show(function(rs) {
						/*
						 * rs.value 拼合后的 value
						 * rs.text 拼合后的 text
						 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
						 * rs.m 月，用法同年
						 * rs.d 日，用法同年
						 * rs.h 时，用法同年
						 * rs.i 分（minutes 的第二个字母），用法同年
						 */
						userBirthday.value = rs.text;
						picker.dispose();
					});
				}, false);
			}
			//------------------------------
			//学历
			var userDegree = new $.PopPicker();
			userDegree.setData([{
				text: '博士'
			}, {
				text: '硕士'
			}, {
				text: '本科'
			}, {
				text: '大专'
			}, {
				text: '高中/中专'
			}, {
				text: '初中'
			}, {
				text: '小学'
			}]);
			var setDegree = doc.getElementById('userDegree');
			if(setDegree){
				setDegree.addEventListener('tap', function(event) {
					userDegree.show(function(items) {
						setDegree.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userDegree.dispose();
					});
				}, false);
			}
			//-------------------------------
			//工作年限
			var userJobyear = new $.PopPicker();
			userJobyear.setData([{
				text: '1年以下'
			}, {
				text: '1-3年'
			}, {
				text: '3-5年'
			}, {
				text: '5年以上'
			}]);
			var setJobyear = doc.getElementById('userJobyear');
			if(setJobyear){
				setJobyear.addEventListener('tap', function(event) {
					userJobyear.show(function(items) {
						setJobyear.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userJobyear.dispose();
					});
				}, false);
			}
			//-------------------------------
			//工作领域
			var userJobfield = new $.PopPicker();
			userJobfield.setData([{
				text: '传单派发'
			}, {
				text: '促销导购'
			}, {
				text: '督导'
			}, {
				text: '地推拉访'
			}, {
				text: '商品代理'
			}, {
				text: '网赚试玩'
			}, {
				text: '快递'
			}, {
				text: '调研'
			}, {
				text: '送餐员'
			}, {
				text: '促销'
			}, {
				text: '礼仪'
			}, {
				text: '安保'
			}, {
				text: '销售'
			}, {
				text: '服务员'
			}, {
				text: '临时工'
			}, {
				text: '校内'
			}, {
				text: '设计'
			}, {
				text: '文员'
			}, {
				text: '派单'
			}, {
				text: '模特'
			}, {
				text: '实习'
			}, {
				text: '家教'
			}, {
				text: '演出'
			}, {
				text: '客服'
			}, {
				text: '翻译'
			}, {
				text: '其它'
			}]);
			var setJobfield = doc.getElementById('userJobfield');
			if(setJobfield){
				setJobfield.addEventListener('tap', function(event) {
					userJobfield.show(function(items) {
						setJobfield.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userJobyear.dispose();
					});
				}, false);
			}
			//-------------------------------
			//性别要求
			var userJobsex = new $.PopPicker();
			userJobsex.setData([{
				text: '不限'
			}, {
				text: '男'
			}, {
				text: '女'
			}]);
			var setJobsex = doc.getElementById('jobsex');
			if(setJobsex){
				setJobsex.addEventListener('tap', function(event) {
					userJobsex.show(function(items) {
						setJobsex.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userJobyear.dispose();
					});
				}, false);
			}
			//------------------------------
			//计薪方式
			var paytype = new $.PopPicker();
			paytype.setData([{
				text: '小时'
			}, {
				text: '天'
			}, {
				text: '周'
			}, {
				text: '月'
			}, {
				text: '次'
			}, {
				text: '件'
			}]);
			var setpaytype = doc.getElementById('paytype');
			if(setpaytype){
				setpaytype.addEventListener('tap', function(event) {
					paytype.show(function(items) {
						setpaytype.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userSex.dispose();
					});
				}, false);
			}
			//------------------------------
			//结算时间
			var paytime = new $.PopPicker();
			paytime.setData([{
				text: '日结'
			}, {
				text: '周结'
			}, {
				text: '月结'
			}, {
				text: '完工结'
			}, {
				text: '次月结'
			}, {
				text: '半月结'
			}]);
			var setpaytime = doc.getElementById('paytime');
			if(setpaytime){
				setpaytime.addEventListener('tap', function(event) {
					paytime.show(function(items) {
						setpaytime.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userSex.dispose();
					});
				}, false);
			}
			//------------------------------
			//开始日期
			var jobstarday = doc.getElementById('jobstarday');
			if(jobstarday){
				jobstarday.addEventListener('tap', function() {
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var picker = new $.DtPicker(options);
					picker.show(function(rs) {
						jobstarday.value = rs.text;
						picker.dispose();
					});
				}, false);
			}
			//------------------------------
			//结束日期
			var jobendday = doc.getElementById('jobendday');
			if(jobendday){
				jobendday.addEventListener('tap', function() {
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var picker = new $.DtPicker(options);
					picker.show(function(rs) {
						jobendday.value = rs.text;
						picker.dispose();
					});
				}, false);
			}
			//------------------------------
			//开始时间
			var jobstartime = doc.getElementById('jobstartime');
			if(jobstartime){
				jobstartime.addEventListener('tap', function() {
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var picker = new $.DtPicker(options);
					picker.show(function(rs) {
						jobstartime.value = rs.text;
						picker.dispose();
					});
				}, false);
			}
			//------------------------------
			//结束时间
			var jobendtime = doc.getElementById('jobendtime');
			if(jobendtime){
				jobendtime.addEventListener('tap', function() {
					var optionsJson = this.getAttribute('data-options') || '{}';
					var options = JSON.parse(optionsJson);
					var picker = new $.DtPicker(options);
					picker.show(function(rs) {
						jobendtime.value = rs.text;
						picker.dispose();
					});
				}, false);
			}
			//-----------------------------------------
			//城市列表
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			var cityPicker3 = new $.PopPicker({
				layer: 3
			});
			cityPicker3.setData(cityData);
			var showCityPickerButton = doc.getElementById('jobarea');
			var jobprovince = doc.getElementById('jobprovince');
			var jobcity = doc.getElementById('jobcity');
			var jobdistrict = doc.getElementById('jobdistrict');
			if(showCityPickerButton){
				//var cityResult3 = doc.getElementById('cityResult3');
				showCityPickerButton.addEventListener('tap', function(event) {
					cityPicker3.show(function(items) {
					// 	cityResult3.innerText = "你选择的城市是:" + _getParam(items[0], 'text') + " " + _getParam(items[1], 'text') + " " + _getParam(items[2], 'text');
					// 	//返回 false 可以阻止选择框的关闭
					// 	//return false;
						showCityPickerButton.value = _getParam(items[0], 'text') + _getParam(items[1], 'text') +_getParam(items[2], 'text');
						jobprovince.value = _getParam(items[0], 'text');
						jobcity.value = _getParam(items[1], 'text');
						jobdistrict.value = _getParam(items[2], 'text');
					});
				}, false);
			}
		});
	})(mui, document);


});