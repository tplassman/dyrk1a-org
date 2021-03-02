export default class {
    constructor({
        id,
        conditional,
        actions,
        events,
    }) {
        const el = document.getElementById(id);
        const input = el.querySelector('input, select, textarea');
        const error = el.querySelector('p');
        const form = el.closest('form');
        const targets = conditional
            ? form.querySelectorAll(`[name="${conditional.name}"]`)
            : [];

        function handleSetInputError(e) {
            const { name, error: errorText } = e.detail;

            if (input.name !== name) {
                return;
            }

            error.textContent = errorText;
        }
        function handleTargetChange(e) {
            const { value } = e.currentTarget;

            el.style.display = conditional.value.includes(value) ? 'block' : 'none';
        }

        events.on(actions.setInputError, handleSetInputError);
        targets.forEach(target => {
            target.addEventListener('change', handleTargetChange);
        });

        // Initalize conditional formatting
        if (conditional) {
            const formData = new FormData(form);

            el.style.display = formData.get(conditional.name) === conditional.value ? 'block' : 'none';
        }
    }
}
