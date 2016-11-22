<div class=".small-8">
  <h1 class="text-center">로그인</h1>
</div>

<form class="loginForm" name="loginForm" method="post" action="<?=MAIN_URL?>/login/loginCheck" onsubmit="return formCheck();">
<div class="row">
  <input type="hidden" name="prev_url" value="<?=$prev_url; ?>">
  <div class="medium-5 medium-centered columns">
  <input type="text" id="user" name="user" maxlength="20" placeholder="아이디">
  </div>

  <div class="medium-5 medium-centered columns">
  <input type="password" id="pass" name="pass" maxlength="20" placeholder="비밀번호">
  </div>

  <div class="medium-5 medium-centered columns">
  <button class="expanded button" type="submit">로그인</button>
  </div>

  <div class="medium-5 medium-centered columns">
  <hr>
  </div>
  <div class="medium-5 medium-centered columns">
    <div class="text-center">
      <a href="<?=MAIN_URL?>/login/findId">아이디 찾기</a>
      <span>|</span>
      <a href="<?=MAIN_URL?>/login/findPass">비밀번호 찾기</a>
      <span>|</span>
      <a href="<?=MAIN_URL ?>/register">회원가입</a>
    </div>
  </div>
</div>
</form>
<script>
function formCheck(){
  var f = document.loginForm;

  if(f.user.value == "") {
    alert("아이디를 입력해주세요.");
    return false;
  }

  if(f.pass.value == "") {
    alert("비밀번호를 입력해주세요.");
    return false;
  }

  return true;
}
</script>
