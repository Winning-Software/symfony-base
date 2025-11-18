import FlashHandler from './Core/FlashHandler';

document.addEventListener('DOMContentLoaded', async () => {
    new FlashHandler();

    const inputs: NodeListOf<HTMLInputElement> = document.querySelectorAll('input');

    inputs.forEach((input: HTMLInputElement) => {
        input.addEventListener('focus', () => {
            if (input.classList.contains('border-red-500')) {
                input.classList.add('border-gray-200');
                input.classList.remove('border-red-500');

                input.nextElementSibling?.remove();
            }
        });
    });

    const healthCheckContainer: HTMLElement|null = document.querySelector('.health-check-container');

    if (healthCheckContainer) {
        checkDatabaseConnection(healthCheckContainer).then((success: boolean) => {
            if (success) {
                checkDefaultTablesExist(healthCheckContainer).then((success: boolean) => {
                    healthCheckContainer.querySelector('.fa-spin')?.remove();
                })
            } else {
                healthCheckContainer.querySelector('.fa-spin')?.remove();
            }
        });
    }
});

interface HealthCheckResponse
{
    success: boolean;
}
async function checkDatabaseConnection(container: HTMLElement): Promise<boolean>
{
    const checkContainer: HTMLElement = document.createElement('div');
    checkContainer.classList.add('text-center', 'text-sm', 'text-gray-500', 'my-2');
    checkContainer.innerHTML = 'Checking database connection...';

    container.insertBefore(checkContainer, container.querySelector('.fa-spin'));

    return await fetch('/health-check/database-connection')
        .then((response: Response) => response.json())
        .then((json: HealthCheckResponse) => {
            return json.success
        })
        .then((success: boolean) => {
            checkContainer.innerHTML = success ? '<i class="fa-solid fa-circle-check"></i> Database connection is OK' : '<i class="fa-solid fa-circle-xmark"></i> Database connection failed - update .env file';
            checkContainer.classList.replace('text-gray-500', success ? 'text-green-600' : 'text-red-600');
            return success;
        });
}

async function checkDefaultTablesExist(container: HTMLElement)
{
    const checkContainer: HTMLElement = document.createElement('div');
    checkContainer.classList.add('text-center', 'text-sm', 'text-gray-500', 'my-2');
    checkContainer.innerHTML = 'Checking default tables exist...';

    container.insertBefore(checkContainer, container.querySelector('.fa-spin'));

    return await fetch('/health-check/default-tables-exist')
        .then((response: Response) => response.json())
        .then((json: HealthCheckResponse) => {
            checkContainer.innerHTML = json.success ? '<i class="fa-solid fa-circle-check"></i> Default tables exist' : '<i class="fa-solid fa-circle-xmark"></i> Default tables do not exist - run data/setup.sql';
            checkContainer.classList.replace('text-gray-500', json.success ? 'text-green-600' : 'text-red-600');

            return json.success;
        });
}