import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import MainLayout from '@/layouts/MainLayout';
import HomePage from '@/pages/HomePage';
import ContactPage from '@/pages/ContactPage';
import MaintenancePage from '@/pages/MaintenancePage';
import { useMaintenanceMode } from '@/hooks/useMaintenanceMode';

// Corporate Pages
import ProfilKorporatPage from '@/pages/corporate/ProfilKorporatPage';
import CartaOrganisasiPage from '@/pages/corporate/CartaOrganisasiPage';
import DirektoriKakitanganPage from '@/pages/corporate/DirektoriKakitanganPage';
import LogoPage from '@/pages/corporate/LogoPage';
import PerutusanImamBesarPage from '@/pages/corporate/PerutusanImamBesarPage';
import SejarahMasjidPage from '@/pages/corporate/SejarahMasjidPage';

// Information Pages
import BeritaSemasaPage from '@/pages/information/BeritaSemasaPage';
import BeritaDetailPage from '@/pages/information/BeritaDetailPage';
import PengumumanPage from '@/pages/information/PengumumanPage';
import PengumumanDetailPage from '@/pages/information/PengumumanDetailPage';
import KemudahanPage from '@/pages/information/KemudahanPage';
import TakwimPage from '@/pages/information/TakwimPage';
import KutipanTabungMasjidPage from '@/pages/information/KutipanTabungMasjidPage';

// Download Pages
import BorangPage from '@/pages/download/BorangPage';
import JadualKuliahPage from '@/pages/download/JadualKuliahPage';
import NotaKuliahPage from '@/pages/download/NotaKuliahPage';

const AppContent: React.FC = () => {
    const { maintenance, loading } = useMaintenanceMode();

    // Show loading state while checking maintenance mode
    if (loading) {
        return (
            <div
                style={{
                    minHeight: '100vh',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    backgroundColor: '#f8f9fa'
                }}
            >
                <div style={{ textAlign: 'center' }}>
                    <img
                        src="/images/masjid.jpeg"
                        alt="Masjid Al Mustaghfirin"
                        style={{
                            width: '100px',
                            height: '100px',
                            objectFit: 'cover',
                            marginBottom: '1rem',
                            borderRadius: '50%'
                        }}
                    />
                    <p style={{ color: '#6c757d' }}>Memuat...</p>
                </div>
            </div>
        );
    }

    // Show maintenance page if in maintenance mode
    if (maintenance) {
        return <MaintenancePage />;
    }

    // Show normal application
    return (
        <Routes>
            <Route path="/" element={<MainLayout />}>
                <Route index element={<HomePage />} />
                <Route path="contact" element={<ContactPage />} />

                {/* Corporate Routes */}
                <Route path="corporate/perutusan" element={<PerutusanImamBesarPage />} />
                <Route path="corporate/sejarah" element={<SejarahMasjidPage />} />
                <Route path="corporate/profil" element={<ProfilKorporatPage />} />
                <Route path="corporate/logo" element={<LogoPage />} />
                <Route path="corporate/carta" element={<CartaOrganisasiPage />} />
                <Route path="corporate/direktori" element={<DirektoriKakitanganPage />} />

                {/* Information Routes */}
                <Route path="information/berita" element={<BeritaSemasaPage />} />
                <Route path="information/berita/:id" element={<BeritaDetailPage />} />
                <Route path="information/pengumuman" element={<PengumumanPage />} />
                <Route path="information/pengumuman/:id" element={<PengumumanDetailPage />} />
                <Route path="information/kemudahan" element={<KemudahanPage />} />
                <Route path="information/takwim" element={<TakwimPage />} />
                <Route path="information/kutipan" element={<KutipanTabungMasjidPage />} />

                {/* Download Routes */}
                <Route path="download/borang" element={<BorangPage />} />
                <Route path="download/jadual" element={<JadualKuliahPage />} />
                <Route path="download/nota" element={<NotaKuliahPage />} />
            </Route>
        </Routes>
    );
};

const AppComponent: React.FC = () => {
    return (
        <Router>
            <AppContent />
        </Router>
    );
};

export default AppComponent;
