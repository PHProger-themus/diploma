<?php

namespace app\dto;

class Product
{
  public int $ID;
  public string $name;
  public float $price;
  public int $quantity;
  public string $quantityColor;
  /**
   * @var Reserve[]
   */
  public $reserve;
  public int $reserveTotal;
}