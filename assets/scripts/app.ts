import FlashHandler from './Core/FlashHandler';
import InputManager from './Auth/InputManager';
import AppHealthChecker from "./Core/AppHealthChecker";

document.addEventListener('DOMContentLoaded', async () => {
    new FlashHandler();
    new InputManager();
    new AppHealthChecker();
});