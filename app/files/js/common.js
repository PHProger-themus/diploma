let eventTargetUrl = ''
let proceedDeleting = () => {
  document.location.href = eventTargetUrl
  $('#modal').modal('hide')
}
let modals = {
  'reserve': {
    'title': 'Резервирование товара',
    'action': (data) => {
      const company = $('#modalCompany').val(), quantity = $('#modalQuantity').val()
      if (company && quantity) {
        $.ajax({
          url: '/diploma/ajax',
          method: 'POST',
          data: {
            action: 'reserveFor',
            productId: data.productId,
            company, quantity,
          },
          beforeSend: () => modalLoading('show'),
          success: () => {
            modalLoading('hide')
            $('#modal').modal('hide')
          }
        })
      }
    },
    'button': 'Зарезервировать',
    'body': (data) => `<p class='g-mb-8'>Зарезервируйте товар, указав наименование физ. лица или юр. лица и количество товара.</p>
        <form data-reserveFor='${data.productId}'>
          <label for='name'>Наименование <span class='g-color-red'>*</span></label>
          <input type='text' class='form-control g-mb-8' id='modalCompany' name='name' />
          <label for='quantity'>Количество <span class='g-color-red'>*</span></label>
          <input type='number' class='form-control' id='modalQuantity' name='quantity' />
        </form>`
  },

  'delete': {
    'title': 'Подтвердите удаление',
    'action': proceedDeleting,
    'button': 'Удалить',
    'buttonColor': 'btn-danger',
    'body': () => `<p class="m-0">Вы действительно хотите удалить запись?</p>`
  }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    'delay': {show: 400, hide: 100}
  })

  $(document).on('click', "a[data-delete]", function (e) {
    e.preventDefault()
    eventTargetUrl = e.target.closest('a').getAttribute('href')
    showModal('delete')
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

function showModal(modal, bodyData) {
  const modalData = modals[modal]
  if (modalData['body']) {
    $('#modal #modalTitle').text(modalData.title || 'Действие')
    $('#modal #modalBody').html(modalData.body(bodyData))
    const buttonElement = $('#modal #modalProceed')
    buttonElement.html((modalData.button || 'Сохранить') + "<i class='fa fa-spinner fa-spin g-ml-5 d-none'></i>")
    modalData.action && buttonElement.click(modalData.action.bind(null, bodyData))
    buttonElement.addClass(modalData.buttonColor || 'btn-primary')
    $('#modal').modal('show')
  } else {
    console.error('Для показа модального окна требуется содержимое (свойство body в объекте модальных окон)')
  }
}

function modalLoading(action) {
  const method = action === 'show' ? 'removeClass' : 'addClass'
  $('#modalProceed i.fa-spinner')[method]('d-none')
}