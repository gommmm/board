<p class="title">MySql 정보<p>
<form class="sInput" name="Form" method="post" action="<?=MAIN_URL?>/install/step2" onsubmit="return formChk();">

<div class="row">
	<ul>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="host" name="host" placeholder="호스트명" />
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="db_id" name="db_id" placeholder="데이터베이스 아이디" />
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="password" id="db_password" name="db_password" placeholder="데이터베이스 비밀번호" />
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="db_name" name="db_name" placeholder="데이터베이스명" />
				</div>
			</li>
		</div>
	</ul>
</div>

<p class="title">관리자 정보</p>

<div class="row">
	<ul>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="admin_id" name="admin_id" placeholder="관리자 아이디" />
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="admin_email" name="admin_email" placeholder="관리자 이메일 주소">
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="text" id="admin_name" name="admin_name" placeholder="관리자 닉네임" />
				</div>
			</li>
		</div>
		<div class="row">
			<li>
				<div class="medium-3 columns medium-centered">
					<input type="password" id="admin_password" name="admin_password" placeholder="관리자 비밀번호" />
				</div>
			</li>
		</div>
	</ul>
<div class="row">

<div class="text-center">
	<input type="submit" class="button" value="설치" />
</div>

</form>
<script>
// 폼 검증 함수
function formChk() {
	var form = $(".sInput");
	var pattern = /[0-9a-zA-Z_-]+@[.0-9a-zA-Z_-]+/;

	if(form.find("#host").val() == "") {
		alert("호스트명을 입력해주세요.");
		return false;
	}

	if(form.find("#db_id").val() == "") {
		alert("데이터베이스 아이디를 입력해주세요.");
		return false;
	}

	if(form.find("#db_password").val() == "") {
		alert("데이터베이스 비밀번호를 입력해주세요.");
		return false;
	}

	if(form.find("#db_name").val() == "") {
		alert("데이터베이스명을 입력해주세요.");
		return false;
	}

	if(form.find("#admin_id").val() == "") {
		alert("관리자 아이디를 입력해주세요.");
		return false;
	}

	if(form.find("#admin_email").val() == "") {
		alert("관리자 이메일을 입력해주세요.");
		return false;
	}

	if(pattern.test(form.find("#admin_email").val()) == false) {
		alert("이메일을 올바른 형식으로 입력해주세요.");
		return false;
	}

	if(form.find("#admin_name").val() == "") {
		alert("관리자 이름을 입력해주세요.");
		return false;
	}

	if(form.find("#admin_password").val() == "") {
		alert("관리자 비밀번호를 입력해주세요.");
		return false;
	}
	return true;
}
</script>
