<?php

/* @TODO It is free to add helper functions here. */
/* ========== REGION START ========== */



/* ========== REGION END ========== */

/**
 * This function saves the order into the databse.
 * @param order an object containing order details
 */
function save_order($order) {
  /* @TODO Comment out the current return statement */
  /* ========== REGION START ========== */
  file_put_contents("order.json", json_encode($order, JSON_PRETTY_PRINT));
  echo json_encode($order);
  /* ========== REGION END ========== */

  /* @TODO Your Implementation Here. */
  /* ========== REGION START ========== */



  /* ========== REGION END ========== */
}

$json = file_get_contents("php://input");
$order = json_decode($json);
save_order($order);

?>