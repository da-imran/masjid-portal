import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '@/components/Header';
import Footer from '@/components/Footer';

const MainLayout: React.FC = () => {
    return (
        <div style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
            <Header />
            <main className="container-fluid px-3 px-md-4 px-lg-5" style={{ flex: 1, paddingTop: '20px', paddingBottom: '20px' }}>
                <div className="row">
                    <div className="col-12">
                        <Outlet />
                    </div>
                </div>
            </main>
            <Footer />
        </div>
    );
};

export default MainLayout;
