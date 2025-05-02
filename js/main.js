/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) => {
    const toggle = document.getElementById(toggleId),
          nav = document.getElementById(navId);

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            nav.classList.toggle('show-menu');
            toggle.classList.toggle('show-icon');
        });
    }
};

showMenu('nav-toggle', 'nav-menu');

/*=============== DROPDOWN MENU ONLY ON ARROW CLICK ===============*/
const dropdownItems = document.querySelectorAll('.dropdown-item');

dropdownItems.forEach(item => {
    const arrow = item.querySelector('.dropdown-arrow'); // <-- CSAK A NYÍLRA!

    if (arrow) {
        arrow.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Ne kattintson át másra

            // Ha ugyanarra kattintasz, toggle-öli
            item.classList.toggle('open');

            // Zárjuk be a többi nyitott dropdown-t
            dropdownItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('open');
                }
            });
        });
    }
});

// Üres helyre kattintásra zárjon minden dropdown
document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown-item')) {
        dropdownItems.forEach(item => item.classList.remove('open'));
    }
});
