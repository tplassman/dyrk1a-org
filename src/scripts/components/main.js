import { scroll } from 'ui-utilities';

export default class {
    constructor({
        id,
        state,
    }) {
        const el = document.getElementById(id);
        const anchors = el.querySelectorAll('a[href*="#"][data-smooth-scroll="true"]:not([data-ready="true"])');

        function handleAnchor(e) {
            e.preventDefault();

            scroll.to(e.currentTarget.href.split('#')[1], state.headerHeight);
        }

        anchors.forEach(a => {
            a.addEventListener('click', handleAnchor);
            a.setAttribute('data-ready', true);
        });
    }
}
