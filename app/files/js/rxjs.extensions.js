const {
  of, fromEvent, combineLatest, merge,
  map, tap, filter, switchMap, take, first,
  debounceTime, startWith,
  Observable, subscribe, pipe, unsubscribe
} = rxjs

const fromFetch = (url, responseType = 'json') => new Observable((observer) => {
  fetch(url).then(response => response[responseType]()).then(data => {
    observer.next(data)
    observer.complete()
  })
  .catch(err => observer.error(err))
});
