export default class InputManager
{
    inputs: NodeListOf<HTMLInputElement>;

    constructor()
    {
        this.inputs = document.querySelectorAll('input');

        this.inputs.forEach((input: HTMLInputElement) => {
            input.addEventListener('focus', () => {
                input.classList.add('border-auth-primary');
                input.classList.remove('border-message-error');

                input.nextElementSibling?.remove();
            });
        });
    }
}