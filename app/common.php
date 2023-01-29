<?php

function getModal($modal, $params = []): string {
  $modals = [
    'reserve' => [
      'Резервирование товара',
      fn() => addslashes(\system\core\Content::output('reserveModal', $params, true)),
      [
        'text' => 'Зарезервировать',
        'action' => 'reserve()'
      ]
    ]
  ];

  if (!isset($modals[$modal][0], $modals[$modal][1])) {
    return "Не хватает параметров";
  }
  return "showModal(
      '" . $modals[$modal][0] . "',
      `" . $modals[$modal][1]() . "`" . (isset($modals[$modal][2]) ? ",
      {
        text: '" . ($modals[$modal][2]['text'] ?? 'Сохранить') . "',
        action: '" . ($modals[$modal][2]['action'] ?? '') . "',
        color: '" . ($modals[$modal][2]['color'] ?? 'btn-primary') . "'
      }
    " : "") . ")";
}

return \system\classes\ArrayHolder::new([]);
