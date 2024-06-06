

// прокрутка страницы
window.onscroll = () =>{
    searchForm.classList.remove('active');
    if (window.scrollY > 80){
        document.querySelector('.header .header-2').classList.add('active');
    }else{
        document.querySelector('.header .header-2').classList.remove('active');
    }
}
window.onload = () =>{
    if (window.scrollY > 80){
        document.querySelector('.header .header-2').classList.add('active');
    }else{
        document.querySelector('.header .header-2').classList.remove('active');
    }
    fadeOut();
}

// слайдер
var swiper = new Swiper(".books-slider", {
    loop: true,
    centeredSlides: true,
    autoplay: {
        delay: 9500,
        disableOnInteraction: false,
    },
    breakpoints: {
      0:{
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
    },
  });


// экран загрузки
  function loader(){
    document.querySelector('.loader-container').classList.add('active');
  }

  function fadeOut(){
    setTimeout(loader,2000);
  }

