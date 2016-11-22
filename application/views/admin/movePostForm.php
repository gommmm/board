<p>게시물 이동</p>
<form name="moveForm" id="moveForm" method="post" action="movePost">
  <input type="hidden" name="id_list" value="<?=$id_list?>">
  <input type="hidden" name="referer" value="<?=$referer?>">

  <select name="menu_name">
    <?php foreach($menu_list as $index => $row) : ?>
      <option value="<?=$row['bc_code']?>"><?=$row['bc_name']?></option>
    <?php endforeach; ?>
  </select>
</form>

<a class="submit" href="#">확인</a>
<a class="cancel" href="#">취소</a>

<script>
$(".submit").on("click", function(e) {
  var moveForm = $("#moveForm");
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
