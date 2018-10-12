$(document).ready(function () {
    $('table').hide();
    $('form input').on("change", function () {
        var file = this.files[0],
            fileName = file.name;
        extension = fileName.split(".");
        needExtension = "txt";
        fileSize = file.size;
        extension = extension[extension.length - 1].toLowerCase();
        if (fileSize > 2048) {
            alert("Файл должен быть размером не более 2 КБ");
        } else {
            if (extension === needExtension) {
                $('form p').text("Выбран файл: " + fileName);
                $('button#show').on("click", function (e) {
                    // e.preventDefault();
                    var form_data = new FormData();
                    form_data.append('file', file);

                    $.ajax({
                        url: "/show",
                        type: "POST",
                        dataType: "json",
                        data: form_data,
                        processData: false,
                        contentType: false
                    }).done(function (result) {
                        $('form p').text("Файл загружен");
                        $('table').show();
                        var finderText;
                        for (var i = 0; i < result.length; i++) {
                            for (var j = 0; j < result[i].length; j++) {
                                finderText = 'table tr:nth-child(' + (i+1) + ') td:nth-child(' + (j+1) + ')';
                                if (result[i][j] === '*') {
                                    result[i][j] = ' ';
                                }
                                $(finderText).append(result[i][j]);
                            }
                        }


                    })
                });
            } else {
                $('form p').text("Неправильный тип файла, должен быть только .txt файл");
            }
        }

    });

    $('button#result').on("click", function () {

    });
});




