<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <h1><?php echo $text_pickpacklist; ?></h1>
  
  <div style="page-break-after: always;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><?php echo "Order No."; ?></td>
          <td><?php echo "Collection Method"; ?></td>
          <td><?php echo "Date"; ?></td>
          <td><?php echo "Time"; ?></td>
          <td><?php echo "Customer Detail"; ?></td>
          <td><?php echo "Order Detail"; ?></td>
          <td><?php echo "SKU"; ?></td>
          <td><?php echo "Model"; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
        <?php foreach ($order['product'] as $product) { ?>
            <tr>
              <td><?php echo $order['order_id']; ?></td>
              <td><?php echo $order['shipping_method']; ?></td>
              <td><?php echo $order['delivery_date']; ?></td>
              <td><?php echo $order['delivery_time']; ?></td>
              <td><?php echo $order['shipping_address']."<br>Contact No: ". $order['telephone']; ?></td>
              <!--<td><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>-->
              <td><?php echo $product['detail']; ?></td>
              <td><?php echo $product['sku_detail']; ?></td>
              <td><?php echo $product['model']; ?></td>
            </tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </div>
  
</div>
</body>
</html>