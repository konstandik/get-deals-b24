<?require_once (__DIR__.'/crest.php');?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Отображение списка сделок из Б24 по статусам</title>
    <link rel="stylesheet" href="style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
<h1>Отображение списка сделок из Б24 по статусам</h1>

<?//получаем список статусов
$statusListRes = CRest::call(
    'crm.status.list',
    array(
        'filter' => array(
            'ENTITY_ID' => 'DEAL_STAGE'
        ),
    ));
?>
<div class="btn-panel">
<?foreach ($statusListRes['result'] as $status){?>
    <div class="form_radio_btn">
        <input id="radio-<?=$status['STATUS_ID']?>"
               type="radio"
               name="radio"
               value="<?=$status['STATUS_ID']?>"

               onClick = "getdetails(this)"
               data-status="<?=$status['STATUS_ID']?>"
        >
        <label for="radio-<?=$status['STATUS_ID']?>"><?=$status['NAME']?></label>
    </div>
<?}?>
</div>

<div class="deals">
</div>

<script>
    function getdetails(obj){
        var status = obj.getAttribute('data-status');
    $.ajax({
        url: 'ajax.php',         // Куда пойдет запрос
        method: 'POST',                      //* Метод передачи (post или get)
        dataType: 'html',                   // Тип данных в ответе (xml, json, script, html).
        cache: false,
        data: {STAGE_ID: status},              // Параметры передаваемые в запросе.
        success: function(data){// функция которая будет выполнена после успешного запроса.
            $(".deals").html(data);
        }
    })
    }
</script>

</body>
</html>
