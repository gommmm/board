$(document).on("click", ".select", function() {
  var ul = $(".mock_date");
  var display = ul.css("display");

  if(display == "block")
    ul.hide();
  else
    ul.show();
});

$(document).on("click", ".select-type", function() {
  var ul = $(".mock_type");
  var display = ul.css("display");

  if(display == "block")
    ul.hide();
  else
    ul.show();
});

$(document).on("mouseover", ".mock_date li, .mock_type li", function() {
  var className = $(this).parent().attr("class");
  var li_list = $("." + className + " li");
  var li_list_length = li_list.length;
  var li = "";

  for(i=0; i<li_list_length; i++) {
    li = $(li_list[i]);
    li_className = $(li_list[i]).attr("class");

    if(li_className == "on") {
      li.removeClass("on");
    }
  }

  $(this).addClass("on");
});

$(document).on("mouseout", ".mock_date li, .mock_type li", function() {
  var ul = $(this).parent();
  var ul_display = ul.css("display");

  if(ul_display != "none") {
    $(this).removeClass("on");
  }
});

$(document).on("click", ".mock_date li", function() {
  var t = $(this);
  var value = $(this).text();
  var select = $(".select");
  var ul = $(".mock_date");
  var searchDate = "searchDate=" + $("li.on").text();
  var date_list = $(".date option");
  var date_len = date_list.length;
  var date = "";

  if(t.find("fieldset").length == 1) {
    value = t.find("fieldset label").text();
  }

  if(t.find("fieldset").length != 1) {
    select.text(value);
    ul.hide();

    for(var i=0; i < date_len; i++) {
      date = $(date_list[i]);

      if(t.text() == date.text()) {
        date.attr("selected", "selected");
      } else {
        date.removeAttr("selected");
      }
    }
  }

  t.addClass("on");

});

$(document).on("click", ".mock_type li", function() {
  var t = $(this);
  var value = $(this).text();
  var select = $(".select-type");
  var ul = $(".mock_type");
  var type_list = $(".type option");

  console.log(value);
  $.each(type_list, function(i, type) {
    var type_option = $(type);
    if(type_option.text() == value)
      type_option.attr("selected", "selected");
    else
      type_option.removeAttr("selected");
  });

  select.text(value);
  ul.hide();
});

$(document).on("click", ".set_btn", function() {
  var ul = $("ul.mock_date");
  var fieldset = $(this).parent();
  var label = fieldset.find("label");
  var label_val = label.text();
  var select = $(".select");
  var date_input_option = "";
  var date_input = $(".seljs_text");
  var date_input_to = $(date_input[0]);
  var date_input_from = $(date_input[1]);
  var matchi_to = "";
  var match_from = "";

  match_to = date_validation(date_input_to.val());
  match_from = date_validation(date_input_from.val());

  if((match_to == false || match_from == false) || match_to > match_from) {
    alert("검색기간을 잘못 입력하셨습니다.\n확인 후 검색기간을 다시 입력해주세요.\n(예) 2010-12-24");

    if(match_to == null && match_from == null) {
      date_input_to.focus();
    } else if(match_to == null) {
      date_input_to.focus();
    } else {
      date_input_from.focus();
    }
  } else {
    date_input_option = $(".date option:eq(6)");
    date_input_option.val(date_input_to.val()+date_input_from.val()).attr("selected", true);
    ul.hide();
    select.text(label_val);

  }
});

$(document).on("click", "div.content, div.search, .prev-next, .list-btn", function(e) {
  var mock_date = $(".mock_date");
  var mock_type = $(".mock_type");
  var tar = "";

  if(mock_date.css("display") == "block")
    tar = mock_date;
  else if(mock_type.css("display") == "block")
    tar = mock_type;

  if(tar !== "" && tar.css("display") == "block") {
    if(!($(e.target).is(".select, .select-type, .mock_date li fieldset, li fieldset label, .seljs_text, .set_btn"))) {
      tar.hide();
    }
  }

});

$(document).on("click", ".select-type", function() {
  var ul = $(".mock_date");
  ul.hide();
});

$(document).on("click", ".select", function() {
  var ul = $(".mock_type");
  ul.hide();
});



$(document).on("keydown", ".seljs_text", function(e) {
  var code = e.keyCode ? e.keyCode : e.which;
  if(code > 47 && code < 58 || code == 8 || code == 37 || code == 39 || code == 46 || code == 9) {

  } else {
    e.preventDefault ? e.preventDefault() : e.returnValue = false;
  }
});

$(document).on("drag select keydown keyup", ".seljs_text", function(e) {
  var t = $(this);
  var t_val = t.val();
  var val_length = t_val.length;
  var year = "";
  var month = "";
  var day = "";
  var date = "";
  var r_val = ""
  var r_val_length = "";

  r_val= t_val.replace(/-/gi, "");
  r_val_length = r_val.length;

  if(r_val_length == 8) {
    year = r_val.substring(0,4);
    month = r_val.substring(4,6);
    day = r_val.substring(6,8);

    date = year + "-" + month + "-" + day;
    if(e.keyCode != 8) {
      t.val(date);
    }
  }
});

function date_validation(date) {
  var pattern = "([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1-2][0-9]|[3][0-1])";
  var result = date.match(pattern);
  var year = "";
  var month = "";
  var day = "";
  var date = "";
  var unixtime = "";

  if(result != null) {
    year = result[1];
    month =  result[2];
    day = result[3];
    date = new Date(year, month, day);
    unixtime = date.getTime();
    return unixtime;
  }
  return false;
}
