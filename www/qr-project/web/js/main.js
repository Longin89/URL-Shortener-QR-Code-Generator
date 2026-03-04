$(document).ready(function() {
    // Обработчик нажатия кнопки OK
    $('#create-btn').click(function() {
        createShortUrl();
    });
    
    // Обработчик нажатия Enter в поле ввода
    $('#url-input').keypress(function(e) {
        if (e.which === 13) {
            createShortUrl();
        }
    });
    
    // Обработчик создания еще одной ссылки
    $('#create-another-btn').click(function() {
        $('#result').addClass('d-none');
        $('#url-form').removeClass('d-none');
        $('#url-input').val('').focus();
        $('#error-message').addClass('d-none');
    });
    
    // Создание короткой ссылки
    function createShortUrl() {
        var url = $('#url-input').val().trim();
        
        if (!url) {
            showError('Введите URL');
            return;
        }
        
        // Скрываем ошибки (если есть) и результаты
        $('#error-message').addClass('d-none');
        $('#result').addClass('d-none');
        $('#loading').removeClass('d-none');
        $('#create-btn').prop('disabled', true);
        
        // Ajax запрос
        $.ajax({
            url: '/url/create',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({ url: url }),
            contentType: 'application/json',
            success: function(response) {
                $('#loading').addClass('d-none');
                $('#create-btn').prop('disabled', false);
                
                if (response.success) {
                    // Показываем результат
                    $('#qr-code').attr('src', response.data.qr_code);
                    $('#short-url').attr('href', response.data.short_url).text('http://' + response.data.short_code);
                    $('#original-url').val(response.data.original_url);
                    $('#hits-count').text(response.data.hits);
                    
                    $('#url-form').addClass('d-none');
                    $('#result').removeClass('d-none');
                } else {
                    showError(response.message || 'Произошла ошибка');
                }
            },
            error: function(xhr, status, error) {
                $('#loading').addClass('d-none');
                $('#create-btn').prop('disabled', false);
                showError('Ошибка сервера. Попробуйте позже.');
            }
        });
    }
    
    // Функция показа ошибки
    function showError(message) {
        $('#error-message').text(message).removeClass('d-none');
    }
});