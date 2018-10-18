$(document).ready(function () {
    var myApp = {};
    $('i#circle').hide();
    $('table').hide();
    var table_showed = 0;
    $('form input').on("change", function kek() {
        var files = this.files;
        myApp.files = files;
        var file = this.files[0],
            fileName = file.name,
            extension = fileName.split("."),
            needExtension = "txt",
            fileSize = file.size,
            extension = extension[extension.length - 1].toLowerCase();
        myApp.file = file;
        myApp.fileName = file.name;
        if (fileSize > 2048) {
            alert("Файл должен быть размером не более 2 КБ");
        } else {
            if (extension === needExtension) {
                $('form p').text("Выбран файл: " + fileName);
            } else {
                $('form p').text("Неправильный тип файла, должен быть только .txt файл");
            }
        }
        return files;

    });
    $('button#show').on("click", function (e) {
        $('i#circle').show();
        var form_data = new FormData();
        form_data.set('file', myApp.file);

        $.ajax({
            url: "/show",
            type: "POST",
            dataType: "json",
            data: form_data,
            processData: false,
            contentType: false
        }).done(function (result) {
            $('i#circle').hide();
            var json_result = result;
            $('form p').text("Файл " + myApp.fileName + " загружен");
            if (table_showed === 0) {
                $('table').show();
                table_showed = 1;
            }
            var finderText;
            for (var i = 0; i < json_result[0].length; i++) {
                for (var j = 0; j < json_result[0][i].length; j++) {
                    finderText = 'table tr:nth-child(' + (i + 1) + ') td:nth-child(' + (j + 1) + ')';
                    if (json_result[0][i][j] === '*') {
                        json_result[0][i][j] = ' ';
                    }
                    $(finderText).empty();

                    $(finderText).append(json_result[0][i][j]);

                }
            }
            $('button#result').on("click", function () {
                var finderText;
                for (var i = 0; i < json_result[1].length; i++) {
                    for (var j = 0; j < json_result[1][i].length; j++) {
                        finderText = 'table tr:nth-child(' + (i + 1) + ') td:nth-child(' + (j + 1) + ')';
                        if (json_result[1][i][j] === '*') {
                            json_result[1][i][j] = ' ';
                        }
                        $(finderText).empty();
                        $(finderText).append(json_result[1][i][j]);
                    }
                }
            });
        })
    });
});




