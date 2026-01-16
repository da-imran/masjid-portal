import React from 'react';

const MaintenancePage: React.FC = () => {
    return (
        <div
            style={{
                minHeight: '100vh',
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                justifyContent: 'center',
                backgroundColor: '#f8f9fa',
                padding: '20px'
            }}
        >
            <div
                style={{
                    textAlign: 'center',
                    maxWidth: '600px',
                    width: '100%'
                }}
            >
                {/* Masjid Logo */}
                <img
                    src="/images/masjid.jpeg"
                    alt="Masjid Al Mustaghfirin"
                    style={{
                        width: '150px',
                        height: '150px',
                        objectFit: 'cover',
                        marginBottom: '2rem',
                        borderRadius: '50%'
                    }}
                />

                {/* Maintenance Message */}
                <h1
                    style={{
                        fontSize: '2rem',
                        fontWeight: 'bold',
                        color: '#1f1f1f',
                        marginBottom: '1rem'
                    }}
                >
                    Harap Maaf.
                </h1>

                <p
                    style={{
                        fontSize: '1.25rem',
                        color: '#6c757d',
                        marginBottom: '2rem',
                        lineHeight: '1.6'
                    }}
                >
                    Kami sedang melakukan kerja penyelengaraan.<br />
                    Sila cuba sebentar lagi.
                </p>

                {/* Optional: Contact Information */}
                <div
                    style={{
                        marginTop: '3rem',
                        padding: '1.5rem',
                        backgroundColor: 'white',
                        borderRadius: '8px',
                        boxShadow: '0 2px 4px rgba(0,0,0,0.1)'
                    }}
                >
                    <p style={{ fontSize: '0.9rem', color: '#6c757d', margin: '0' }}>
                        <strong>Masjid Al Mustaghfirin, Bayan Lepas</strong><br />
                        Jalan Sultan Azlan Shah, Kampung Sungai Tiram,<br />
                        11900 Bayan Lepas, Pulau Pinang<br />
                        <br />
                        Telefon: +6019-320-0799
                    </p>
                </div>
            </div>
        </div>
    );
};

export default MaintenancePage;
