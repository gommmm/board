<?php $this->load->view('auth/nav'); ?>

<div>
	<div class=".small-8">
		<h1 class="text-center">비밀번호 찾기</h1>
	</div>

	<div class="medium-6 medium-centered columns">
		<hr>
	</div>

	<form id="idInputForm" method="post" action="<?=MAIN_URL?>/login/findPass">
	<div class="row">
		<div class="medium-6 medium-centered columns">
		<p>비밀번호를 찾고자 하는 아이디를 입력해 주세요.</p>
			<div class="small-2 small-offset-1 columns">
				<label class="text-right" for="userId">아이디</label>
			</div>
			<div class="small-6 columns end">
				<input id="userId" name="userId" type="text">
			</div>
		</div>
	</div>

	<div class="medium-6 medium-centered columns">
			<button type="submit" class="hollow button float-center">다음</button>
	</div>
	</form>
</div>

<script>
$("#idInputForm").bind("submit", function() {
	var userId = $("#userId").val();

	if(userId == "") {
		alert("아이디를 입력해주세요.");
		return false;
	}
});
</script>
