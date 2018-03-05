SELECT
  out_order_id,id,
  (

    SELECT count(*)

    FROM nit_order_product op
    WHERE op.order_id = nit_orders.id

          AND (

            (SELECT op2.quantity
             FROM nit_order_product op2
             WHERE op2.order_id = nit_orders.out_order_id)

            -

            (SELECT coalesce(sum(op2.quantity), 0)
             FROM nit_order_product op2 INNER JOIN nit_orders o2 ON op2.order_id = o2.id
             WHERE
               o2.type = 'devolution'
               AND o2.client_id = nit_orders.client_id
               AND o2.is_processed = 'Y')) < 0

  ) AS broken_products
FROM nit_orders
WHERE type = 'devolution'
having broken_products > 0;
