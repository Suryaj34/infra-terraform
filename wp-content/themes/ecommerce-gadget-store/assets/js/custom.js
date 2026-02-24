jQuery(document).ready(function() {
jQuery(".product-heading h2").each(function() {
	var ecommerce_gadget_store_t = jQuery(this).text();
	var ecommerce_gadget_store_splitT = ecommerce_gadget_store_t.split(" ");
	var ecommerce_gadget_store_lastSecondIndex = ecommerce_gadget_store_splitT.length - 2;
	var ecommerce_gadget_store_lastIndex = ecommerce_gadget_store_splitT.length - 1;
	var ecommerce_gadget_store_newText = "";
	for (var ecommerce_gadget_store_i = 0; ecommerce_gadget_store_i < ecommerce_gadget_store_splitT.length; ecommerce_gadget_store_i++) {
		if (ecommerce_gadget_store_i == ecommerce_gadget_store_lastSecondIndex || ecommerce_gadget_store_i == ecommerce_gadget_store_lastIndex) {
			ecommerce_gadget_store_newText += "<span style='color:var(--wp--preset--color--accent)'>";
			ecommerce_gadget_store_newText += ecommerce_gadget_store_splitT[ecommerce_gadget_store_i] + " ";
			ecommerce_gadget_store_newText += "</span>";
		} else {
			ecommerce_gadget_store_newText += ecommerce_gadget_store_splitT[ecommerce_gadget_store_i] + " ";
		}
	}
	jQuery(this).html(ecommerce_gadget_store_newText);
});
});

// swiper js
jQuery(document).ready(function () {
  var ecommerce_gadget_store_swiper_testimonials = new Swiper(".testimonial-swiper-slider.mySwiper", {
    slidesPerView: 3,
      spaceBetween: 15,
      speed: 1000,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".testimonial-swiper-button-next",
        prevEl: ".testimonial-swiper-button-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        767: {
          slidesPerView: 2,
        },
        1023: {
          slidesPerView: 3,
        }
      },
  })
});