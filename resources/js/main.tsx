import 'vite/modulepreload-polyfill';
import 'bootstrap/dist/css/bootstrap.min.css';
import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import AppComponent from './AppComponent';
import { getBrowserLogger } from './lib/browserLogger';

// Initialize browser logger (stores logs in localStorage, no API calls)
// Available console commands: viewLogs(), clearLogs(), downloadLogs(), exportLogs()
getBrowserLogger();

// Render the React application
ReactDOM.createRoot(document.getElementById('root')!).render(
    <React.StrictMode>
        <AppComponent />
    </React.StrictMode>
);
