<div class="row section_top">
	<h5 class="float-left">메뉴 관리</h5>
	<div class="float-right">
		<button class="save" type="button">저장</button>
		<button class="cancel" type="button">취소</button>
	</div>
</div>

<div class="row">
	<hr>
</div>

<div class="row section_content">
	<div class="medium-2 columns">
		<div class="add_group callout" style="height: 505px">
			<strong>기본메뉴</strong>
			<ul class="add_list">
				<li class="normal_board">일반게시판</li>
				<li class="image_board">사진게시판</li>
				<li class="group">그룹</li>
			</ul>
		</div>
	</div>
	<div class="medium-1 columns">
		<div class="btn_add">
			<button class="add"></button>
		</div>
	</div>
	<div class="medium-4 columns">
		<div class="menu_list callout" style="padding: 0; width: 250px; height: 505px; overflow-y: scroll;">
			<div class="btn_list">
				<button class="tab button">들여쓰기</button>
				<button class="del button">삭제</button>
			</div>
			<div class="menu">
				<ul class="edit_list" id="sortable">
					<?php $this->load->view('admin/menu_list'); ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="medium-5 columns">
		<div class="menu_description callout" style="height: 505px">
			<h3>메뉴 설정 안내</h3>
		</div>
	</div>
</div>

<div class="text-center">
	<p><strong>메뉴 추가, 메뉴 삭제, 메뉴 정렬(드래그앤드롭) 기능</strong></p>
</div>
<script type="text/template" id="board">
	<div>
		<div class="row">
			<div class="medium-2 columns">
					<p><strong>메뉴명</strong></p>
			</div>
			<div class="medium-6 columns end">
				<input type="text" class="ipt menuname" maxlength="10" value="{menu_name}">
			</div>
		</div>
		<div class="row">
			<div class="medium-2 columns">
					<p><strong>코드명</strong></p>
			</div>
			<div class="medium-6 columns end">
				<input type="text" class="ipt codename" maxlength="10" value="{code_name}">
			</div>
		</div>
		<div class="type_set row">
			<div class="medium-2 columns">
			<p class="title"><strong>형태</strong></p>
			</div>
			<div class="medium-4 columns">
			<label for="normal">일반게시판 <input type="radio"  name="board_type" id="normal" value="normal"></label>
			</div>
			<div class="medium-4 columns end">
			<label for="image">이미지게시판 <input type="radio" name="board_type" id="image" value="image"></label>
			</div>
		</div>
		<div class="row">
			<div class="medium-2 columns">
				<p class="grade" ><strong>권한설정</strong></p>
			</div>
			<div class="medium-10 columns">
				<ul class="set_grade" >
					<div class="row">
						<li>
								<label for="write_level" class="small-3 medium-3 columns">글쓰기</label>
								<select id="write_level" name="write_level" class="write_level small-5 medium-5 columns">
									{option}
								</select>
								<p class="small-3 medium-3 columns end">이상</p>
						</li>
					</div>

					<div class="row">
						<li>
							<label for="comment_level" class="small-3 medium-3 columns">댓글쓰기</label>
							<select id="comment_level" name="comment_level" class="comment_level small-5 medium-5 columns">
								{option}
							</select>
							<p class="small-3 medium-3 columns end">이상</p>
						</li>
					</div>

					<div class="row">
						<li>
							<label for="read_level" class="small-3 medium-3 columns">읽기</label>
							<select id="read_level" name="read_level" class="read_level small-5 medium-5 columns">
								{option}
							</select>
							<p class="small-3 medium-3 columns end">이상</p>
						</li>
					</div>
				</ul>
		</div>
	</div>
	</div>
</script>

<script type="text/template" id="group">
	<div class="row">
		<div class="medium-2 columns">
			<label><strong>그룹명</strong></label>
		</div>
		<div class="medium-6 columns end">
			<input type="text" class="ipt groupname" maxlength="10" value="{group_name}">
		</div>
	</div>
</script>

<script>
var delNumList = [];
var menu_data = {};
var prev_select_id = "";
var o_menu_name = "";
var o_code_name = "";
var o_write_level = "";
var o_comment_level = "";
var o_read_level = "";
var o_radio_val = "";
var sort_prev_index = "";
var changeMenuinfo = false;
var editedMenuInfo = {
	menuid_order: [],
	added_menus: [],
	deleted_menuids: [],
	modified_menus: []
};

// 메뉴추가를 위해 기본 메뉴를 눌렀을 때 옆에 빈 버튼에 +가 추가 되게하고 사용할 수 있게 해줌.
$(document).on("click", ".add_list li", function() {
	$(".add_list").find(".on").removeClass("on");
	$(this).addClass("on");
	$(".add").addClass("on");
	$(".add").html("+");
});

// 메뉴추가 기능
$(document).on("click", ".add.on", function() {
	var id = -1;
	var add_list_on = $(".add_list li.on");
	var name = add_list_on.html();
	var type = " " + add_list_on.attr("class").replace(/ on/gi, "");
	var menu = $("#sortable li");
	var loc = "";
	var tag = "<li id=\"" + id + "\" class=\"selectable ui-sortable-handle on2\"><p class="+ type + ">"+ name +"</p></li>"
	var index = -($("#sortable li#-1").length+1);

	// 메뉴추가하려는 위치를 정해줌.
	if(menu.hasClass("on")) {
		loc = menu.filter(".on");
	} else {
		loc = menu.filter(":last");
	}

	var add_list_len = -($("#sortable li#-1").length);

	if(loc.attr("id") == -1) { // 추가된 메뉴 설정부분 기억하게 하기
		var loc_index = -($("#sortable li#-1").index(loc)+1);
		var temp_data = "";
		var temp_data2 = "";


		if(loc_index == add_list_len) {
			menu_data[loc_index-1] = createMenuInfo(id, name, type);
		} else {
			for(i=loc_index; i>add_list_len; i--) {

				if(i != loc_index) {
					temp_data2 = menu_data[i-2];
					menu_data[i-2] = temp_data;
					temp_data = temp_data2;
				} else {
					temp_data = menu_data[i-2];
					menu_data[i-2] = menu_data[i-1];
				}
			}

			menu_data[loc_index-1] = createMenuInfo(id, name, type);

		}
	} else {
		menu_data[index] = createMenuInfo(id, name, type);
	}

	loc.after(tag);
});

// 들여쓰기 관련 기능 (그룹에는 사용이 되지 않는다.)
$(document).on("click", ".tab.on", function() {
	var tab_li = $("#sortable li.on");
	var id = tab_li.attr("id");
	var minus_index = -($("#sortable li#-1").index(tab_li)+1);
	var index = "";

	if(id == -1) {
		index = minus_index;
	} else {
		index = id;
	}

	if(!($(".selectable.on p").hasClass("group"))) {
		if($(".selectable.on").hasClass("indent") === true) {
			$(".selectable.on").removeClass("indent");
			menu_data[index]["indent"] = 0;
		} else {
			$(".selectable.on").addClass("indent on2");
			menu_data[index]["indent"] = 1;
		}
	}

	changeMenuinfo = true;
});

// 메뉴 삭제 기능
$(document).on("click", ".del", function() {
	var id = $(".selectable.on").attr("id");

	if(id == 1 || id == 2) {
		alert("기본메뉴는 삭제하실 수 없습니다.")
		return false;
	}

	if($(".selectable.on").attr("id") != -1) { // id값이 -1이면 새로 추가한 상태의 메뉴이기 때문에 삭제배열에 추가할 필요가 없다.
		delNumList.push($(".selectable.on").attr("id"));
	}

	$(".selectable.on").remove();
	$("button.tab").removeClass("on");
	$(".menu_description").html("<h3>메뉴 설정 안내</h3>")
});

// 메뉴를 클릭했을 때 메뉴정보를 만들어 주는 기능 (위치변경이나 메뉴를 추가해주었을 때 값 정보를 이동시켜야되기 때문에 필요하다.)
$(document).on("click", "#sortable li", function(e) {
	var id = $(this).attr("id");
	var index = -($("#sortable li#-1").index(this) + 1);
	var pattern = /[a-zA-Z0-9]+/;
	var menuname = $("input.menuname").val();
	var codename = $("input.codename").val();

	// 이전에 선택한 메뉴가 있는데 삭제한 메뉴가 아니면
	if(prev_select_id != "" && delNumList.indexOf(prev_select_id) == -1) {
		checkChangeMenuAttr(prev_select_id);
	}

	if(menuname == "") {
		alert("메뉴명을 입력해주세요.");
		$("input.menuname").focus();
		return false;
	}

	if(codename == "") {
		alert("코드명을 입력해주세요.");
		$("input.codename").focus();
		return false;
	}

	if(pattern.test(codename) == false) {
		alert("코드명은 영어와 숫자만 이용해서 입력하셔야합니다.");
		$("input.codename").focus();
		return false
	}


	if(id == -1 ) {
		createMenu(menu_data[index], index);
	} else {
		if(menu_data[id] == undefined) {
			$.ajax({url: "./menu/get_menu", type : 'POST', dataType: "json", data: {"id" : id}, success: function(result) {
				createMenu(result, id);
			}});
		} else {
			createMenu(menu_data[id], id);
		}
	}

	$("#sortable .on").removeClass("on");
	$(this).addClass("on");
	$(".tab").addClass("on");

	prev_select_id = id > 0 ? id : index;
});

/* 메뉴명이나 코드명을 입력했을 때 메뉴의 설정이 바뀌었다는걸 알려주기 위해 on2클래스를 추가해주면서 색을 바꿔줌.
그리고 메뉴명을 입력했을 때 저장되있는 메뉴의 이름이 바껴서 보이게 해줌. */
$(document).on("keyup", ".ipt", function() {
	var id = $(".selectable.on").attr("id");
	var ipt_name = $(this).val();
	var type = $(this).attr("class").replace(/^(ipt )|(name)$/g,'');
	var bc_type = ""
	var select = $("#sortable li.on");
	var select_id =  id > 0 ? id : -($("#sortable li#-1").index(select) + 1);

	$(".selectable.on").addClass("on2");

	if($("input.menuname").val() == "") {
		select.html("&nbsp");
		return false;
	}

	if(type == "menu" || type == "group") {
		select.html(ipt_name)
		bc_type = "bc_name";
	} else {
		bc_type = "bc_code";
	}

	menu_data[select_id][bc_type] = ipt_name;
});

// 저장 버튼 구현부
$(document).on("click", ".save", function() {
	var menu = $("#sortable li");
	var select_id = $("#sortable li.on").attr("id");
	var menu_type = "";
	var num = -1;
	var menu_len = menu.length;
	var add_menu_index = -1;
	var id = "";
	var pattern = /[a-zA-Z0-9]+/;
	var menuname = $("input.menuname").val();
	var codename = $("input.codename").val();

	if(menuname == "") {
		alert("메뉴명을 입력해주세요.");
		$("input.menuname").focus();
		return false;
	}

	if(codename == "") {
		alert("코드명을 입력해주세요.");
		$("input.codename").focus();
		return false;
	}

	if(pattern.test(codename) == false) {
		alert("코드명은 영어와 숫자만 이용해서 입력하셔야합니다.");
		$("input.codename").focus();
		return false
	}

	if(select_id > 0) {
		checkChangeMenuAttr(select_id);
	}

	// 메뉴 저장을 위한 객체속성 세팅
	editedMenuInfo.deleted_menuids = delNumList;
	console.log(menu_data);

	for(i=0; i<menu_len; i++) {
		id = $("#sortable li:eq("+ i +")").attr("id");

		if(id == -1) {
			if(menu_data[num]['is_group'] != 1 && pattern.test(menu_data[num]['bc_code']) == false) {
				alert('추가한 메뉴 중 코드명이 영문과 숫자로 이루어지지 않은 메뉴가 있습니다.\n추가한 메뉴 중 코드명을 영문과 숫자를 이용하여 작성해주시기 바랍니다.');
				editedMenuInfo.menuid_order = [];
				editedMenuInfo.added_menus = [];
				return false;
			}
			menu_type = menu_data[num]["type"];
			editedMenuInfo.menuid_order.push(add_menu_index);
			editedMenuInfo.added_menus.push(menu_data[num]);
			num--;
		} else {
			editedMenuInfo.menuid_order.push(id);
		}
	}


	$.ajax({
		type: "POST",
		url: "./menu/save",
		data: {"menu" : JSON.stringify(editedMenuInfo)},
		dataType: "text",
		//contentType : "application/json",
		success: function(res) {
			if(res == 'success') {
				alert("저장되었습니다.");
				refresh();
			} else {
				alert("중복된 코드 값을 포함한 메뉴가 있습니다.\n수정해주시기 바랍니다.");
			}
		}
	});

	delNumList = [];
	editedMenuInfo = {
		menuid_order: [],
		added_menus: [],
		deleted_menuids: [],
		modified_menus: []
	};

});

// 취소버튼 구현부
$(document).on('click', '.cancel', function(){
		$(".normal_board.on, .image_board.on, .group.on, .add.on, .selectable.on").removeClass("on");
		$(".add").html("");

		refresh();
});

var refresh = function() {
	$.ajax({
		url:"./menu/refresh",
		dataType:"html",
		success:function(data){
			$("#sortable").html(data);
			$(".menu_description").html("<h3>메뉴 설정 안내</h3>");

			prev_select_id = "";
			menu_data = [];
			res = [];
		}
	});
}

// 메뉴를 드래그를 해서 이동했을 때 저장되있던 메뉴데이터의 값을 변경해주는 기능
$( "#sortable" ).on( "sortupdate", function( event, ui ) {
	var add_list = $("#sortable li#-1");
	var index = -(add_list.index(ui.item) + 1);

	var temp_data = "";
	var temp_data2 = "";

	ui.item.addClass("on2");

	if(sort_prev_index > index) {
		for(i=index; i<sort_prev_index; i++) {
			temp_data = i == index ? menu_data[i] : temp_data2;

			if(i==index) {
				menu_data[i] = menu_data[sort_prev_index];
			}

			temp_data2 = menu_data[i+1];
			menu_data[i+1] = temp_data;
		}
	} else {
		for(i=index-1; i>=sort_prev_index; i--) {
			temp_data = i == index-1 ? menu_data[index] : temp_data2;

			if(i==index-1) {
				menu_data[index] = menu_data[sort_prev_index];
			}

			temp_data2 = menu_data[i];
			menu_data[i] = temp_data;
		}
	}

} );

// 메뉴를 누르고 있을 때 인덱스 값을 저장해뒀다가 나중에 sortupdate 이벤트가 발생하면 이전 인덱스 값으로 사용해준다.
$( "#sortable" ).on( "sortactivate", function( event, ui ) {
	sort_prev_index = -($("#sortable li#-1").index(ui.item) + 1);
});

// 만들어 놓은 메뉴 데이터의 특정값이 변경되면 갱신해주는 기능
$(document).on("change", "input[name=board_type], .write_level, .read_level, .comment_level", function() {

	var tag_name = $(this).prop("tagName");
	var select_menu = $("#sortable li.on");
	var id = select_menu.attr("id");
	var minus_index = -($("#sortable li#-1").index(select_menu)+1);
	var attribute = tag_name == "SELECT" ? "bc_" + $(this).attr("class") : "type";
	var select_value = tag_name == "SELECT" ? $(this).find("option:selected").val() : $(this).val();

	if(id == -1) {
		menu_data[minus_index][attribute] = select_value;
	} else {
		menu_data[id][attribute] = select_value;
	}
});

// 메뉴 생성 기능
var createMenu = function(res, id) {
	var tag = "";
	var opt_len = 9;
	var write_opt = "";
	var comment_opt = "";
	var read_opt = "";
	var data = {};

	if(menu_data[id] == undefined) {
		menu_data[id] = res;
	}

	data = menu_data[id];

	if(res["is_group"] == 0 || (menu_data[id] != undefined && menu_data[id]["is_group"] == 0)) {
		tag = $($("#board").html());

		for(i=0 ; i<opt_len ; i++) {
			if(i != 0 ) {
				write_opt += "<option value=\""+ i +"\">"+ i + "</option>";
				comment_opt += "<option value=\""+ i +"\">"+ i + "</option>";
			}
			read_opt += "<option value=\""+ i +"\">"+ i + "</option>";
		}

		tag.find(".menuname").val(data["bc_name"]);
		tag.find(".codename").val(data["bc_code"]);
		/*
		tag.find(".write_level").html(write_opt).find("option[value='"+ data["bc_write_level"] +"']").attr("selected", "selected");
		tag.find(".comment_level").html(comment_opt).find("option[value='"+ data["bc_comment_level"] +"']").attr("selected", "selected");
		tag.find(".read_level").html(read_opt).find("option[value='"+ data["bc_read_level"] +"']").attr("selected", "selected");
		*/
		tag.find(".write_level").html(write_opt).val(data["bc_write_level"])
		tag.find(".comment_level").html(comment_opt).val(data["bc_comment_level"]);
		tag.find(".read_level").html(read_opt).val(data["bc_read_level"]);

		if(data["type"] == "normal")
			tag.find("#normal").attr("checked", "checked");
		else
			tag.find("#image").attr("checked", "checked");

	} else {
		tag = $($("#group").html());
		tag.find(".groupname").val(data["bc_name"]);
	}

	$(".menu_description").html(tag);

	o_menu_name = $(".menuname").val();
	o_code_name = $(".codename").val();
	o_write_level = $(".write_level").val();
	o_comment_level = $(".comment_level").val();
	o_read_level = $(".read_level").val();
	o_radio_val = $(":radio[name='board_type']:checked").val();
	o_group_name = $(".groupname").val();
};

// 메뉴를 추가했을 때 메뉴 정보를 만들어서 반환해주는 메소드
var createMenuInfo = function(id, board_name, board_type) {
	var data = {};
	var board_type = board_type.trim();

	data["bc_idx"] = id;
	data["bc_name"] = board_name;
	data["bc_code"] = "코드명";
	data["indent"] = 0;
	data["bc_write_level"] = 1;
	data["bc_comment_level"] = 1;
	data["bc_read_level"] = 0;

	if(board_type == "group") {
		data["is_group"] = 1;
		data["bc_code"] = "";
		data["type"] = "group";
	} else {
		data["is_group"] = 0;
		data["type"] = board_type == "normal_board" ? "normal" : "image";
	}

	return data;
}

// 메뉴정보가 바뀌었는지 체크하고 바뀌었으면 메뉴데이터의 값을 갱신해주는 기능
var checkChangeMenuAttr = function(id) {

	var changeMenudata = false;
	var readLevel = $(".read_level").val();
	var writeLevel = $(".write_level").val();
	var commentLevel = $(".comment_level").val();
	var radio = $(":radio[name=\"board_type\"]:checked").val();
	var menu = $(".menuname").val();
	var group = $(".groupname").val();
	var code = $(".codename").val();
	var data = menu_data[id];
	var menudata = [writeLevel, readLevel, commentLevel, radio, menu, group, code];
	var pastMenudata = [o_write_level, o_read_level, o_comment_level, o_radio_val, o_menu_name, o_group_name, o_code_name];
	var index = ["bc_write_level", "bc_read_level", "bc_comment_level", "type", "bc_name", "bc_name", "bc_code"];
	var count = index.length;

	for(var i=0; i<count; i++) {
		if(id > 0 && menudata[i] != pastMenudata[i]) {
			data[index[i]] = menudata[i];
			changeMenuinfo = true;

			if(menudata[i] != "bc_name" && menudata[i] != "bc_code") {
				$("li#" + id).addClass("on2");
			}
		}
	}

	/*
	if(writeLevel != o_write_level) {
		menu_data[id]["bc_write_level"] = writeLevel;
		chkChange = true;
		$("li#" + id).addClass("on2");

	}

	if(readLevel != o_read_level) {
		menu_data[id]["bc_read_level"] = readLevel;
		$("li#" + id).addClass("on2");
		chkChange = true;
	}

	if(commentLevel != o_comment_level) {
		menu_data[id]["bc_comment_level"] = commentLevel;
		$("li#" + id).addClass("on2");
		chkChange = true;
	}

	if(radio != o_radio_val) {
		menu_data[id]["type"] = radio;
		$("li#" + id).addClass("on2");
		chkChange = true;
	}

	if(menu != o_menu_name) {
		menu_data[id]["bc_name"] = menu;
		chkChange = true;
	}

	if(group != o_group_name) {
		menu_data[id]["bc_name"] = group;
		chkChange = true;
	}

	if(code != o_code_name) {
		menu_data[id]["bc_code"] = code;
		chkChange = true;
	}*/

	if(changeMenuinfo == true) {
		$.each(editedMenuInfo.modified_menus, function(index, savedData) {
				if(savedData["bc_idx"] == data["bc_idx"]) {
					editedMenuInfo.modified_menus[index] = data;
					changeMenudata = true;
				}
		});

		if(changeMenudata == false) {
			editedMenuInfo.modified_menus.push(data);
		}
	}
}
</script>
