<div class=".small-8">
	<h1 class="text-center"><?=$title ?></h1>
</div>

<form class="registerForm" name="registerForm" method="post" action="<?=MAIN_URL ?><?php echo $type == 'w' ? '/register/add' : '/memberModify/modify' ?>" onsubmit="return formChk();">
<div class="row">

	<?php if($type == 'w') : ?>
	<div class="medium-4 medium-centered columns">
	<input type="text" id="m_id" name="m_id" maxlength="20" placeholder="아이디">
	<span class="check"></span>
	</div>

	<div class="medium-4 medium-centered columns">
	<input type="text" id="email" name="email" placeholder="이메일">
	<span class="check"></span>
	</div>
	<?php endif; ?>

	<div class="medium-4 medium-centered columns">
	<input type="text" id="m_name" name="m_name" placeholder="닉네임">
	<span class="check"></span>
	</div>

	<div class="medium-4 medium-centered columns">
	<input type="password" id="m_pass" name="m_pass" placeholder="비밀번호">
	</div>

	<div class="medium-4 medium-centered columns">
	<input type="password" id="m_pass2" name="m_pass2" placeholder="비밀번호 재확인">
	</div>

	<div class="medium-4 medium-centered columns">
		<hr />
	</div>

	<div class="medium-4 medium-centered columns">
	<p>아래 이미지를 보이는 대로 입력해주세요.</p>
	<div class="captcha">
	<?=$captcha?>
	</div>
	<input type="text" id="captcha_word" name="captcha_word" maxlength="6" placeholder="자동입력 방지문자">
	</div>

	<div class="medium-4 medium-centered columns">
	<button class="expanded button" type="submit"><?=$submit ?></button>
	</div>

</div>
</form>
<script>
$(document).on("blur", "#m_id, #email, #m_name", function() {
	 var url ="<?=MAIN_URL?>/login/check";
	 var checkId = $(this).parent().find(".check");
	 var id = $(this).attr("id");
	 var val = $(this).val();
	 var data = {};

	 data[id] = val;
	 $.ajax({
				type:"POST",
				url:url,
				data:data,
				success:function(result){
						if(result == "true") {
							checkId.addClass("exist");
							checkId.html("이미 존재합니다.");
						} else {
							checkId.removeClass("exist");
							checkId.html("");
						}
				}
		});
});

function formChk() {
	var f = document.registerForm;
	var kor = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
	var pattern = /[0-9a-zA-Z_-]+@[.0-9a-zA-Z_-]+/;
	var check = $(".exist");
	var check_text = "";
	var captcha_word = "<?=$captcha_word?>"

	<?php if($type != 'm') : ?>
	if(f.m_id.value == "") {
		alert("아이디를 입력해주세요.");
		return false;
	}
	if(f.email.value == "") {
		alert("이메일을 입력해주세요.");
		return false;
	}

	if(kor.test(f.m_id.value) == true) {
		alert("아이디는 영어와 숫자로 이루어진 아이디만 가능합니다.");
		return false;
	}

	if(pattern.test(f.email.value) == false) {
		alert("이메일을 올바른 형식으로 입력해주세요.");
		return false;
	}
	<?php endif; ?>

	if(f.m_name.value == "") {
		alert("닉네임을 입력해주세요.");
		return false;
	}

	if(f.m_pass.value == "") {
		alert("비밀번호를 입력해주세요.");
		return false;
	}
	if(f.m_pass.value != f.m_pass2.value) {
		alert("비밀번호가 다릅니다.");
		return false;
	}

	if(f.captcha_word.value != captcha_word) {
		alert("이미지의 번호와 입력하신 번호가 틀립니다.");
		return false;
	}

	if(check.length > 0) {
		check_text = $(check[0]).prev().prev().text();

		alert("가입된 " + Josa(check_text, '이') + " 있습니다.\n다른 " + Josa(check_text, '을') + " 입력해주세요.");
		return false;
	}

	return true;
}

function Josa(txt, josa) // 한글 조사붙이는 함수
{
	var code = txt.charCodeAt(txt.length-1) - 44032;

	// 원본 문구가 없을때는 빈 문자열 반환
	if (txt.length == 0) return '';

	// 한글이 아닐때
	if (code < 0 || code > 11171) return txt;

	if (code % 28 == 0) return txt + Josa.get(josa, false);
	else return txt + Josa.get(josa, true);
}
Josa.get = function (josa, jong) {
	// jong : true면 받침있음, false면 받침없음

	if (josa == '을' || josa == '를') return (jong?'을':'를');
	if (josa == '이' || josa == '가') return (jong?'이':'가');
	if (josa == '은' || josa == '는') return (jong?'은':'는');
	if (josa == '와' || josa == '과') return (jong?'와':'과');

	// 알 수 없는 조사
	return '**';
}
</script>
