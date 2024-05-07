/*CAROUSEL*/

const dynamicCarousel = () => {
  //Elementos del DOM
  const tracker = document.querySelector(".carousel__tracker"); //ul list
  const slides = Array.from(tracker.children); //li elements
  const prevBtn = document.querySelector("#prevImgCarousel");
  const nextBtn = document.querySelector("#nextImgCarousel");

  //counter navigation
  //size of slides
  const { width: slidesWidth } = slides[0].getBoundingClientRect();
  const maxLength = slides.length;
  let currentIndex = 0;

  //change positions
  const setSlidePosition = (slide, index) => {
    slide.style.left = `${slidesWidth * index}px`;
  };

  slides.forEach(setSlidePosition);

  const moveToSlide = (tracker, currentSlide, targetSlide) => {
    const amountToMove = targetSlide.style.left;
    //move at the moment we click
    tracker.style.transform = `translateX(-${amountToMove})`;
    //move class into previous sibling
    currentSlide.classList.remove("current__slide");
    targetSlide.classList.add("current__slide");
  };

  const toggleVisibilityBtn = (btnToHide, btnToShow) => {
    btnToHide.classList.add("hidden");
    btnToShow.classList.remove("hidden");
  };
  const clearVisibility = (btn1, btn2) => {
    btn1.classList.remove("hidden");
    btn2.classList.remove("hidden");
  };

  const changePrevImageHandler = () => {
    const currentSlide = tracker.querySelector(".current__slide"); //current image show
    const previousSlide = currentSlide.previousElementSibling; //next image to be shown

    if (currentIndex >= 0) {
      moveToSlide(tracker, currentSlide, previousSlide);
      currentIndex--;
      currentIndex === 0
        ? toggleVisibilityBtn(prevBtn, nextBtn)
        : clearVisibility(nextBtn, prevBtn);
    }
  };

  const changeNextImageHandler = () => {
    const currentSlide = tracker.querySelector(".current__slide"); //current image show
    const nextSlide = currentSlide.nextElementSibling; //next image to be shown
    if (currentIndex <= maxLength - 2) {
      moveToSlide(tracker, currentSlide, nextSlide);
      currentIndex++;
      currentIndex === maxLength - 1
        ? toggleVisibilityBtn(nextBtn, prevBtn)
        : clearVisibility(nextBtn, prevBtn);
    }
  };

  prevBtn.addEventListener("click", changePrevImageHandler);
  nextBtn.addEventListener("click", changeNextImageHandler);
};

document.addEventListener("DOMContentLoaded", dynamicCarousel);
