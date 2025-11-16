document.addEventListener('DOMContentLoaded', function () {
		const toggle = document.querySelector('.nav-toggle');
		const navLinks = document.querySelector('.nav-links');
		const NAV_BREAKPOINT = 768; 

		if (!toggle || !navLinks) return; 

		toggle.addEventListener('click', function () {
				navLinks.classList.toggle('open');
		});

		window.addEventListener('resize', function () {
				if (window.innerWidth > NAV_BREAKPOINT && navLinks.classList.contains('open')) {
						navLinks.classList.remove('open');
				}
		});

		navLinks.addEventListener('click', function (e) {
				const target = e.target;
				if (target.tagName === 'A' && navLinks.classList.contains('open')) {
						navLinks.classList.remove('open');
				}
		});
});
