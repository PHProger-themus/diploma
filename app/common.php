<?php

function getModal($modal, $params = []): string
{
  return "showModal('{$modal}', " . str_replace('"', '\'', json_encode($params)) . ")";
}

return \system\classes\ArrayHolder::new([]);
