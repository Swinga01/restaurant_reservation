import './bootstrap.js';
import './styles/app.css';

// Import and register all Stimulus controllers
import { application } from './bootstrap.js';

// Import your custom controllers
import ReservationValidationController from './controllers/reservation_validation_controller.js';

// Register controllers
application.register('reservation-validation', ReservationValidationController);

console.log('Stimulus controllers registered - including reservation validation');
