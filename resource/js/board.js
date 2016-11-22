$(document).on("click", ".article .nickname>a, dd.nickname>a, div.nickname>a", function(e) {
    var offset = $(this).offset();
    var left = offset.left;
    var top = offset.top;
    var name = $(this).text();
    var id = $(this).parent().attr("id").replace(/article_/g, "");
    var href =  location.pathname;
    var href_len = href.length;
    var last_href_char = href.charAt(href_len-1);

    /* 현재는 주소값의 마지막 값을 이용해서 게시판코드가 있는지 없는지 체크하고 있다.
       추후에 괜찮은 방법이 있으면 고쳐주면 좋을 것 같다.
    */
    if(last_href_char == "/") {
      var link = $(this).parent().prev().find("a").attr("href");
      var url_len = url.length;
      var link_len = $(this).parent().prev().find("a").attr("href").length;
      var str = link.substr(url_len, link_len);
      var pattern = /[a-zA-Z0-9]+/;

      code = pattern.exec(str)[0];
    }

    var tag = "<div class=\"menu_box\"><ul style=\"position:absolute;z-index:5000;background-color:#FFFFFF;\"><li><a href=\"" + url + "/" + code + "/1?date=all&search=b_name&word=" + name + "\">게시물보기</a></li>";

    if (user_id !== "" && id != user_id) { // 메뉴 생성용
        tag += "<li><a href=\"" + url + "/message/send?receiverId=" + id + "\">쪽지보내기</a></li>" +
            "<li class=\"chat\"><a href=\"" + url + "/chat/"+ user_id +"/"+ id +"\">1:1 대화</a></li>";
        $("#nickSaveFrm input[type=hidden]").val(name);

        if(user_level == 9) {
          tag += "<li class=\"change_level\"><a href=\"#\">등급변경</a></li>" +
                "<li class=\"banish\"><a href=\"#\">강퇴시키기</a></li>";
        }
    }

    tag += "</ul></div>";

    $(".menu_box").remove();
    $(this).after(tag);
    $(".menu_box").css("top", top + 37).css("left", left);

    /* code는 전역변수라서 값이 남기때문에 초기화 해줘야
    전체게시물에서 매번 code값을 바꿔줄 수 있다. (페이지가 전환이 되면 문제가 없지만
    메뉴를 활성화했다가 다른 메뉴를 활성화 했을 때 문제가 될 수 있기 때문에 초기화를 했다.)*/
    e.preventDefault();
});

$(document).on("click", ".menu_box ul li:eq(1) a", function(e) {
    var x = (screen.width - 620) / 2;
    var y = (screen.height - 500) / 2;
    var this_href = $(this).attr('href');
    var index = this_href.indexOf("=") + 1;
    var receiver_id = this_href.substr(index);
    var nickNameSaveFrm = $("#nickSaveFrm")[0];

    $(".menu_box").hide();

    window.open('', 'message', 'left=' + x + ',top=' + y + ',width=620,height=500,scrollbars=1');
    nickNameSaveFrm.target = "message";
    nickNameSaveFrm.action = url + "/message/send?receiverId=" + receiver_id;
    nickNameSaveFrm.method = "post";
    nickNameSaveFrm.submit();

    e.preventDefault();
});

$(document).on("click", ".chat a", function(e) {
  var x = (screen.width - 480) / 2;
  var y = (screen.height - 520) / 2;
  var this_href = $(this).attr('href');

  $(".menu_box").hide();

  window.open(this_href, 'chat', 'left=' + x + ',top=' + y + ',width=480,height=520,scrollbars=1');

  e.preventDefault();

});

$(document).on("click", "div.content, div.search", function(e) {
    if ($(".menu_box").css("display") == "block") {
        if (!$("td.nickname, dd.nickname, div.nickname").has(e.target).length) {
            $(".menu_box").hide();
        }
    }
});

// 관리자가 사용할 수 있는 강퇴 및 사용자 레벨 변경 기능
$(document).on("click", ".banish, .change_level", function(e) {
  e.preventDefault();

  var class_name = $(this).attr("class");
  var multiForm = $("#multiForm");
  var id_list = multiForm.find("#id_list");
  var id = $(this).parent().parent().parent().attr("id");
  var index = id.indexOf("_") + 1;

  id = id.substring(index);
  id_list.val(id);

  if(class_name === "banish" && confirm("이 사용자를 강퇴하시겠습니까?") === true) {
    multiForm.attr("target", "");
    multiForm.attr("action", url+"/admin/member/banish");
    multiForm.submit();

    /* 새창을 사용하지 않는데 타겟값을 설정한 이유는 등급변경 새로운 창을 띄우고 나서
    값을 보내지 않고 강제종료하면 타겟값이 남아 있기 때문에 새창을 띄우면서 게시글을 삭제하게된다.
    그래서 따로 설정하게 되었다. */

    return true;
  } else if(class_name === "change_level"){
    var x = (screen.width - 300) / 2;
    var y = (screen.height - 400) / 2;

    window.name = "parent";
    window.open('', 'changeLevelForm', 'left=' + x + ',top=' + y + ',width=300,height=400');

    multiForm.attr("target", "changeLevelForm").attr("action", url+"/admin/member/changeLevelForm").submit();

    return true;
  }
});

// 관리자가 사용할 수 있는 글이동 및 글삭제 기능
$(document).on("click", "a.remove, a.move", function(e) {
  var list = [];
  var multiForm = $("#multiForm");
  var id_list = multiForm.find("#id_list");
  var class_name = $(this).attr("class");
  var checked = $("input[name=chk]:checked");

  e.preventDefault();
  window.name = "";

  if(checked.length === 0) {
    var msg = class_name === "remove" ? "삭제" : "이동";
    alert(msg + "할 게시물을 선택해주세요.");

    return false;
  }

  checked.each(function() {
    if(class_name === "remove" || $(this).parent().next().text() !== "")
      list.push($(this).val());
  });

  if(list.length === 0) {
    alert('본글없이 답글만 이동할 수 없습니다.');

    return false;
  }

  if(class_name === "remove" && confirm("게시글을 삭제하시겠습니까?") === true) {
    id_list.val(list);
    multiForm.attr("target", "");
    // 위에 주석에서 설명한 것과 이유가 같다.
    multiForm.attr("action", url +"/"+ code +"/delete" ).submit();

    return true;
  } else if(class_name === 'move') {
    var x = (screen.width - 300) / 2;
    var y = (screen.height - 240) / 2;

    window.name = "parent";
    window.open('', 'movePostForm', 'left=' + x + ',top=' + y + ',width=300,height=240');
    id_list.val(list);
    multiForm.attr("target", "movePostForm").attr("action", url+"/admin/menu/movePostForm").submit();

    return true;
  }
});

$(document).on("click", ".removeNotice", function(e) {
  console.log("작동");
  var id = $(this).attr("id");
  var multiForm = $("#multiForm");
  var id_list = $("#id_list");

  e.preventDefault();

  multiForm.attr("action", url + "/admin/menu/removeNotice");
  id_list.val(id);
  multiForm.submit();

});

// 멀티 파일 업로드를 위해 파일을 추가해주고 삭제해주는 이벤트 처리
var file_count = 1;
var file_id = -1;
var remove_id_list = {};

$(document).ready(function() {
  //var file = $(".file");
  //var file_exists = file.length;

  /*if(file_exists !== 0) {
    var file_last_id = file.last().attr("id");
    file_last_id *= 1;
    file_count = file_last_id + 1;
  }*/

  var file_tag = "<input type=\"file\" class=\"b_file\" id=\"b_file"+ file_count +"\" name=\"b_file[]\" style=\"display:none\">";

  if($("#b_file"+ file_count).length === 0) // 수정을 위해 추가함.
    $(".file_select").append(file_tag);
});

$(document).on("click", "#b_file_button", function(e) {
  $( "#b_file" + file_count ).trigger( "click" );
});

$(document).on("change", ".b_file", function(e) {
  var file = $(this)[0].files[0];
  var label = "<label class=\"item\">첨부파일</label>";
  var file_list = $(".file_list");

  var tag = "<div id=\""+ file_id +"\" class=\"file\">"+file.name+"<a class=\"file_remove\" href=\"#\">x</a></div>";
  file_id -= 1;
  file_count += 1;
  var file_tag = "<input type=\"file\" class=\"b_file\" id=\"b_file"+ file_count +"\" name=\"b_file[]\" style=\"display:none\">";

  e.preventDefault();

  if($(".item").length === 0)
    file_list.append(label);
  file_list.append(tag);

  $(".file_select").append(file_tag);
});

$(document).on("click", ".file_remove", function(e) {
  var parent = $(this).parent();
  var filename = parent.find(".filename").text();
  var id = parent.attr("id"); // 이 상태로는 새로운 값이면 음수의 값, db에 저장된 값이면 양수의 값

  e.preventDefault();

  if(id > 0) { // 삭제한 id값이 양수이면 배열에 id값 추가
    remove_id_list[id] = filename;
  }

  /* id값이 음수이면 새로 파일을 추가한 값이기 때문에 이것을 삭제하면
     input file태그도 같이 삭제해줘야 되는데 id값은 음수이고 input태그는 양수이기 때문에
     id값을 양수로 바꿔준 후 삭제한다.
  */

  if(id < 0) {
     id = -id;
     $("#b_file"+id).remove();

  }

  parent.remove();

  if($(".file").length === 0) {
    $(".item").remove();
  }
});

// 전송버튼을 클릭했을 때 삭제 id값 전달
$(document).on("click", "input[type='submit']", function(e) {
  var hidden_tag = '<input type="hidden" id="remove_id_list" name="remove_id_list" value="">';

  $(".file_list").append(hidden_tag);
  $("#remove_id_list").val(JSON.stringify(remove_id_list));
});

// 여기까지

$(document).on("click", ".checkAll", function() {
  var checked = $(this).prop("checked");
  var chk = $(".chk");
  if(checked === true)
    chk.prop("checked", true);
  else
    chk.prop("checked", false);
});

/*var uri =
{
	segment_array : function  ()
	{
		var path = location.pathname;

        	//-- 앞 / 제거
        	path = path.substr(1);

		//-- 끝 / 제거
		if (path.slice(-1) == '/')
		{
			path = path.substr(0 , path.length - 1);
		}

		var seg_arr = path.split('/');

		//-- index.php 제거
		if (seg_arr[0] == 'index.php')
		{
			seg_arr.shift();
		}

		return seg_arr;
	},

	segment : function (n , v)
	{
		var seg_array = this.segment_array();
		var seg_n = seg_array[n-1];

		if (typeof seg_n == 'undefined')
		{
			if (typeof v != 'undefined')
			{
				return v;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return seg_n;
		}
	}
};*/
