// Простой выпадающий список, с поддержкой асинхронной подгрузки данных на базе RxJS

const fetchData = {
  'clients': {
    url: v => '/diploma/clients?keyword=' + v,
    prop: 'name'
  }
}
const fetchDelay = 400

const dropdowns = Array.from(document.querySelectorAll('[bgchar-dropdown]'))

if (dropdowns.length) {
  dropdowns.forEach(dropdown => dropdown.querySelector('ul').style.display = 'none')
  document.addEventListener('click', e => {
    dropdowns.forEach(dropdown => {
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
        }
      })
    })
  })

  document.querySelectorAll('[bgchar-dropdown][data-dropdown-async]').forEach(dropdown => {
    const input = dropdown.querySelector('input'),
          data = fetchData[dropdown.getAttribute('data-dropdown-async')],
          ul = dropdown.querySelector('ul')

    merge(fromEvent(input, 'input'), fromEvent(input, 'focus')).pipe(
      filter(e => !(e.type === 'focus' && ul.style.display === 'block')),
      debounceTime(400),
      switchMap(() => fromFetch(data.url(dropdown.querySelector('input').value)))
    ).subscribe(response => {
      ul.innerHTML = ''
      response.forEach(row => {
        ul.innerHTML += `<li class='g-cursor-pointer'>${row[data.prop]}</li>`
      })
      ul.style.display = 'block'
    });
  })
}