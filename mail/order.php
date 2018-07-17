
  <div class="table-responsive">
    <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>Наименование</th>
          <th>Кол-во</th>
          <th>Цена</th>
          <th>Сумма</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($session['cart'] as $id => $item): ?>
          <tr>
            <td><a href="<?php \yii\helpers\Url::to(['product/view', 'id'=>$id], true)?>"><?= $item['name']?></a></td>
            <td><?= $item['qty']?></td>
            <td><?= $item['price']?></td>
            <td><?= $item['price'] * $item['qty'] ?></td>
          </tr>
        <?php endforeach; ?>
          <tr>
            <td colspan="3">Итого: </td>
            <td><?= $session['cart.qty']?></td>
          </tr>
          <tr>
            <td colspan="3">На сумму: </td>
            <td><?= $session['cart.sum']?></td>
          </tr>
      </tbody>
    </table>
  </div>
