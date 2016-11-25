<?php if($this->session->userdata('user_id')) : ?>
<script type="text/javascript">
var logout = function() { // 노출시 조작으로 인한 로그아웃을 할 수 있으므로 나중에 숨기기 바람.
  window.location="<?=MAIN_URL?>/logout?reason=timeout";
}

setTimeout(logout, 1800000);
</script>
<?php endif; ?>
</body>
</html>
