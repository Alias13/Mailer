<?php
if(isset($_POST['submit'])) {
    require 'phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->isSMTP();

    $mail->Host = 'smtp.mail.ru';
    $mail->SMTPAuth = true;
    $mail->Username = 'test_polaz'; // логин от вашей почты
    $mail->Password = 'password12345'; // пароль от почтового ящика
    $mail->SMTPSecure = 'ssl';
    $mail->Port = '465';

    $mail->CharSet = 'UTF-8';
    $mail->From = 'test_polaz@mail.ru'; // адрес почты, с которой идет отправка
    if (isset($_POST['author'])) {
        $mail->FromName = $_POST['author']; // имя отправителя
    }
    $mail->addAddress($_POST['email']); //адрес получателя

    $mail->isHTML(true);

    if (isset($_POST['subject'])) {
        $mail->Subject = $_POST['subject']; // тема письма
    }
    if ($_POST['text']) {
        $mail->Body = $_POST['text']; //текст письма
    } else {
        $mail->Body = ' ';
    }

    $error = false;
    if (!empty($_FILES['file']['name'][0]) && (array_sum($_FILES['file']['size']) < 10000000)) {
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            if (is_uploaded_file($_FILES['file']['tmp_name'][$i]) && ($_FILES['file']['error'][$i] == 0)) {
                $mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]);
            } else {
                $error = 'Не удалось загрузить Файл(ы)';
                break;
            }
        }
    }
    if (!$error && $mail->send()) {
        echo  '<script>       
                    alert("Письмо отправлено");
               </script>';

    } else {
        echo  '<script>       
                    alert("Письмо не отправлено \nОшибка: ' . $error . '\n' . $mail->ErrorInfo . '");
               </script>';

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mailer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <style>
        #feedback-form {
            width: 400px; margin: 2% auto;
        }
        @media (max-width: 550px) {
            #feedback-form {width: 100%; margin: 2%;}
        }
    </style>
</head>
<body>
<div>
    <form method="post" enctype="multipart/form-data"  id="feedback-form">
        <div class="form-group">
            <label for="InputEmail">Адрес получателя</label>
            <input type="email" class="form-control" id="InputEmail" placeholder="Email" required name="email">
        </div>
        <div class="form-group">
            <label for="InputSubject">Тема письма</label>
            <input type="text" class="form-control" id="InputSubject" placeholder="Тема" name="subject">
        </div>
        <div class="form-group">
            <label for="InputAuthor">Автор письма</label>
            <input type="text" class="form-control" id="InputAuthor" placeholder="Имя" name="author">
        </div>
        <div class="form-group">
            <label for="InputText">Текст письма</label>
            <textarea  id="InputText"  name="text"></textarea>
        </div>
        <div class="form-group">
            <label for="InputFile" style="margin-right: 5%;">Прикрепить файлы </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
            <input type="file" style="display: inline-block;" id="InputFile" multiple name="file[]">
        </div>
        <button type="submit" class="btn btn-default" name="submit">Отправить</button>
    </form>
</div>

<script>
    CKEDITOR.replace("text");
</script>
</body>
</html>