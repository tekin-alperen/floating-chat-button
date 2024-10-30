(function($) {
    'use strict';

    $(document).ready(function() {
        // WhatsApp default rengi
        const DEFAULT_COLOR = '#25D366';

        // Color picker başlatma
        $('.color-picker').wpColorPicker();

        // Reset butonu için event listener
        $('.reset-color').on('click', function(e) {
            e.preventDefault();
            // Color picker'ı varsayılan renge set et
            $('.color-picker').wpColorPicker('color', DEFAULT_COLOR);
        });
    });

})(jQuery);