let eventTargetUrl = ''
let proceedDeleting = () => {
  document.location.href = eventTargetUrl
  $('#modal').modal('hide')
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    'delay': {show: 400, hide: 100}
  })

  $(document).on('click', "a[data-bs-original-title='Удалить']", function (e) {
    e.preventDefault()
    eventTargetUrl = e.target.closest('a').getAttribute('href')
    showModal('Подтвердите удаление', '<p class="m-0">Вы действительно хотите удалить запись?</p>', {
      text: 'Удалить',
      action: 'proceedDeleting()',
      color: 'btn-danger',
    })
  })

  // Показ блоков с резервом
  let reserveTimeout = {}
  $('.reserve-block .fa-circle-info').on({
    'mouseover': function () {
      const block = $(this).closest('.reserve-block').find('.position-absolute'), product = block.attr('data-product')
      if (reserveTimeout['product-' + product]) {
        clearTimeout(reserveTimeout['product-' + product])
      }
      block.fadeIn(200)
    },
    'mouseout': function () {
      const block = $(this).closest('.reserve-block').find('.position-absolute'), product = block.attr('data-product')
      reserveTimeout['product-' + product] = setTimeout(function () {
        block.fadeOut(200)
      }, 200)
    }
  })
  $('.reserve-block .position-absolute').on({
    'mouseover': function () {
      const block = $(this), product = block.attr('data-product')
      if (reserveTimeout['product-' + product]) {
        clearTimeout(reserveTimeout['product-' + product])
      }
      block.fadeIn(200)
    },
    'mouseout': function () {
      const block = $(this), product = block.attr('data-product')
      reserveTimeout['product-' + product] = setTimeout(function () {
        block.fadeOut(200)
      }, 200)
    }
  })
})

function showModal(title, body, button = {text: 'Сохранить', action: '', color: 'btn-primary'}) {
  $('#modal #modalTitle').text(title)
  $('#modal #modalBody').html(body)
  const buttonElement = $('#modal #modalProceed')
  buttonElement.text(button.text)
  buttonElement.attr('onclick', button.action)
  buttonElement.addClass(button.color)
  $('#modal').modal('show')
}