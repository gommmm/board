<?php $this->load->view('auth/nav'); ?>

<div>
	<div class=".medium-8">
		<h1 class="text-center">비밀번호 재설정</h1>
	</div>

	<div class="medium-6 medium-centered columns">
		<hr>
	</div>

	<form id="passForm" method="post" action="<?=MAIN_URL?>/login/changePass">
	<div class="row">
		<div class="medium-6 medium-centered columns">
			<p>
				새로 사용할 비밀번호를 입력해 주세요.
				<br /> 사용하시던 비밀번호는 저희도 알 수 없습니다. 비밀번호를 새로 설정해 주세요.
			</p>
		</div>
		<div class="medium-6 medium-centered columns">
			<div class="small-6 small-offset-2 columns end">
				<p>아이디: <strong><?=$userId?></strong></p>
			</div>
		</div>

		<div class="medium-6 medium-centered columns">
			<div class="small-6 small-offset-2 columns end">
				<input type="password" id="pass" name="pass" placeholder="새 비밀번호">
			</div>
		</div>

		<div class="medium-6 medium-centered columns">
			<div class="small-6 small-offset-2 columns end">
				<input type="password" id="pass2" name="pass2" placeholder="새 비밀번호 확인">
			</div>
		</div>
	</div>

	<div class="medium-6 medium-centered columns">
			<div class="small-6 small-offset-2 columns end">
				<button type="submit" class="hollow button float-center">확인</button>
			</div>
	</div>
	<input type="hidden" name="userId" value="<?=$userId?>">
	</form>
</div>

<script>
$(document).on("submit", "#passForm", function() {
	var pass = $("#pass").val();
	var pass2 = $("#pass2").val();

	if(pass != pass2) {
		alert("입력한 비밀번호가 서로 다릅니다.");
		return false;
	}
});
</script>
