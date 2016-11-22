<div class="search">
			<form method="get" action="<?=MAIN_URL.'/'.$bc_code.'/1' ?>">
        <div class="list-search">
  				<span class="select"><?=searchDate_to_str($searchDate)?></span>
          <select name="date" class="date" style="display:none">
            <option value="all" <?=$searchDate == 'all' ? 'selected' : '' ?>>전체기간</option>
  					<option value="1d" <?=$searchDate == '1d' ? 'selected' : '' ?>>1일</option>
  					<option value="1w" <?=$searchDate == '1w' ? 'selected' : '' ?>>1주</option>
  					<option value="1m" <?=$searchDate == '1m' ? 'selected' : '' ?>>1개월</option>
  					<option value="6m" <?=$searchDate == '6m' ? 'selected' : '' ?>>6개월</option>
  					<option value="1y" <?=$searchDate == '1y' ? 'selected' : '' ?>>1년</option>
  					<option <?=strlen($searchDate) == 20 ? 'value='.$searchDate.' selected' : '' ?>>기간입력</option>
  				</select>
          <span class="select-type"><?=searchTypeToStr($search)?></span>
  				<select name="search" class="type" style="display:none">
            <option value="b_title||b_content" <?=$search == 'b_title||b_content' ? 'selected' : '' ?>>제목+내용</option>
  					<option value="b_title" <?=$search == 'b_title' ? 'selected' : '' ?>>제목만</option>
  					<option value="b_name" <?=$search == 'b_name' ? 'selected' : '' ?>>글작성자</option>
  					<option value="c_content" <?=$search == 'c_content' ? 'selected' : '' ?>>댓글내용</option>
  					<option value="c_name" <?=$search == 'c_name' ? 'selected' : '' ?>>댓글작성자</option>
  				</select>
  				<input type="text" class="word" name="word" value="<?=$word?>"/>
  				<input class="btn-search" type="submit" value="전송" />
        </div>
			</form>
      <ul class="mock_date" style="display:none">
        <li <?=$searchDate == 'all' ? 'class="on"' : '' ?>>전체기간</li>
				<li <?=$searchDate == '1d' ? 'class="on"' : '' ?>>1일</li>
				<li <?=$searchDate == '1w' ? 'class="on"' : '' ?>>1주</li>
				<li <?=$searchDate == '1m' ? 'class="on"' : '' ?>>1개월</li>
				<li <?=$searchDate == '6m' ? 'class="on"' : '' ?>>6개월</li>
				<li <?=$searchDate == '1y' ? 'class="on"' : '' ?>>1년</li>
				<li class="seljs_mover <?=strlen($searchDate) == 20 ? "on" : '' ?>">
					<fieldset >
						<label>기간입력</label>
						<input type="text" class="seljs_text"  maxlength="10" value="<?=strlen($searchDate) == 20 ? substr($searchDate, 0, 10) : '' ?>" placeholder="2016-11-10"/>
						~
						<input type="text" class="seljs_text" maxlength="10" value="<?=strlen($searchDate) == 20 ? substr($searchDate, 10, 10) : '' ?>" placeholder="2016-11-17"/>
						<input type="image" class="set_btn" value="설정"/>
					</fieldset>
				</li>
      </ul>

      <ul class="mock_type" style="display:none;">
        <li <?=$search == 'b_title||b_content' ? 'class="on"' : '' ?>>제목+내용</li>
        <li <?=$search == 'b_title' ? 'class="on"' : '' ?>>제목만</li>
        <li <?=$search == 'b_name' ? 'class="on"' : '' ?>>글작성자</li>
        <li <?=$search == 'c_content' ? 'class="on"' : '' ?>>댓글내용</li>
        <li <?=$search == 'c_name' ? 'class="on"' : '' ?>>댓글작성자</li>
      </ul>
</div>
