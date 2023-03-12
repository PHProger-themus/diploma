<?php

namespace app\dto;

class Order
{
  public int $ID;
  public string $name;
  public mixed $products;
  public int $client_id;
  public string $packed;
  /** @var Client | null */
  public $client;
}