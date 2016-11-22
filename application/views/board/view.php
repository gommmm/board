

	   <h4><?=$board['title'] ?></h4>
		 <hr />
     <h6 class="nickname" id="article_<?=$board['m_id'] ?>">
		     <?=$board['name'] ?>
     </h6>

		 <div class="column row text-right">
		 <?php if(count($file_list) != 0) : ?>
		 <label>첨부파일</label>
	 	 <ul class="file">
		 <?php foreach ($file_list as $row) : ?>
		 <li><a href="<?=MAIN_URL ?>/<?=$board_config['bc_code']?>/download/<?=$row['id']?>"><?=$row['filename'] ?></a></li>
	 	 <?php endforeach; ?>
	 	 </ul>
	 	 <?php endif; ?>
	 	 </div>

		 <div class="content">
		 <p><?=$board['content'] ?></p>
     </div>

		 <a href="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/1">목록보기</a>
		 <?php if($user_level > 0 && $board_config['bc_write_level'] <= $user_level) : ?>
		 <a href="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/reply/<?=$board['b_idx'] ?>">답글쓰기</a>
	 	 <?php endif; ?>

		 <?php if($this->session->userdata('user_level') == 9 || $this->session->userdata('user_id') == $board['m_id']) : ?>
       <?php if($this->session->userdata('user_id') == $board['m_id']) : ?>
         <a href="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/modify/<?=$board['b_idx'] ?>">글수정</a>
       <?php endif; ?>
     <a href="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/delete/<?=$board['b_idx'] ?>">글삭제</a>
		 <?php endif; ?>
		 <br />

		<ul class="c_list comment">
			<?php foreach($comment_list as $comment) : ?>
			<li class="<?=$comment['p_idx'] != 0 ? 'reply' : '' ?>">
				<?php if($comment['deleted']) : ?>
				<p class="deleted">삭제된 댓글입니다.</p>
				<?php else : ?>
				<div class="cmt_info">
					<span class="name"><?php echo $comment['name'] ?></span>
					<span class="date"><?php echo $comment['c_regdate'] ?></span>
					<?php if($user_level > 0 && $board_config['bc_comment_level'] <= $user_level) : ?>
					<button class="btnReply">답글</button>
					<?php endif; ?>

					<?php if($this->session->userdata('user_level') == 9 || $this->session->userdata('user_id') == $comment['m_id']) : ?>
            <?php if($this->session->userdata('user_id') == $comment['m_id']) : ?>
              <button class="btnEdit">수정</button>
            <?php endif; ?>
					<button class="btnDelete">삭제</button>
					<?php endif; ?>
				</div>
				<p class="cmt"><?php if($comment['cp_name'] != '' && $comment['c_seq'] != 2) : ?><span class="re_p_nick"><a><?=$comment['cp_name']?></a></span><?php endif; ?><span class="content"><?=$comment['s_content'] ?></span></p>
				<input type="hidden" name="cmt_id" value="<?=$comment['c_idx'] ?>" />
				<input type="hidden" name="ref_id" value="<?=$comment['p_idx'] != 0 ? $comment['p_idx'] : $comment['c_idx'] ?>" />
				<?php endif; ?>
			</li>
			<li class="dashed"></li>
			<?php endforeach; ?>
		</ul>

		 <?php if($this->session->userdata('user_level') >= $board_config['bc_comment_level']) : ?>
			<form id="cmtForm" name="commentForm" method="post" action="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/comment">
				<input type="hidden" name="bc_code" value="<?=$board_config['bc_code'] ?>" />
				<input type="hidden" name="b_idx" value="<?=$board['b_idx'] ?>" />
				<input type="hidden" id="m_name" name="m_name" value="<?=$this->session->userdata('user_name')?>" />
				<div class="column row comment">
				<div class="medium-10 columns">
				<textarea id="c_content" name="c_content"></textarea>
				</div>
				<div class="medium-2 columns">
				<input class="cmt_submit" type="submit" value="등록" />
				</div>
				</div>
			</form>
	     <?php endif; ?>

		<div class="near_posts">
		<?php if($prev_post != '') : ?>
		<a href="./<?=$prev_post['b_idx']?>">이전글</a><?=' '.$prev_post['title'].' '.$prev_post['name'].' '.$prev_post['b_regdate'].'<br />'?>
		<?php endif; ?>
		<?php if(count($near_posts) > 1) {
			          foreach($near_posts as $post) {
						  if(strlen($post['b_reply']) > 0) {
					          echo '<span style="padding-left:'. strlen($post['b_reply'])*10 .'px">-></span>';
					      }
						  if($board['b_idx'] == $post['b_idx']) {
						      echo $post['b_idx'] .' <strong>'.$post['title'].' '.$post['name'].' '.$post['b_regdate'].'</strong><br />';
						  } else {
                              		echo '<a href="./'.$post['b_idx'].'">'. $post['title'].'</a> '.$post['name'].' '.$post['b_regdate'].'<br />';
						  }
				      }
				  }
        ?>
		<?php if($next_post != '') : ?>
		<a href="./<?=$next_post['b_idx']?>">다음글</a><?=' '.$next_post['title'].' '.$next_post['name'].' '.$next_post['b_regdate'].'<br />'?>
		<?php endif; ?>
		</div>
    </div>
  </div>

  <script>
	var content = "";
	var code = "<?=$board_config['bc_code']?>";

	function formChk(element) {
		if($(element)[0].m_name.value == "") {
			alert("작성자를 입력해주세요.");
			$(element)[0].m_name.focus();
			event.preventDefault();
			return false;
		}

		if($(element)[0].c_content.value == "" || $(element)[0].c_content.value == content) {
			alert("내용을 입력해주세요.");
			$(element).find("textarea[name=c_content]").text("");
			$(element)[0].c_content.focus();
			event.preventDefault();
			return false;
		}

		return true;
	}

	$(document).on("submit", "form[name=commentForm]", function() {
		var chk = formChk(this);

		if(chk) {
			this.submit();
		}
	});

	$(document).on("click", ".btnReply", function() { // 수정요망
		var temp = $(this).parent().parent(); // 변수명 temp 나중에 이름 수정요망.
		var cmt_id = temp.find("input[name=cmt_id]").val();
		var className = temp.attr("class");
		var name = "";
		var form = '<li class="reply">'
						+'<form name="commentForm" method="post" action="<?=MAIN_URL ?>/<?=$board_config['bc_code'] ?>/comment">'
							+'<input type="hidden" name="cmt_id" value="" />'
							+'<input type="hidden" name="bc_code" value="<?=$board_config['bc_code'] ?>" />'
							+'<input type="hidden" name="b_idx" value="<?=$board['b_idx'] ?>" />'
							+'<input type="hidden" name="m_name" id="m_name" value="<?=$this->session->userdata('user_name')?>" />'
							+'<div class="column row comment"><div class="medium-10 columns">'
							+'<textarea name="c_content" id="c_content"></textarea></div>'
							+'<div class="medium-2 columns">'
							+'<input class="cmt_submit" type="submit" value="등록" /></div></div>'
					+'</form></li>';
		var reply = $(".reply").find("form[name=commentForm]").parent();
		var is_reply = reply.length;
		var now_edit = $(".cmt_fix").parent();
		var edit = now_edit.next();
		var is_edit = now_edit.length;
		var this_next_display = temp.next().css("display");
		var reply_button = "";

		if(is_edit) {
			if(this_next_display == "none") {
				temp = edit;
			}
			edit.show();
			now_edit.remove();
		}

		if(is_reply) {
			reply_original = reply.prev().find(".btnReplyCancel");
			reply_original.attr("class", "btnReply");
			reply_original.text("답글");
			reply.remove();
		}

		temp.after(form);
		reply_button = temp.find(".btnReply");
		reply_button.attr("class", "btnReplyCancel").text("답글취소");
		temp.next().find("input[name=cmt_id]").val(cmt_id); //

		if(className == "reply") {
			content_box = temp.next().find("textarea[name=c_content]");
			name = temp.find(".name").text();
			content = name + "님께 답글남기기";

			content_box.text(content);
		}
	});

	$(document).on("click", ".btnReplyCancel", function() {
		$(".reply").find("form").parent().remove();
		$(this)[0].className = "btnReply";
		$(this)[0].innerText = "답글";
	});

	$(document).on("click", "li form[name=commentForm] textarea[name=c_content]", function() {
		$(this).text("");
	});

	$(document).on("blur", "li form[name=commentForm] textarea[name=c_content]", function() {
		var cmt_className = $(this).parent().parent().prev().attr("class");
		if(content != "" && cmt_className == "reply" ) {
			$("li textarea[name=c_content]").text(content);
		}
	});

	$(document).on("click", ".btnEdit", function() {
		var li = $(this).parent().parent();
		var cmt_id = li.find("input[name=cmt_id]").val();
		var className = li.attr("class");
		var name = li.find(".name").text();
		var date = li.find(".date").text();
		var content = li.find(".cmt .content").text();
		var edit_box = $('<li class="' + className + '"/>')
		var elements = $( '<div class="cmt_info">'+
		'<span class="name">'+ name +'</span> <span class="date">'+ date +'</span>'+
		'&nbsp<button class="btnReply">답글</button> <button class="editCancel">수정 취소</button></div>'+
		'<form class="cmt_fix" method="post" action="<?=MAIN_URL?>/'+ code +'/comment/'+ cmt_id +'">'+
		'<input type="hidden" name="cmt_id" value="'+ cmt_id +'" />'+
		'<input type="hidden" name="mode" value="edit" />'+
		'<div class="column row comment"><div class="medium-10 columns">'+
		'<textarea id="c_content" name="c_content">'+ content +'</textarea></div>'+
		'<div class="medium-2 columns">'+
		'<input class="cmt_submit" type="submit" value="수정"></div></div></form>');
		var reply = $(".reply").find("form").parent();
		var is_reply = reply.length;
		var now_edit = $(".cmt_fix").parent();
		var edit = now_edit.next();
		var is_edit = now_edit.length;

		if(is_reply) {
			reply_button = reply.prev().find(".btnReplyCancel");
			reply_button.attr("class", "btnReply");
			reply_button.text("답글");
			reply.remove();
		}

		if(is_edit) {
			edit.show();
			now_edit.remove();
		}

		edit_box.append(elements);
		li.hide();
		li.before(edit_box);
	});

	$(document).on("click", ".editCancel", function() { // 수정요망
		var li = $(this).parent().parent();
		li.next().show();
		li.remove();
	});

	$(document).on("click", ".cmtModify", function() {
		alert("수정");
	});

	$(".btnDelete").click(function() {
		var li = $(this).parent().parent();
		var li_className = li.attr("class")
		var cmt_id = li.find("input[name=cmt_id]")[0].value;
		var is_del = ""
		var msg = "";

		is_del = confirm("댓글을 삭제하시겠습니까?");

		if(is_del) {
			location.href = "<?=MAIN_URL?>/"+code+"/deleteComment/"+cmt_id;
		}
	});
  </script>
