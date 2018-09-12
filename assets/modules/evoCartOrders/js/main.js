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
                //alert(res.result);
                //$('.alert-heading').text(res.result);
                //$('.alert').addClass('show');
            },
            error: function(res) {
                alert(res);
            }
        })
    });

    var _clearPage = true;
    $('.js-ec-form').on('submit', function(e) {
        e.preventDefault();
        var _this = $(this);
        if(_clearPage) {
            $('#js-ec-page').val('');
        }
        $.ajax({
            url: window.location.href,
            data: _this.serialize(),
            dataType: "json",
            method: 'post',
            success: function(res) {
                $('.js-ec-list').html(res.list);
                $('.js-ec-pages').html(res.pages);
            },
            error: function(res) {
                alert(res);
            }
        });
        _clearPage = true;
    });

    $(document).on('click', '.js-ec-pages .page', function() {
        $('#js-ec-page').val($(this).data('page'));
        _clearPage = false;
        $('.js-ec-form').trigger('submit');
        return false;
    });
    $(document).on('change', '.js-ec-sort, .js-ec-display', function() {
        $('#js-ec-page').val('');
        $('.js-ec-form').trigger('submit');
    });

})
