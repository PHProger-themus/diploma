$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    'delay': {show: 400, hide: 100}
  });

  // Показ блоков с резервом
  let reserveTimeout = {};
  $('.reserve-block .fa-circle-info').on({
    'mouseover' : function() {
      const block = $(this).closest('.reserve-block').find('.position-absolute'), product = block.attr('data-product')
      if (reserveTimeout['product-' + product]) {
        clearTimeout(reserveTimeout['product-' + product])
      }
      block.fadeIn(200);
    },
    'mouseout' : function() {
      const block = $(this).closest('.reserve-block').find('.position-absolute'), product = block.attr('data-product')
      reserveTimeout['product-' + product] = setTimeout(function () {
        block.fadeOut(200);
      }, 200)
    }
  })
  $('.reserve-block .position-absolute').on({
    'mouseover' : function() {
      const block = $(this), product = block.attr('data-product')
      if (reserveTimeout['product-' + product]) {
        clearTimeout(reserveTimeout['product-' + product])
      }
      block.fadeIn(200);
    },
    'mouseout' : function() {
      const block = $(this), product = block.attr('data-product')
      reserveTimeout['product-' + product] = setTimeout(function () {
        block.fadeOut(200);
      }, 200)
    }
  })
})