// Простой выпадающий список, с поддержкой асинхронной подгрузки данных на базе RxJS

const fetchData = {
  'clients': {
    url: v => '/diploma/api/clients?keyword=' + v,
    value: 'ID',
    text: 'name'
  },
  'products': {
    url: v => '/diploma/api/products?keyword=' + v,
    value: 'ID',
    text: 'name',
    onSelect: (v, n) => {
      $('.products-list').prepend(`
        <li class="g-pl-10 g-pr-80 g-py-5 g-pos-rel g-bg-primary--hover g-color-black--hover g-cursor-pointer">
          ${n}
          <span class="buttons pull-right g-pos-abs g-right-10 g-top-5">
            <input type="hidden" name="products[]" value="${v}" />
            <input type="text" name="quantity[]" class="quantity d-inline-block form-control g-bg-gray-dark-v3 g-brd-gray-dark-v4 g-color-white g-height-25 g-mr-5 g-pl-5 g-width-50" value="1" />
            <span class="g-color-red" onclick="this.closest('li').remove()">
              <i class="fa fa-trash-can"></i>
            </span>
          </span>
        </li>`
      )
      $('div[data-dropdown-id="products"] input').val('')
    }
  }
}
const fetchDelay = 400

const dropdowns = Array.from(document.querySelectorAll('[bgchar-dropdown]'))

if (dropdowns.length) {
  dropdowns.forEach(dropdown => dropdown.querySelector('ul').style.display = 'none')
  document.addEventListener('click', e => {
    dropdowns.forEach(dropdown => {
      const params = fetchData[dropdown.getAttribute('data-dropdown-id')]
      if (!dropdown.contains(e.target)) {
        dropdown.querySelector('ul').style.display = 'none'
        return
      }
      if (!dropdown.hasAttribute('data-dropdown-async') && dropdown.querySelector('input').contains(e.target)) {
        dropdown.querySelector('ul').style.display = 'block'
        return
      }
      dropdown.querySelectorAll('li').forEach(li => {
        if (li.contains(e.target)) {
          dropdown.querySelector('input').value = e.target.textContent
          dropdown.querySelector('ul').style.display = 'none'
          params.onSelect && params.onSelect(e.target.getAttribute('data-value'), e.target.textContent)
        }
      })
    })
  })

  document.querySelectorAll('[bgchar-dropdown][data-dropdown-async]').forEach(dropdown => {
    const input = dropdown.querySelector('input'),
          params = fetchData[dropdown.getAttribute('data-dropdown-id')],
          ul = dropdown.querySelector('ul')

    merge(fromEvent(input, 'input'), fromEvent(input, 'focus')).pipe(
      filter(e => !(e.type === 'focus' && ul.style.display === 'block')),
      debounceTime(400),
      switchMap(() => fromFetch(params.url(dropdown.querySelector('input').value)))
    ).subscribe(response => {
      ul.innerHTML = ''
      response.forEach(row => {
        ul.innerHTML += `<li data-value='${row[params.value]}' class='g-cursor-pointer'>${row[params.text]}</li>`
      })
      ul.style.display = 'block'
    });
  })
}