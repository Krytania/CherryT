const swiper = new Swiper(".mySwiper", {
	loop: true,
		autoplay: {
		delay: 5000,
		disableOnInteraction: false,
	},
	pagination: {
		el: ".swiper-pagination",
		clickable: true,
	},
	navigation: {
		nextEl: document.querySelector(".swiper-button-next"),
		prevEl: document.querySelector(".swiper-button-prev"),
	},
});