jQuery.extend(jQuery.validator.messages, {
    required: '這是必填欄位！',
    remote: '請修正此欄位！',
    email: '請輸入正確的 E-Mail 格式！',
    url: '請輸入正確的網址格式！',
    date: '請輸入正確的時間格式！',
    dateISO: '請輸入正確的 ISO 時間格式！',
    number: '請輸入正確的數字！',
    digits: 'Please enter only digits.',
    creditcard: '請輸入正確信用卡號碼！',
    equalTo: '請再輸入一樣的數字！',
    accept: 'Please enter a value with a valid extension.',
    maxlength: jQuery.validator.format('Please enter no more than {0} characters.'),
    minlength: jQuery.validator.format('Please enter at least {0} characters.'),
    rangelength: jQuery.validator.format('Please enter a value between {0} and {1} characters long.'),
    range: jQuery.validator.format('Please enter a value between {0} and {1}.'),
    max: jQuery.validator.format('Please enter a value less than or equal to {0}.'),
    min: jQuery.validator.format('Please enter a value greater than or equal to {0}.')
});