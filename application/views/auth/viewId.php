<?php $this->load->view('auth/nav'); ?>

<div>
  <div class=".small-8">
    <h1 class="text-center">아이디 찾기</h1>
  </div>

  <div class="medium-6 medium-centered columns">
    <hr>
  </div>

  <form>
  <div class="row">
    <div class="medium-6 medium-centered columns">
      <dl>
        <dt>고객님의 아이디와 일치하는 아이디입니다.</dt>
        <dd><?=$member['m_id']?></dd>
      </dl>
    </div>
  </div>

  <div class="medium-6 medium-centered columns">
      <div class="text-center">
      <a class="button" href="<?=MAIN_URL?>/login">로그인하기</a>
      <a class="button" href="<?=MAIN_URL?>/login/findPass">비밀번호 찾기</a>
      </div>
  </div>
  </form>
</div>
