<div class="row">
  <div class="text-right">
    <?php if ($this->session->userdata('user_id')): ?>
    <a href="<?=MAIN_URL ?>/memberModify">정보수정</a>
    <a href="<?=MAIN_URL ?>/logout">로그아웃</a>
    <a href="<?=MAIN_URL ?>/message" class="message">쪽지</a>
    <?php if ($this->session->userdata('user_level') == 9) : ?>
    <a href="<?=MAIN_URL ?>/admin">관리자</a>
    <?php endif; ?>
    <?php else: ?>
    <a href="<?=MAIN_URL ?>/login">로그인</a>
    <a href="<?=MAIN_URL ?>/register">회원가입</a>
    <?php endif; ?>
  </div>
</div>

<div class="row">
    <h1 class="text-center"><a href="<?= MAIN_URL ?>">게시판</a></h1>
</div>

<div class="row">
  <div class="medium-2 columns">
    <!-- 게시판 메뉴 -->
    <ul class="menu vertical">
       <?php foreach ($menu_list as $menu) : ?>
       <?php if ($menu['is_group'] == 1) : ?>
       <li class="group"><strong><?=$menu['bc_name'] ?></strong></li>
       <?php else : ?>
       <li class="<?=$menu['indent'] == 1 ? 'indent' : '' ?>">
       <a href="<?=MAIN_URL ?>/<?=$menu['bc_code'] ?>/1"><?=$menu['bc_name']?></a>
       </li>
       <?php endif; ?>
       <?php endforeach ?>
    </ul>
  </div>

  <!-- 게시판 종류에 따라 출력 -->
  <div class="medium-10 columns content">
    <!-- 컨트롤러에서 다 처리후 보여줄 view 값을 만들어서 처리하기 -->
    <?php $this->load->view('board/'.$content); ?>
  </div>

</div>

<!-- 임시 실시간 쪽지 알림 테스트 코드 -->
<div class="popup" style="display:none; position: fixed; background: #f5e28c; right: 0; bottom: 0; z-index: 9999; width: 200px; height: 100px;">
  <a href="#" class="close">x</a>
  <p>쪽지가 도착했습니다.</p>
</div>

<script>
var user_id = "<?=$this->session->userdata('user_id')?>";
var user_level = "<?=$this->session->userdata('user_level')?>";
var code = "<?=$this->uri->segment(1)?>"
var url  = "<?=MAIN_URL?>"
</script>

<script>
  $(document).on("click", ".message", function() {
    var x = (screen.width-640)/2;
    var y = (screen.height-640)/2;

    window.open('<?=MAIN_URL ?>/message', 'message', 'left='+ x +',top='+ y +',width=640,height=640');
    return false;
  })
</script>


<script src="<?=NODE?>/socket.io/socket.io.js"></script>
<script>

          var socket = io('<?=NODE?>');
          var socket_id = '';

          $( document ).ready(function() {

          /*
          socket.on('update', function(s_id) {
            socket_id = s_id;
          });

          socket.on('new_message', function(val) {
            if(socket_id == val){
            console.log("aaa");
              $("div.popup").show();
            }
          });*/

          $(".close").on("click", function(e) {
            e.preventDefault();
            $(".popup").hide();
          });

          socket.on('send id', function(id) {
            if(user_id == id) {
              $(".popup").slideDown(5000);
            }
          });
        });


</script>
