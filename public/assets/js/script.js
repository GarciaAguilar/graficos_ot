const menusItemsDropdown = document.querySelectorAll('.menu-item-dropdown');
const menuItemsStatic = document.querySelectorAll('.menu-item-static');
const sidebar = document.getElementById('sidebar');
const menuBtn = document.getElementById('menu-btn');


menuBtn.addEventListener('click', () => {
    sidebar.classList.toggle('minimize');
    
    menusItemsDropdown.forEach((item) => {
        const subMenu = item.querySelector('.sub-menu');
        const isActive = item.classList.contains('sub-menu-toggle');
        const isMinimized = sidebar.classList.contains('minimize');
        
        if (subMenu && isActive) {
            if (isMinimized) {
                subMenu.style.height = '';
                subMenu.style.padding = '';
            } else {
                subMenu.style.height = `${subMenu.scrollHeight + 6}px`;
                subMenu.style.padding = '0.2rem 0';
            }
        }
    });
});

menusItemsDropdown.forEach((menuItem) => {
    menuItem.addEventListener('click', () => {
        const subMenu = menuItem.querySelector('.sub-menu');
        const isActive = menuItem.classList.toggle('sub-menu-toggle');
        const isMinimized = sidebar.classList.contains('minimize');
        
        if (subMenu) {
            if (isActive) {
                if (isMinimized) {
                    subMenu.style.height = '';
                    subMenu.style.padding = '';
                } else {
                    subMenu.style.height = `${subMenu.scrollHeight + 6}px`;
                    subMenu.style.padding = '0.2rem 0';
                }
            } else {
                subMenu.style.height = '0';
                subMenu.style.padding = '0';
            }
        }
        
        menusItemsDropdown.forEach((item) => {
            if (item !== menuItem) {
                const otherSubMenu = item.querySelector('.sub-menu');
                if (otherSubMenu) {
                    item.classList.remove('sub-menu-toggle');
                    otherSubMenu.style.height = '0';
                    otherSubMenu.style.padding = '0';
                }
            }
        });
    });
});

menuItemsStatic.forEach((menuItem) => {
    menuItem.addEventListener('mouseenter', () => {
        if (!sidebar.classList.contains('minimize')) return;

        menusItemsDropdown.forEach((item) => {
            const otherSubMenu = item.querySelector('.sub-menu');
            if (otherSubMenu) {
                item.classList.remove('sub-menu-toggle');
                otherSubMenu.style.height = '0';
                otherSubMenu.style.padding = '0';
            }
        });
    });
});
