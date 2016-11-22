<div class="content">
  <div class="row">
    <p class="small-5 columns end">회원레벨 변경</p>
  </div>
  <form name="changeForm" id="changeForm" method="post" action="changeLevel">
    <input type="hidden" name="id_list" value="<?=$id_list?>">
    <input type="hidden" name="referer" value="<?=$referer?>">

    <div class="row">
      <div class="small-5 columns end">
        <select name="select_value">
            <?php for($i=1; $i<9; $i++) : ?>
              <?php if($i === $level) : ?>
              <option value="<?=$i ?>" selected="selected"><?=$i ?></option>
              <?php else : ?>
              <option value="<?=$i ?>"><?=$i ?></option>
              <?php endif; ?>
            <?php endfor; ?>
        </select>
      </div>
    </div>
  </form>
</div>

<div class="row text-center">
<a class="submit" href="#">확인</a>
<a class="cancel" href="#">취소</a>
</div>

<script>
$(".submit").on("click", function(e) {
  var moveForm = $("#changeForm");
  e.preventDefault();
  moveForm.attr("target", "parent");
  moveForm.submit();
  window.close();
})

$(".cancel").on("click", function(e) {
  e.preventDefault();
  window.close();
});
</script>
