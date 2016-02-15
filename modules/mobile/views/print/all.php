<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 28.05.15
 * Time: 10:30
 * @var $numbers array
 */
?>
<table class="table">
    <?php foreach ($numbers as $number): ?>
        <tr>
            <td><?=$number['number'] ?></td>
            <td><?=$number['owner']->fullName ?></td>
            <td><?=$number['owner']->post ?></td>
            <td><?=$number['limit'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>