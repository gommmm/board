<div class=".small-8">
	<h1 class="text-center">회원가입</h1>
</div>

<form id="registerForm" name="registerForm" action="<?=MAIN_URL ?>/register/form" method="post" onsubmit="return formChk();">
<div class="row">
		<div class="medium-5 medium-centered columns">
			<label for="checkAll">
				모두 동의
				<input class="float-right" id="checkAll" type="checkbox">
			</label>
		</div>
		<div class="medium-5 medium-centered columns">
				<label for="check1">
					회원가입약관 동의
					<input class="float-right cb" id="check1" name="check1" type="checkbox">
				</label>
				<textarea class="term" readonly="readonly">회원가입약관에 동의하겠습니다.</textarea>
		</div>
		<div class="medium-5 medium-centered columns">
			<label for="check2">
				개인취급방침 동의
				<input class="float-right cb" id="check2" name="check2" type="checkbox"></label>
			<textarea class="term" readonly="readonly">개인정보취급방침에 동의하겠습니다.</textarea>
		</div>

		<div class="medium-5 medium-centered columns">
			<div class="expanded button-group">
				<a href="#" id="agree" class="button">동의</a>
				<a href="<?=MAIN_URL?>" class="button">비동의</a>
			</div>
		</div>
</div>
</form>

<script>
$("#agree").on("click", function(e) {
	e.preventDefault();
	$("#registerForm").submit();
});

$("#checkAll").on("click", function() {
	var checkbox = $(".cb");

	if($(this).prop("checked") == true) {
		console.log("작동");
		checkbox.prop("checked", true);
	} else {
		checkbox.prop("checked", false);
	}
});

function formChk() {
	var f = document.registerForm;

	if(f.check1.checked == false) {
		alert("회원가입약관에 동의하세요.");
		return false;
	}

	if(f.check2.checked == false) {
		alert("개인정보취급방침에 동의하세요.");
		return false;
	}
	return true;
}
</script>
