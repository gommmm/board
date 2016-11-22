<body>
	<div class="row">
		<h5>멤버 관리</h5>
	</div>

	<div class="row">
		<hr>
	</div>

	<div class="row">
		<table class="member">
			<?php $this->load->view('admin/member_list'); ?>
		</table>
	</div>
	<div class="row">
		<div class="medium-1 columns">
			선택멤버를
		</div>
		<div class="medium-2 columns">
			<select class="memberLevel">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
			</select>
		</div>
		<div class="medium-4 columns end">
			레벨으로
			<button class="changeLevel" type="button">변경</button>
			<span>|</span>
			<button class="banish">강제탈퇴</button>
			<span>|</span>
			<button class="message">쪽지</button>
		</div>
	</div>

	<div class="row text-center">
		<ul class="pagination prev-next" role="navigation" aria-label="Pagination">
		<?=$paging_str ?>
		</ul>
	</div>

	<form class="messageForm" name="messageForm" method="post">
		<input type="hidden" class="id_list" name="id_list">
		<input type="hidden" class="nick_list" name="nick_list">
	</form>

	<script>
		var page = "<?=$page ?>";

		$(document).on("click", ".checkAll", function() {
			if($(".checkAll").prop("checked") == true) {
				$(".cb").prop("checked", true);
			} else {
				$(".cb").prop("checked", false);
			}
		});

		$(document).on("click", ".changeLevel, .banish, .message", function() {
			var cn = $(this).attr("class")
			var len = $(".cb").length;
			var check_list = [];
			var id_list = [];
			var nick_list = [];
			var select_value = "";

			for(var i=0; i<len; i++) {
				if($(".cb").eq(i).prop("checked") == true) {
					check_list.push(i);
				}
			}

			for(var i=0; i<check_list.length; i++) {
				id_list.push($(".cb").eq(check_list[i]).parent().next().text());
			}

			if(id_list.length == 0) {
				alert("멤버를 선택해주세요.");
			} else {
				if(cn == "changeLevel") {
					select_value = $(".memberLevel option:selected").val();
					changeLevel(id_list, select_value, page);
				} else if(cn == "banish"){
					banish(id_list, page);
				} else {
					for(var i=0; i<check_list.length; i++) {
						nick_list.push($(".cb").eq(check_list[i]).parent().next().next().text());
					}

					message(id_list, nick_list);
				}
			}

		});

		var changeLevel = function(id_list, select_value, page) {
			$.ajax({url: "<?=MAIN_URL?>/admin/member/changeLevel", type : 'POST', data: {"id_list" : id_list, "select_value" : select_value }, success: function(result) {
					refresh(page);
			}});
		}

		var banish = function(id_list, page) {
			$.ajax({url: "<?=MAIN_URL?>/admin/member/banish", type : 'POST', data: {"id_list" : id_list}, success: function(result) {
					refresh(page);
			}});
		};

		var refresh = function(page) {
			$.ajax({url: "<?=MAIN_URL?>/admin/member/refresh", type : 'POST', data: {"page" : page}, success: function(result) {
					alert("성공적으로 반영되었습니다.");
					$(".member").html(result);
			}});
		}

		var message = function(i_list, n_list) {
			var len = i_list.length;
			var id_list = "";
			var nick_list = "";
			var messageForm = $(".messageForm");
			var targetForm =  messageForm[0];
			var x = (screen.width-620)/2;
			var y = (screen.height-500)/2;

			for(var i=0; i<len; i++) {
				if(i == len-1) {
					id_list += i_list[i];
					nick_list += n_list[i];
				} else {
					id_list += i_list[i]+",";
					nick_list += n_list[i]+",";
				}
			}

			messageForm.find(".id_list").val(id_list);
			messageForm.find(".nick_list").val(nick_list);

			window.open("", "message", "left="+ x +",top="+ y +",width=620,height=500,scrollbars=1");
			targetForm.target = "message";
			targetForm.action = "<?=MAIN_URL ?>/message/send";
			targetForm.submit();
		}
	</script>
