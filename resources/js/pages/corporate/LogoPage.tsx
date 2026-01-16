import React from 'react';

const LogoPage: React.FC = () => {
    return (
        <div className="row">
            <div className="col">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Makna Logo</b></h6>
                <div className="col-md-12 text-center">
                    <div style={{ minWidth: '500px', maxWidth: '900px', margin: 'auto', display: 'block' }}>
                        <img src="/images/masjid.jpeg" alt="Image Description" />
                    </div>
                </div>
                <div className="mt-5 mb-5">
                    <p> Halaman ini sedang dibangunkan</p>
                </div>
            </div>
        </div>
    );
};

export default LogoPage;
