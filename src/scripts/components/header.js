export default class {
    constructor({
        id,
        state,
    }) {
        const el = document.getElementById(id);

        function setHeaderHeight() {
            state.headerHeight = el.offsetHeight;
        }

        window.addEventListener('resize', setHeaderHeight);

        setHeaderHeight();
    }
}
