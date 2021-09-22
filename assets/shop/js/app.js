function setCookie(cname, cvalue, exdays) {
	let d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);

	let expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(";");
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == " ") {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function checkCookie() {
	let cookie = getCookie("policy");
	if (cookie != "") {
		return;
	} else {
		let cookieBar = document.querySelector(".cookies");

		cookieBar.classList.add("cookies--active");
		document.querySelector(".cookies .button").addEventListener("click", (e) => {
			setCookie("policy", true, 365);
			cookieBar.classList.remove("cookies--active");
		});
	}
}

function selectProductImage() {
	const slides = document.querySelectorAll(".glide__slide .responsive-img");

	for (let i = 0; i < slides.length; i++) {
		slides[i].addEventListener("click", function () {
			document.querySelector(".product__img").setAttribute("src", slides[i].getAttribute("src"));
		});
	}
}

function toggleMenu() {
	const nav = document.querySelector(".header__navbar--mobile .header__menu");
	const icon = document.querySelector(".header__navbar__icon");

	if (!nav.classList.contains("show")) {
		nav.classList.add("show");
		icon.classList.add("absolute");
		icon.src = "app/img/cancel.svg";
	} else {
		nav.classList.remove("show");
		icon.classList.remove("absolute");
		icon.src = "app/img/menu.svg";
	}
}
