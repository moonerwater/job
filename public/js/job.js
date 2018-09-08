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
				text: '地推拉访'
			}, {
				text: '商品代理'
			}, {
				text: '网赚试玩'
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
			//------------------------------
			//工作类别
			var jobtype = new $.PopPicker();
			jobtype.setData([{
				text: '传单派发'
			}, {
				text: '促销导购'
			}, {
				text: '地推拉访'
			}, {
				text: '商品代理'
			}, {
				text: '网赚试玩'
			}, {
				text: '其他'
			}]);
			var setjobtype = doc.getElementById('jobtype');
			if(setjobtype){
				setjobtype.addEventListener('tap', function(event) {
					jobtype.show(function(items) {
						setjobtype.value = JSON.stringify(items[0].text).replace(/\"/g,'');
						//返回 false 可以阻止选择框的关闭
						//return false;
						//userSex.dispose();
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
		});
	})(mui, document);


});