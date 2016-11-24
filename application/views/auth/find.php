<?php $this->load->view('auth/nav'); ?>

<div>
	<div class=".small-8">
		<h1 class="text-center"><?=$title?></h1>
	</div>

	<div class="medium-6 medium-centered columns">
		<hr>
	</div>

	<form class="authNoForm" method="post" action="<?=MAIN_URL?>/login/<?=$mode?>">
	<div class="row">
		<div class="medium-6 medium-centered columns">
		<p>본인확인 이메일 주소와 입력한 이메일 주소가 같아야, 인증번호를 받을 수 있습니다.</p>
			<div class="small-2 columns">
				<label class="text-right" for="userId">이메일 주소</label>
			</div>
			<div class="small-6 columns">
				<input id="email" name="email" type="text">
			</div>
			<div class="small-4 columns">
				<input type="button" class="tiny hollow button btnEmailAuthNo" value="인증번호 받기">
			</div>
		</div>

		<div class="medium-6 medium-centered columns">
			<div class="small-6 small-offset-2 columns end">
				<input type="text" id="authNo" name="authNo" maxlength="6" disabled="disabled" placeholder="인증번호 6자리 숫자 입력">
			</div>
		</div>
	</div>

	<div class="medium-6 medium-centered columns">
			<button type="submit" class="hollow button float-center btnAuth">다음</button>
	</div>
	<input type="hidden" name="userId" value="<?=isset($userId) ? $userId : ''?>">
	</form>
</div>

<script>
	$(document).on("click", ".btnEmailAuthNo", function() {
		var email = $("input[name=email]").val();
		var pattern = /[0-9a-zA-Z_-]+@[.0-9a-zA-Z_-]+/;
		var userId = "<?=isset($userId) ? $userId : ''?>";
		var url = "<?=MAIN_URL?>" + "/login/<?=$this->uri->segment(2)?>";
		var data = {};

		if(email == "") {
			alert("이메일을 입력해주세요.");
			return false;
		}

		if(pattern.test(email) == false) {
			alert("이메일을 올바른 형식으로 입력해주세요.");
			return false;
		}

		data['email'] = email;
		if(userId != "") {
			data['userId'] = userId;
		}

		$.ajax({
					type:"POST",
					url:url,
					data:data,
					success:function(args){
						if(args == "success") {
							alert("인증번호를 발송했습니다.\n인증번호가 오지 않으면 입력하신 정보가 회원정보와 일치하는지 확인해주세요.");
							$("#authNo").attr("disabled", false);
						}
					}
		});
	});

	$(document).on("click", ".btnAuth", function(e) {
				var authNo = $("#authNo").val();
				var url = "<?=MAIN_URL?>" + "/login/authNoCheck";
				var form = $(".authNoForm");

				e.preventDefault();

				if(authNo.length != 6) {
					alert("인증번호 6자리를 입력하세요.");
					return false;
				}

				$.ajax({
					type:"POST",
					url:url,
					data:{"authNo": authNo},
					success:function(result){
						if(result == 'same') {
							form.submit();
						} else {
							alert("인증번호가 맞지 않습니다.");
						}
					}
		});
	});

	$(document).on("keydown", "#authNo", function(e) {
		var keycode = e.which ? e.which : e.keyCode;

		if(!((keycode > 47 && keycode < 58) || (keycode >=96 && keycode <= 105) || (keycode > 36 && keycode < 41) || keycode == 8 || keycode == 46 || keycode == 116 || keycode == 17 || (e.ctrlKey && (keycode == 90 || keycode == 88 || keycode == 67 || keycode == 86)))) {
			alert("숫자만 입력해 주세요.");
			$(this).val("");
		}
	});

	$(document).on("keyup", "#authNo", function() {
		var val = $(this).val();
		var pattern = /[a-zA-z]/i;
		var check = pattern.test(val);

		if(check == true) {
			alert("숫자만 입력해 주세요.");
			$(this).val("");
		}
	});
</script>
