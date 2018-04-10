$(function() {
    $(document).on('click', '.js-modal-toggler', function() {
        var _this = $(this);
        $('.js-modal-id').text(_this.data('id'));
        $('.js-modal-content').html(_this.next().html());
        $('#orderModal').toggle();
        $('body').toggleClass('modaled');
    });
    $(document).on('click', '.js-modal-close', function() {
        $($(this).data('target')).hide();
        $('body').removeClass('modaled');
    });
    $(document).on('change', '.js-status', function() {
        var _this = $(this);
        $.ajax({
            url: window.location.href,
            data: {
                action: 'status',
                order: _this.data('id'),
                status: _this.val()
            },
            dataType: "json",
            method: 'get',
            success: function(res) {
                if(typeof res.error !== 'undefined') {
                    alert(res.error);
                    return;
                }
                alert(res.result);
            },
            error: function(res) {
                alert(res);
            }
        })
    })
})
