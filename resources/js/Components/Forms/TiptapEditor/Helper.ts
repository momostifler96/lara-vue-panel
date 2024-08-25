
const createDropdown = (btn: HTMLButtonElement, dropdown: HTMLDivElement, closeClickIn: boolean = false) => {
    btn.addEventListener('click', (e: Event) => {
        e.preventDefault();
        e.stopPropagation();
        dropdown.classList.add('editor-dropdown-open');
        const btnRect = btn.getBoundingClientRect();
        const dropdownRect = dropdown.getBoundingClientRect();
        const windowHeight = window.innerHeight
        const bodyRect = document.body.getBoundingClientRect();

        const viewportHeight = window.innerHeight;
        btn.classList.add('active');
        // dropdown.style.bottom = 'auto';

        console.log('(btnRect.bottom + dropdownRect.height) <= windowHeight', (btnRect.bottom + dropdownRect.height) < windowHeight, dropdownRect, btnRect.bottom, dropdownRect.height, windowHeight);
        if ((btnRect.bottom + dropdownRect.height) < windowHeight) {
            dropdown.style.top = '100%';
            dropdown.style.bottom = 'auto';
        } else {
            dropdown.style.bottom = '100%';
            dropdown.style.top = 'auto';
        }

        // dropdown.style.top = `${btnRect.top}px`;
        dropdown.style.left = `0px`;
    });
    const closeDropdown = (e: Event) => {
        e.preventDefault();
        if (closeClickIn) {
            if (dropdown && dropdown.classList.contains('editor-dropdown-open')) {
                dropdown.classList.remove('editor-dropdown-open');
            }
            btn.classList.remove('active');
        } else {
            if (dropdown && !dropdown.contains(e.target) && dropdown.classList.contains('editor-dropdown-open')) {
                dropdown.classList.remove('editor-dropdown-open');
                btn.classList.remove('active')
            }
        }


    }

    document.addEventListener('click', closeDropdown);
}

export { createDropdown };