<?php

namespace app\dto;

class Order
{
  public int $ID;
  public int $product_id;
  public int $client_id;
  public Product $product;
  public int $quantity;
  public string $packed;
  /**
   * @var Client | null
   */
  
  public $client;
}