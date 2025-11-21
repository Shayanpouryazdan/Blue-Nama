jQuery(document).ready(function($) {
    // انتخاب همه
    $('#select-all').on('click', function() {
        $('input[name="selected_pages[]"]').prop('checked', this.checked);
    });

    // کپی شورت‌کد
    $(document).on('click', '.copy-btn', function() {
        var $btn = $(this);
        var text = $btn.attr('data-clipboard-text');

        navigator.clipboard.writeText(text).then(function() {
            $btn.text('کپی شد!').addClass('copied');
            setTimeout(function() {
                $btn.text('کپی').removeClass('copied');
            }, 2000);
        });
    });
});