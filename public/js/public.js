(function($) {
    'use strict';

    $(document).ready(function() {
        // Buton animasyonu için sınıf ekleme
        $('.floating-chat-button').addClass('is-visible');
        
        // Tıklama analitikleri eklenebilir
        $('.chat-button').on('click', function() {
            // Örnek: Google Analytics event
            if (typeof ga !== 'undefined') {
                ga('send', 'event', 'Chat Button', 'click', 'WhatsApp Chat');
            }
        });
    });

})(jQuery);