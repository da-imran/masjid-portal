import 'vite/modulepreload-polyfill';
import 'bootstrap/dist/css/bootstrap.min.css';
import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import AppComponent from './AppComponent';

// Render the React application
ReactDOM.createRoot(document.getElementById('root')!).render(
    <React.StrictMode>
        <AppComponent />
    </React.StrictMode>
);
