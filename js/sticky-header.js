const header = document.querySelector('.header')
let lastScroll = 0
const hideOffset = 150

const scrollPosition = () => window.scrollY || document.documentElement.scrollTop

const containHide = () => header.classList.contains('hide')

window.addEventListener('scroll', () => {
  if (scrollPosition() > header.offsetHeight) {
    header.classList.add('sticky')
  } else {
    header.classList.remove('sticky')
  }

  if (scrollPosition() > lastScroll && !containHide() && scrollPosition() > hideOffset) {
    header.classList.add('hide')
  } else if (scrollPosition() < lastScroll && containHide()) {
    header.classList.remove('hide')
  }

  if (scrollPosition() <= hideOffset && containHide()) {
    header.classList.remove('hide')
  }

  lastScroll = scrollPosition()
})
