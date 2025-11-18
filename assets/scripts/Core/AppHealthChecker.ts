export default class AppHealthChecker
{
    private static CHECK_CONTAINER_CLASSES: Array<string> = ['text-center', 'text-sm', 'text-gray-500', 'my-2'];
    private static CHECK_SUCCESS_ICON: string = '<i class="fa-solid fa-circle-check"></i>';
    private static CHECK_FAILURE_ICON: string = '<i class="fa-solid fa-circle-xmark"></i>';
    private healthCheckContainer: HTMLElement|null = document.querySelector('.health-check-container');

    constructor()
    {
        if (this.healthCheckContainer instanceof HTMLElement) {
            this.runHealthChecks().then((): void => {
                this.healthCheckContainer.querySelector('.fa-spin')?.remove();
            });
        }
    }

    private async runHealthChecks(): Promise<void>
    {
        let checkResult: boolean = true;

        const checks: Array<IHealthCheck> = [
            {
                check: this.checkDatabaseConnection,
                message: 'Checking database connection...',
                successMessage: 'Database connection is OK',
                errorMessage: 'Database connection failed - update .env file'
            },
            {
                check: this.checkDefaultDatabaseTablesExist,
                message: 'Checking default tables exist...',
                successMessage: 'Default tables exist',
                errorMessage: 'Default tables do not exist - run data/setup.sql'
            },
        ];

        for (const check of checks) {
            if (!checkResult) break;

            checkResult = await this.runCheck(check.check, check.message, check.successMessage, check.errorMessage);
        }
    }

    private async runCheck(
        callback: () => Promise<boolean>,
        checkMessage: string,
        successMessage: string,
        errorMessage: string
    ): Promise<boolean> {
        const checkContainer: HTMLElement = this.createCheckContainer(checkMessage);

        this.healthCheckContainer?.insertBefore(checkContainer, this.healthCheckContainer.querySelector('.fa-spin'));

        return await callback().then((success: boolean): boolean => {
            checkContainer.innerHTML = success
                ? AppHealthChecker.CHECK_SUCCESS_ICON + ' ' + successMessage
                : AppHealthChecker.CHECK_FAILURE_ICON + ' ' + errorMessage;
            checkContainer.classList.replace('text-gray-500', success ? 'text-message-success' : 'text-message-error');

            return success;
        });
    }

    private createCheckContainer(message: string): HTMLElement
    {
        const checkContainer: HTMLElement = document.createElement('div');
        checkContainer.classList.add(...AppHealthChecker.CHECK_CONTAINER_CLASSES);
        checkContainer.innerHTML = message;

        return checkContainer;
    }

    private async checkDatabaseConnection(): Promise<boolean>
    {
        return await fetch('/health-check/database-connection')
            .then((response: Response): Promise<IHealthCheckResponse> => response.json())
            .then((json: IHealthCheckResponse): boolean => json.success);
    }

    private async checkDefaultDatabaseTablesExist(): Promise<boolean>
    {
        return await fetch('/health-check/default-tables-exist')
            .then((response: Response) => response.json())
            .then((json: IHealthCheckResponse) => json.success);
    }
}

interface IHealthCheckResponse
{
    success: boolean;
}

interface IHealthCheck
{
    check: () => Promise<boolean>;
    message: string;
    successMessage: string;
    errorMessage: string;
}