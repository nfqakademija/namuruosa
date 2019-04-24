const toggle = (function () {
    let _menuButton = document.getElementById("menuButton");
    let _mySidenav = document.getElementById("mySidenav");
    let _menuArrow = document.getElementById("menuArrow");

    function _toggle(event)
    {
        if (_mySidenav.style.width === "250px") {
            _mySidenav.style.width = "0";
            _menuArrow.classList.remove('fa-caret-left');
            _menuArrow.classList.add('fa-caret-right');
        } else {
            _mySidenav.style.width = "250px";
            _menuArrow.classList.remove('fa-caret-right');
            _menuArrow.classList.add('fa-caret-left');
        }
    }

    function init()
    {
        _menuButton.addEventListener('click', _toggle);
    }

    return {
        init: init()
    }
})();
export default toggle;

