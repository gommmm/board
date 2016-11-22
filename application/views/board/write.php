	    <h2>글쓰기</h2>
		  <form name="writeForm" id="writeForm" method="post" enctype="multipart/form-data" action="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?><?php echo mode($type, isset($board['b_idx']) ? $board['b_idx'] : '') ?>" onsubmit="return formChk();">
		  <input type="hidden" name="bc_code" value="<?=$board_config['bc_code'] ?>" />
			<div class="column row">
				<div class="medium-1 columns">
					<label for="m_name">작성자</label>
				</div>
				<div class="medium-3 columns end">
					<input type="text" id="m_name" name="m_name" value="<?php echo $this->session->userdata('user_name') != '' ? $this->session->userdata('user_name').'" readonly="readonly' : ''; ?>" />
				</div>
			</div>
			<div class="column row">
				<div class="medium-1 columns">
					<label for="b_title">글제목</label>
				</div>
				<div class="medium-4 columns <?=$user_level !== 9 ? 'end' : '' ?>">
					<input type="text" id="b_title" name="b_title"  value="<?=$type == 'm' || $type == 'r' && isset($board['title']) ? $board['title'] : '' ?>" />
				</div>
				<?php if($user_level === 9) : ?>
				<div class="medium-2 columns end">
					<input type="checkbox" id="notice" name="notice" value="1" <?=$type == 'm' && $board['notice'] == 1 ? 'checked="checked"' : '' ?>>
					<label for="notice">공지</label>
				</div>
				<?php endif; ?>
			</div>

			<div class="file_select">
			<input type="button" id="b_file_button" value="파일 선택">
			<?php if($type != 'm') :?>
		  <input type="file" class="b_file" id="b_file1" name="b_file[]" style="display:none" />
			<?php endif; ?>
			</div>

		  <div class="column row medium-collapse">
				<div class="medium-9 columns end">
		  		<textarea id="b_content" name="b_content" rows="10" cols="100" style="width:100%;"><?php echo $type == 'r' ? '>' : '' ?> <?=$type == 'm' || $type == 'r' ? htmlspecialchars($board['content']) : '' ?></textarea>
				</div>
			</div>

			<div class="file_list">
				<?php if($type == 'm' && !empty($file_list)) : ?>
				<label class="item">첨부파일</label>
				<?php foreach($file_list as $row) : ?>
				<div id="<?=$row['id']?>" class="file"><span class="filename"><?=$row['filename']?></span><a class="file_remove" href="#">x</a></div>
				<?php endforeach; ?>
			  <?php endif;?>
			</div>

			<input type="submit" value="쓰기" />

			</form>
		  <script>
		  var oEditors = [];

		  nhn.husky.EZCreator.createInIFrame({
				oAppRef: oEditors,
				elPlaceHolder: "b_content",
				sSkinURI: "<?=PLUGIN?>seditor/SmartEditor2Skin.html",
				fCreator: "createSEditor2"
		  });

		  function formChk() {
			var f = document.writeForm;

			oEditors.getById["b_content"].exec("UPDATE_CONTENTS_FIELD", []);

			if(f.b_title.value == "") {
				alert("글제목을 입력해주세요.");
				return false;
			}

			<?php if($board_config['bc_use_secret'] == 1) : ?>
			if(f.b_is_secret.checked == false && b_pass.value != "") {
				alert("비밀글여부를 체크하세요.");
				return false;
			}
			if(f.b_is_secret.checked == true && b_pass.value == "") {
				alert("비밀번호를 입력해주세요.");
				return false;
			}
			<?php endif; ?>

			if(f.b_content.value == "") {
				alert("글내용을 입력해주세요.");
				return false;
			}
			return true;
		  }

		  function removeTag(str) { // 지금은 <p>&nbsp</p> 이 태그만 제거하게 했지만 추후에 태그 및 공백문자 제거 함수로 만들기!
				return str.replace(/(\<p\>&nbsp;\<\/p\>)/gi, "");
		   }
		  </script>
