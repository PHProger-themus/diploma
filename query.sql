CREATE
TEMPORARY TABLE IF NOT EXISTS `oc_af_temporary` (PRIMARY KEY (`product_id`))
SELECT p.product_id,
       p.manufacturer_id,
       IF(p.quantity > 0, 6, p.stock_status_id) as stock_status_id,
       MIN(pd2.price)                           as discount,
       MIN(ps.price)                            as special,
       tax_p.sum_rate                           as tax_precent,
       tax_f.sum_rate                           as tax_fixed,
       AVG(rating)                              AS rating
FROM oc_product p
         LEFT JOIN `oc_af_tax_fixed` tax_f ON p.tax_class_id = tax_f.tax_class_id
         LEFT JOIN `oc_af_tax_percent` tax_p ON p.tax_class_id = tax_p.tax_class_id
         INNER JOIN oc_product_to_category p2c ON (p.product_id = p2c.product_id)
         LEFT JOIN oc_product_discount pd2 ON (pd2.product_id = p.product_id AND pd2.quantity = '1' AND
                                               (pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND
                                               (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()) AND
                                               pd2.customer_group_id = '1')
         LEFT JOIN oc_product_special ps
                   ON (ps.product_id = p.product_id AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) AND
                       (ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND ps.customer_group_id = '1')
         LEFT JOIN oc_review r1 ON (r1.product_id = p.product_id AND r1.status = 1)
         INNER JOIN oc_product_to_store AS p2s ON
    p2s.product_id = p.product_id AND p2s.store_id = '0'
WHERE p.date_available <= NOW()
  AND p.status = 1
  AND p2c.category_id = '131'
GROUP BY p.product_id


SELECT min(p.af_price) as min, max(p.af_price) as max
FROM (SELECT aft.*, ((IFNULL(aft.special, IFNULL(aft.discount, p2.price)))*(1+IFNULL(aft.tax_precent, 0)/100)+IFNULL(aft.tax_fixed, 0)) as af_price FROM `oc_af_temporary` aft
    INNER JOIN `oc_product` p2 ON aft.product_id = p2.product_id GROUP BY aft.product_id ) p



SELECT p.product_id, aft.rating, aft.discount, aft.special
FROM (SELECT aft.*,
             ((IFNULL(aft.special, IFNULL(aft.discount, IF(p2.rus_price IS NOT NULL, ROUND(p2.rus_price /
                                                                                           (SELECT value FROM oc_currency WHERE code = 'RUB'),
                                                                                           4), p2.price)))) *
              (1 + IFNULL(aft.tax_precent, 0) / 100) + IFNULL(aft.tax_fixed, 0)) as af_price,
             p2.af_values
      FROM `oc_af_temporary` aft
               INNER JOIN `oc_product` p2 ON aft.product_id = p2.product_id
      GROUP BY aft.product_id) aft
         INNER JOIN `oc_product` p ON aft.product_id = p.product_id
         INNER JOIN `oc_product_description` pd ON pd.product_id = p.product_id
WHERE pd.language_id = '2'
  AND aft.af_price BETWEEN '50330' AND '420000'
GROUP BY p.product_id
ORDER BY p.price DESC, LCASE(pd.name) DESC LIMIT 0,36