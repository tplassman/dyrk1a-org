import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';

export default class {
    constructor({
        id,
        closeHandle,
        contentHandle,
        activeClass,
        actions,
        events,
        refresh,
    }) {
        // Elements and class variables
        const el = document.getElementById(id);
        const content = el.querySelector(contentHandle);
        const close = el.querySelector(closeHandle);

        // Event handler functions
        function handleKeyup(e) {
            // Only car about escape key
            if (e.keyCode !== 27) return;

            events.emit(actions.closeModal);
        }
        function handleOpenModal() {
            el.classList.add(activeClass);
            disableBodyScroll(el);

            document.addEventListener('keyup', handleKeyup);
        }
        function handleCloseModal() {
            el.classList.remove(activeClass);
            enableBodyScroll(el);

            document.removeEventListener('keyup', handleKeyup);
        }
        function handleLoadModal(e) {
            const {
                markup,
                bg = 'dk',
            } = e.detail;

            el.setAttribute('data-bg', bg);
            content.innerHTML = markup;
            refresh(content);
            handleOpenModal();
        }
        function handleClick() {
            events.emit(actions.closeModal);
        }

        // Add event listeners
        events.on(actions.openModal, handleOpenModal);
        events.on(actions.closeModal, handleCloseModal);
        events.on(actions.loadModal, handleLoadModal);
        close.addEventListener('click', handleClick);
    }
}
