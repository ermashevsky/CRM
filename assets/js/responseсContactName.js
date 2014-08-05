                function getContactDetail(phone_number) {
                  $.post('<?php echo site_url('/core/getContactDetail'); ?>', {'phone_number': phone_number},
                    function(data) {
                        if(data !== ""){
                        $("div#ui_notifIt").append("Звонит " + data);
                        $('div#ui_notifIt').css('text-align','center');
                    }
                  });
                }