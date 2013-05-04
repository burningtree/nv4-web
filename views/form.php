<? if($this->form_send): ?>

  <div class="fonted big2"><?= $this->congrats ?></div>
  <div class="fonted"><a href="?<?=time()?>#page-1"><?= $this->register_another ?></a></div>

<? else: ?>

  <div class="form">
    <form id="reg-form" action="#page-1" method="POST">
    <div class="reg">
      <table class="table">
        <tr class="reg-row">
          <td class="reg-col title"><?= $this->name ?></td>
          <td class="reg-col long" colspan="6"><input type"text" name="name" /></td>
        </tr>
        <tr class="reg-row">
          <td class="reg-col title"><?= $this->email ?></td>
          <td class="reg-col long" colspan="6"><input type"text" name="email" /></td>
        </tr>
        <tr class="reg-row">
          <td class="reg-col title"><?= $this->tickets ?><input type="hidden" name="tickets" value=""></td>
            <? for($i=1;$i<=5; $i++): ?>
            <td class="reg-col tcol"><?=$i?></td>
            </td>
            <? endfor ?>
          <td class="reg-col"><?= $this->number_tickets ?></td>
        </td>
      </table>
    </div>
    <button type="submit" id="send_form" class="fonted big2"><?= $this->send_now ?></button>
    </form>
  </div>

  <script>
  function validateEmail(email) 
  {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }


  $(document).ready(function(){

    $('.reg-col.tcol').click(function(){
      var txt = $(this).text();
      $('#reg-form input[name="tickets"]').val(txt);
      $('.reg-col.tcol').removeClass('active');
      $(this).addClass('active');
      
    });
    $('input[name="name"], input[name="email"]').bind("focusin", function(){
      $(this).parent().parent().addClass('active');
    }).bind("focusout", function(){
      if($(this).attr('name') == 'email'){
        if(validateEmail($(this).val())) return;
      } else if($(this).val() != "") return

      $(this).parent().parent().removeClass('active');
    });
    $('#send_form').click(function(){
      var ok = true;
      $('#reg-form input').each(function(el){
        var val = $(this).val();
        var col = $(this).attr('name');
        var tr = $(this).parent().parent();
        if(val == ''){
          tr.addClass('red');
          ok = false;
        } else {
          if(col == 'email' && !validateEmail(val)){
            tr.addClass('red');
            ok = false;
          } else if(col == 'tickets' && isNaN(val)) {
            tr.addClass('red');
            ok = false;
          } else {
            tr.removeClass('red');
          }
        }
      });
      if(!ok){ return false; }
        
      $.ajax({
        type: 'POST',
        url: '?#page-1',
        data: $('#reg-form').serialize(), 
        success: function(html){
          console.log('got data!');
          console.log(html);
          $('#page-1 .inner').html($('.inner:first', html).html());
        },
        dataType: 'html'
      });
      return false;
    });
  });
  </script>

<? endif; ?>

