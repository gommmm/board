    <div class="content">
      <div class="row">
        <div id="chatLog" class="chat_log" style="height: 450px; overflow: auto;">
          <?php foreach($message as $row) : ?>
            <div class="<?=$row['senderId'] == $id ? 'mymsg' : 'msg'?>">
              <p class="name"><?=$row['senderId'] != $id ? $row['senderId'] : ''?></p>
              <span class="message"><?=$row['message']?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="row">
        <form id="chat">
          <div class="small-10 columns">
            <input id="message" class="message" type="text">
          </div>
          <div class="small-2 columns">
            <input type="submit" class="chat button" value="전송" />
          </div>
        </form>
      </div>
    </div>

    <script src="<?=NODE?>/socket.io/socket.io.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
    <script id="chat_template" type="text/template">
      <div>
        <p class="name">{name}</p>
        <span class="message">{message}</span>
      </div>
    </script>
    <script>
      var socket = io('<?=NODE?>');
      var id = '<?=$id ?>';
      var receiverId = '<?=$receiverId ?>';

      $( document ).ready(function() {
          var scroll_height = $("#chatLog").prop("scrollHeight");
          var chat_log = $("#chatLog");

          chat_log.scrollTop(scroll_height);

          socket.emit('join', id, receiverId);

          $('#chat').on('submit', function(e) {
            var message = $('#message').val();

            if(message === "")
              return false;

            var data = {
            	senderId: id,
            	receiverId: receiverId,
            	message: message
            };

            $.ajax({
          		type: "POST",
          		url: "<?=MAIN_URL?>/chat/save",
          		data: JSON.stringify(data),
          		contentType : "application/json",
          		success: function(res) {
          			console.log(res);
          		}
          	});

          socket.emit('send message', id, receiverId, $('#message').val());
            $('#message').val("");
            e.preventDefault();
          });

          socket.on('message', function(msg, senderId) {
            var template = $($("#chat_template").html());
            var name = "";
            var className = "mymsg";

            if(id !== senderId) {
              className = "msg";
              name = senderId;
            }

            template.addClass(className);
            template.find(".name").text(name);
            template.find(".message").text(msg);

            chat_log.append(template);
            chat_log.scrollTop(scroll_height);
          });
      });

    </script>
