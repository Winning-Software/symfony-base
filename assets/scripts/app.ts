import AppHealthChecker from './Core/AppHealthChecker';
import FlashHandler from './Core/FlashHandler';
import InputManager from './Auth/InputManager';

document.addEventListener('DOMContentLoaded', async () => {
    new AppHealthChecker();
    new FlashHandler();
    new InputManager();
});