<?php

namespace app\dto;

class Order
{
  public int $ID;
  public int $product_id;
  /**
   * @var Product
   */
  public $product;
  public int $quantity;
  public string $packed;
}