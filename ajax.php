<?require_once (__DIR__.'/crest.php');

function fomatStandat($value){
    return number_format($value, 2, ',', ' ');
}

$status =  $_POST['STAGE_ID'];//берем статус

$cdlCall = CRest::call(
    'crm.deal.list',
            array(
                'filter' => array(
                'STAGE_ID' => $status
        ),
                'select' => array(
                    'ID',
                    'TITLE',
                    'OPPORTUNITY',
                ),
    ));

$batchCmd = array();
foreach ($cdlCall['result'] as $deal){
    $batchCmd[$deal['ID']] = 'crm.productrow.list?filter[OWNER_TYPE]=D&filter[OWNER_ID]='.$deal['ID'].'&select[]=PRODUCT_NAME&select[]=PRICE&select[]=QUANTITY&select[]=MEASURE_NAME';
}

//sleep(1);

$batch = CRest::call(
    'batch',
    array(
        'halt' => 0,
        'cmd'=> $batchCmd
    )
);
if(!$batch['result']['result'][$deal['ID']]){?>
    <div class="deals-item">
        <h2>Сделок нет</h2>
    </div>
<?}else {
    foreach ($cdlCall['result'] as $deal) {
        ?>
        <div class="deals-item">
            <h2>Сделка №<?= $deal['ID'] ?></h2>
            <?
            if (!$batch['result']['result'][$deal['ID']]) {
                ?>
                <h2>Сумма сделки: <?= fomatStandat($deal['OPPORTUNITY']) ?></h2>
            <?
            } else {
                ?>
                <table>
                    <thead>
                    <tr>
                        <th>Название товара</th>
                        <th>Кол-во</th>
                        <th>Цена</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    foreach ($batch['result']['result'][$deal['ID']] as $product){?>
                        <tr>
                            <td><?= $product['PRODUCT_NAME']?></td>
                            <td><?= fomatStandat($product['QUANTITY'])?> <?= $product['MEASURE_NAME']?></td>
                            <td><?= fomatStandat($product['PRICE']) ?></td>
                        </tr>
                    <?
                    } ?>
                    <tr>
                        <td colspan="2"><b>Итого</b></td>
                        <td><b><?= fomatStandat($deal['OPPORTUNITY'])?></b></td>
                    </tr>
                    </tbody>
                </table>
            <?
            } ?>
        </div>
    <?
    }
}
?>